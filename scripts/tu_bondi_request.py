#!/usr/bin/env python3
"""
TuBondiClient - Recolección de telemetría para Tu Bondi (Córdoba)
version 0.1.0 
"""

import os
import re
import json
import hashlib
import logging
import requests


# Instanciamos el logger para esta clase/módulo. 
# Usar __name__ ayuda a saber exactamente de qué archivo proviene cada log.
logger = logging.getLogger(__name__)


class TuBondiClient:
    def __init__(self, conf="cbaciudad"):
        self.conf = conf
        
        # URL exacta del documento PHP observada en DevTools
        self.site_entry_url = f"https://micronauta4.dnsalias.net/usuario/urbano.php?conf={self.conf}"
        # Endpoint donde corren las consultas AJAX
        self.cmd_url = "https://micronauta4.dnsalias.net/usuario/urbano2_cmd.php"
        
        # Generación de la huella SHA-256 de 64 caracteres
        self.device_flaker = hashlib.sha256(os.urandom(32)).hexdigest()
        
        # Token fallback
        self.operacion_token = "29135374953728091548"
        self.session = None

        self._inicializar_sesion()
        self.lineas_y_rutas_JSON = None
        self.obtener_lineas_y_rutas(refresh=True)
        self.mapa_clientes_lineas = self.obtener_lineas_por_cliente()
        self.mapa_lineas_rutas = self.obtener_rutas_por_linea()


    def _inicializar_sesion(self):
        """Inicializa el estado de la sesión HTTP y ejecuta el handshake con el backend de Tu Bondi.

        Crea una nueva instancia de `requests.Session`, configura las cabeceras HTTP
        necesarias para emular un navegador real y realiza la secuencia completa de 
        autenticación implícita requerida por el backend PHP.

        El proceso consta de tres pasos principales:
            1. Petición GET a `urbano.php` para cargar el frontend y obtener la respuesta HTML/JS.
            2. Extracción mediante expresiones regulares del token dinámico de transacción 
               `operacion` inyectado por PHP en el cliente.
            3. Petición POST a `cmd=vista` enviando la huella digital SHA-256 (canvas fingerprint)
               para forzar al servidor a invocar `session_start()` y emitir la cookie `PHPSESSID`.

        Side Effects:
            - Instancia y reemplaza el atributo `self.session` con un objeto `requests.Session`.
            - Actualiza `self.operacion_token` si se encuentra un token válido en el HTML.
            - Almacena automáticamente la cookie `PHPSESSID` devuelta por el servidor dentro 
              del cookie jar de `self.session`.

        Raises:
            requests.exceptions.RequestException: Captura y registra cualquier error de red,
                timeout o fallo de conexión durante las peticiones GET o POST sin detener
                bruscamente la ejecución (los detalles se envían al logger con traceback).
        """
        logger.info("Inicializando nueva sesión HTTP en el backend...")
        self.session = requests.Session()
        
        self.session.headers.update({
            "User-Agent": "Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0",
            "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language": "es-AR,es;q=0.8,en-US;q=0.5,en;q=0.3",
            "X-Device-FlakerParam": self.device_flaker,
            "Origin": "https://micronauta4.dnsalias.net",
            "Referer": self.site_entry_url,
            "Host": "micronauta4.dnsalias.net",
            "Connection": "keep-alive"
        })

        try:
            #Petición GET directa al recurso urbano.php
            logger.debug(f"Haciendo GET a la URL de entrada: {self.site_entry_url}")
            res_entry = self.session.get(self.site_entry_url, timeout=10)
            
            #Extracción del token operacion contenido en el JavaScript del PHP
            tokens = re.findall(r"operacion\s*[:=]\s*['\"]?(\d{15,22})['\"]?", res_entry.text)
            if tokens:
                self.operacion_token = tokens[0]
                logger.debug(f"Token 'operacion' capturado dinámicamente: {self.operacion_token}")
            else:
                logger.warning(f"No se pudo extraer 'operacion' del HTML. Usando fallback: {self.operacion_token}")

            #Petición POST a cmd=vista (equivale a la ejecución de fp.js)
            logger.debug("Enviando fingerprint vía POST a cmd=vista...")
            self.session.post(
                f"{self.cmd_url}?cmd=vista",
                data={"fp": self.device_flaker, "conf": self.conf},
                headers={"Content-Type": "application/x-www-form-urlencoded"},
                timeout=10
            )

            cookies = self.session.cookies.get_dict()
            if "PHPSESSID" in cookies:
                logger.debug(f"PHPSESSID registrado en sesión: {cookies['PHPSESSID']}")
            else:
                logger.warning("No se recibió la cookie PHPSESSID en la respuesta del servidor.")

        except Exception as e:
            logger.error(f"Error crítico durante la inicialización de sesión: {e}", exc_info=True)

    def consultar_coche_por_ruta(self, ruta="147", coche="0", parada_seleccionada = "", cliente ="411") -> dict | None:
        """Consulta la posición GPS y telemetría en tiempo real de los coches de una ruta.

        Realiza una petición HTTP POST al endpoint de comandos de Tu Bondi enviando el token
        de transacción `operacion` y la sesión activa. Si el servidor responde con un error 
        `HTTP 408` (sesión caducada/desincronizada), el método reinicializa la sesión y
        reintenta la consulta automáticamente.

        Args:
            ruta (str, optional): Identificador o código de la ruta/línea de colectivo 
                a consultar (ej. "147", "6"). Defaults to "147".
            coche (str, optional): Número de unidad específica. Si se pasa "0", retorna la
                telemetría de todos los coches en circulación sobre la ruta. Defaults to "0".
            parada_seleccionada (str, optional): ID o código de parada específica. 
                Si se omite (""), devuelve la telemetría geográfica estándar. Si se especifica,
                el backend calcula métricas adicionales respecto a esa parada (demora estimada 
                en minutos, hora teórica ajustada de arribo, campo de notificación y banderas 
                de estado como "ya paso" u "OK"). Defaults to "".
            cliente (str, optional): Identificador numérico de la empresa o concesionaria 
                de transporte asociada a la línea (ej. "411" para Coniferal, "408" para TAMSE, 
                "421" para Si Bus, "422" para Sol Bus). Defaults to "411".

        Returns:
            dict | None: Un diccionario parseado desde el JSON del servidor con la clave "coches"
            (lista de objetos con coordenadas, rumbo, rampa, demora y horarios) y metadatos del
            sistema, o `None` si ocurre un error de red o de respuesta HTTP (!= 200).

        Raises:
            requests.exceptions.RequestException: Capturado e informado al logger sin detener 
                la ejecución del programa.
        """
        url = f"{self.cmd_url}?cmd=consultacocheporruta"
        payload = {
            "ruta": str(ruta),
            "coche": str(coche),
            "cliente": str(cliente),
            "parada_seleccionada": str(parada_seleccionada),
            "conf": self.conf,
            "operacion": self.operacion_token
        }

        try:
            logger.debug(f"Consultando telemetría para ruta {ruta}...")
            res = self.session.post(
                url, 
                data=payload, 
                headers={"Content-Type": "application/x-www-form-urlencoded"},
                timeout=12
            )

            if res.status_code == 408:
                logger.warning("El servidor devolvió HTTP 408 (Sesión caducada). Reintentando handshake...")
                self._inicializar_sesion()
                payload["operacion"] = self.operacion_token
                res = self.session.post(
                    url, 
                    data=payload, 
                    headers={"Content-Type": "application/x-www-form-urlencoded"},
                    timeout=12
                )

            if res.status_code == 200:
                logger.info(f"Consulta exitosa para la ruta {ruta}.")
                return res.json()
            else:
                logger.error(f"Error HTTP {res.status_code} devuelto por el backend: {res.text}")
                return None

        except requests.exceptions.RequestException as e:
            logger.error(f"Error de red al consultar la ruta {ruta}: {e}")
            return None

    def obtener_traza_por_ruta(self, ruta="98", cliente="411") -> dict | None:
        """Obtiene el recorrido (traza geográfica) y las paradas de una ruta específica.

        Realiza una petición HTTP POST al comando 'seleccionatraza' de Tu Bondi.
        Si la sesión expiró (HTTP 408), reinicializa la sesión y reintenta automáticamente.

        Args:
            ruta (str, optional): Identificador o código de la ruta/línea de colectivo
                a consultar (ej. "98"). Defaults to "98".
            cliente (str, optional): Identificador numérico de la empresa o concesionaria 
                asociada a la línea (ej. "411", "408", "421", "422"). Mapea al parámetro 
                `cliente_id` esperado por este endpoint. Defaults to "411".

        Returns:
            dict | None: Un diccionario parseado desde el JSON del servidor con las claves:
                - "traza": Lista de coordenadas GPS [lat, lon, orden] que dibujan el recorrido.
                - "paradas": Lista de objetos con los datos y ubicación de cada parada.
                - "color": Código hexadecimal del color asignado a la línea.
                - "sentido": Sentido del recorrido (ej. "I" para Ida, "V" para Vuelta).
                O `None` si la consulta falla por error de red o HTTP != 200.

        Raises:
            requests.exceptions.RequestException: Capturado e informado al logger sin 
                detener la ejecución del programa.
        """

        url = f"{self.cmd_url}?cmd=seleccionatraza"
        payload = {
            "ruta": str(ruta),
            "cliente_id": str(cliente),
            "conf": self.conf
        }

        try:
            logger.debug(f"Consultando traza y paradas para la ruta {ruta}...")
            res = self.session.post(
                url, 
                data=payload, 
                headers={"Content-Type": "application/x-www-form-urlencoded"},
                timeout=15
            )

            if res.status_code == 408:
                logger.warning("El servidor devolvió HTTP 408 (Sesión caducada). Reintentando handshake...")
                self._inicializar_sesion()
                res = self.session.post(
                    url, 
                    data=payload, 
                    headers={"Content-Type": "application/x-www-form-urlencoded"},
                    timeout=15
                )

            if res.status_code == 200:
                logger.info(f"Traza obtenida exitosamente para la ruta {ruta}.")
                return res.json()
            else:
                logger.error(f"Error HTTP {res.status_code} devuelto por el backend: {res.text}")
                return None

        except requests.exceptions.RequestException as e:
            logger.error(f"Error de red al consultar la traza de la ruta {ruta}: {e}")
            return None  

    def obtener_lineas_y_rutas(self, refresh=False) -> dict | None:
        """Obtiene la totalidad de líneas, rutas, grupos y clientes del sistema.

        Realiza una petición HTTP POST al comando 'lineasyrutas' de Tu Bondi.
        Si la sesión expiró (HTTP 408), reinicializa la sesión y reintenta automáticamente.

        Returns:
            dict | None: Un diccionario parseado desde el JSON del servidor con las claves:
                - "lineas": Lista de objetos con líneas, sus rutas asociadas, sentidos y colores.
                - "grupos": Agrupaciones de corredores/líneas por prestador.
                - "clientes": Empresas u operadores del sistema (TAMSE, Coniferal, etc.).
                - "lineascrc": Checksum de validación de la lista de líneas.
                O `None` si la consulta falla por error de red o HTTP != 200.

        Raises:
            requests.exceptions.RequestException: Capturado e informado al logger sin 
                detener la ejecución del programa.
        """
        if not refresh:
            return self.lineas_y_rutas_JSON

        url = f"{self.cmd_url}?cmd=lineasyrutas"
        payload = {
            "conf": self.conf
        }

        try:
            logger.debug("Consultando catálogo completo de líneas y rutas...")
            res = self.session.post(
                url, 
                data=payload, 
                headers={"Content-Type": "application/x-www-form-urlencoded"},
                timeout=15
            )

            if res.status_code == 408:
                logger.warning("El servidor devolvió HTTP 408 (Sesión caducada). Reintentando handshake...")
                self._inicializar_sesion()
                res = self.session.post(
                    url, 
                    data=payload, 
                    headers={"Content-Type": "application/x-www-form-urlencoded"},
                    timeout=15
                )

            if res.status_code == 200:
                logger.info("Catálogo de líneas y rutas obtenido exitosamente.")
                self.lineas_y_rutas_JSON = res.json()
                return res.json()
            else:
                logger.error(f"Error HTTP {res.status_code} devuelto por el backend: {res.text}")
                return None

        except requests.exceptions.RequestException as e:
            logger.error(f"Error de red al consultar catálogo de líneas y rutas: {e}")
            return None

    def obtener_rutas_por_linea(self, linea: str | int = None) -> dict:
        """Agrupa las rutas del sistema según el nombre o ID de la línea a la que pertenecen.

        Consulta el catálogo de `lineasyrutas` y construye un mapeo donde cada clave es
        el identificador/nombre de la línea (ej. "10", "A", "80") y el valor es la lista 
        de sus rutas asociadas (sentido Ida/Vuelta, nombre de trazado, longitud, etc.).

        Args:
            linea (str | int, optional): Nombre o ID público de la línea a filtrar (ej. "10", 
                "A", "80", 10). Si se omite (None), retorna el mapeo de rutas para TODAS las 
                líneas del catálogo. Defaults to None.

        Returns:
            dict: Un diccionario con la estructura:
                {
                    "10": [
                        {"ruta_id": "1", "sentido": "I", "ruta_nombre": "ITUZAINGO A LASALLE", "longitud": "85"},
                        {"ruta_id": "4", "sentido": "V", "ruta_nombre": "LASALLE A ITUZAINGO", "longitud": "84"}
                    ],
                    "A": [...],
                    ...
                }
                Si se filtró por una `linea` específica, retornará:
                {
                    "10": [{"ruta_id": "1", ...}, {"ruta_id": "4", ...}]
                }
                En caso de no encontrar la línea o ante un error, retorna un diccionario vacío `{}`.
        """
        catalogo = self.obtener_lineas_y_rutas()
        if not catalogo or "lineas" not in catalogo:
            logger.error("No se pudo construir el mapeo de rutas por línea debido a un error en el catálogo.")
            return {}

        mapeo_lineas = {}

        for elemento in catalogo["lineas"]:
            # Guardamos la clave por su nombre comercial/público (ej. "10", "A", "C1")
            linea_nombre = str(elemento.get("linea_nombre", "")).strip()
            linea_id = str(elemento.get("linea_id", "")).strip()
            rutas = elemento.get("rutas", [])

            if linea_nombre:
                mapeo_lineas[linea_nombre] = rutas
            
            # También mapeamos la linea_id por si se consulta por el ID interno del servidor
            if linea_id and linea_id not in mapeo_lineas:
                mapeo_lineas[linea_id] = rutas

        if linea is not None:
            linea_key = str(linea).strip()
            if linea_key in mapeo_lineas:
                return {linea_key: mapeo_lineas[linea_key]}
            else:
                logger.warning(f"La línea '{linea_key}' no fue encontrada en el catálogo.")
                return {}

        logger.debug(f"Mapeo de rutas generado exitosamente para {len(mapeo_lineas)} líneas.")
        return mapeo_lineas

    def obtener_lineas_por_cliente(self, cliente: str | int = None) -> dict:
        """Agrupa las líneas y rutas activas del sistema según su ID de cliente (empresa).

        Consulta el catálogo de `lineasyrutas` y organiza las líneas en un diccionario
        donde la clave es el `cliente_id` (en formato string) y el valor es la lista 
        de objetos de líneas pertenecientes a esa empresa prestadora.

        Args:
            cliente (str | int, optional): Si se especifica un ID de cliente (ej. "411", 408),
                retorna únicamente el diccionario mapeado con la lista de líneas pertenecientes 
                a esa empresa. Si se omite (None), retorna el mapeo completo de todos los 
                clientes del sistema. Defaults to None.

        Returns:
            dict: Un diccionario con la estructura:
                {
                    "411": [{"linea_id": "10", "linea_nombre": "10", ...}],
                    "408": [{"linea_id": "1", "linea_nombre": "A", ...}],
                    ...
                }
                Si se filtró por un `cliente` específico, retornará:
                {
                    "411": [{"linea_id": "10", ...}]
                }
                En caso de error en la consulta, retorna un diccionario vacío `{}`.
        """
        catalogo = self.obtener_lineas_y_rutas()
        if not catalogo or "lineas" not in catalogo:
            logger.error("No se pudo construir el mapeo de líneas por cliente debido a un error en el catálogo.")
            return {}

        mapeo_clientes = {}

        for linea in catalogo["lineas"]:
            cliente_id = str(linea.get("cliente"))
            
            if cliente_id not in mapeo_clientes:
                mapeo_clientes[cliente_id] = []
                
            mapeo_clientes[cliente_id].append(linea)

        if cliente is not None:
            cliente_key = str(cliente)
            return {cliente_key: mapeo_clientes.get(cliente_key, [])}

        logger.debug(f"Mapeo generado exitosamente para {len(mapeo_clientes)} clientes/empresas.")
        return mapeo_clientes
    
if __name__ == "__main__":
    import random
    logging.basicConfig(
        level=logging.INFO,
        format="%(asctime)s [%(levelname)s]: %(message)s",
        datefmt="%Y-%m-%d %H:%M:%S"
    )

    print("==================================================")
    print("   TEST TELEMETRÍA (TU BONDI)")
    print("==================================================")

    bot = TuBondiClient()
    mapa_clientes = bot.mapa_clientes_lineas

    if mapa_clientes:
        # 1. Seleccionar un Cliente ID al azar
        cliente_id_azar = random.choice(list(mapa_clientes.keys()))
        lineas_cliente = mapa_clientes[cliente_id_azar]

        # 2. Seleccionar una Línea al azar del cliente elegido
        linea_azar = random.choice(lineas_cliente)
        linea_nombre = linea_azar.get("linea_nombre")
        rutas_linea = linea_azar.get("rutas", [])

        if rutas_linea:
            # 3. Seleccionar una Ruta al azar de la línea elegida
            ruta_azar = random.choice(rutas_linea)
            ruta_id = str(ruta_azar.get("ruta_id"))
            ruta_nombre = ruta_azar.get("ruta_nombre")
            sentido = ruta_azar.get("sentido", "N/D")

            logger.info(f"Cliente/Empresa ID seleccionado : {cliente_id_azar}")
            logger.info(f"Línea seleccionada               : {linea_nombre}")
            logger.info(f"Ruta seleccionada                : ID {ruta_id} ('{ruta_nombre}' - Sentido: {sentido})")

            # 4. Consultar telemetría de los colectivos andando en esa ruta
            telemetria = bot.consultar_coche_por_ruta(ruta=ruta_id, cliente=cliente_id_azar)

            if telemetria and "coches" in telemetria:
                coches_andando = telemetria["coches"]
                
                if coches_andando:
                    # 5. Elegir un colectivo al azar que esté circulando
                    colectivo_azar = random.choice(coches_andando)

                    print("\n" + "="*60)
                    print(f" INFORMACIÓN DEL COLECTIVO EN CIRCULACIÓN (Línea {linea_nombre})")
                    print("="*60)
                    print(json.dumps(colectivo_azar, indent=2, ensure_ascii=False))
                    print("="*60 + "\n")
                else:
                    logger.warning(f"No hay colectivos circulando en este momento para la ruta {ruta_id} (Línea {linea_nombre}).")
            else:
                logger.error("No se obtuvo respuesta de telemetría desde el servidor.")
        else:
            logger.warning(f"La línea {linea_nombre} no tiene rutas configuradas en el catálogo.")
    else:
        logger.error("No se pudo cargar el mapa de clientes desde el catálogo.")