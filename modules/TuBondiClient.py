#!/usr/bin/env python3

"""
TuBondiClient (Ecosistema de Recolección y Telemetría Tu Bondi)
===============================================================

* Versión: 1.0.0
* Autor: Benjamin Vargas (bvargas@alumnos.iua.edu.ar)
* Fecha: 2026-10-29
* Licencia: Uso Académico / Investigación


DESCRIPCIÓN GENERAL
-------------------
Cliente en Python diseñado para interactuar mediante Web Scraping / API 
Reverse Engineering con la plataforma de transporte urbano de Córdoba 
("Tu Bondi"). Automatiza la autenticación, el manejo de cookies, la resolución 
de tokens dinámicos y la recolección masiva de datos en tiempo real (coordenadas 
GPS de unidades, estado de rutas, catálogos de empresas y trazas vectoriales).


ESTRUCTURAS DE DATOS E INTERNAS (Atributos de Clase)
----------------------------------------------------
* `self.conf` (str): Configuración regional/jurisdicción del servicio (por defecto "cbaciudad").
* `self.site_entry_url` (str): URL base para el handshake y obtención del token de sesión.
* `self.cmd_url` (str): Endpoint único de procesamiento de comandos POST.
* `self.device_flaker` (str): Hash SHA256 aleatorio generado por sesión para simular la huella digital del dispositivo (fingerprint).
* `self.operacion_token` (str): Token de seguridad extraído dinámicamente de la interfaz web para validar peticiones POST ante el backend.
* `self.session` (requests.Session): Objeto de sesión HTTP persistente encargado de mantener las cookies (ej. PHPSESSID) y encabezados de navegación.
* `self.lineas_y_rutas_JSON` (dict): Caché en memoria conteniendo el catálogo completo de líneas, rutas, empresas y paradas del sistema.
* `self.mapa_rutas_clientes` (dict): Índice de mapeo en memoria para resolver la relación unívoca entre `ruta_id` / `linea_nombre` y su correspondiente `cliente_id` (empresa).


PETICIONES HTTP REALIZADAS (Endpoints y Comandos)
-------------------------------------------------

1. Handshake de Inicialización (Sesión y Autenticación):
    * **GET** `https://micronauta4.dnsalias.net/usuario/urbano.php?conf=cbaciudad`
      Propósito: Captura inicial del token dinámico `operacion` vía Regex.
    * **POST** `https://micronauta4.dnsalias.net/usuario/urbano2_cmd.php?cmd=vista`
      Payload: `{"fp": <device_flaker>, "conf": <conf>}`
      Propósito: Registrar fingerprint y validar cookie PHPSESSID.

2. Consultas al Endpoint General de Comandos (`cmd_url`):
    * **POST** (`cmd="lineasyrutas"`)
      Payload: `{"cmd": "lineasyrutas", "operacion": ..., "conf": ...}`
      Propósito: Descarga el catálogo global de líneas, rutas y empresas.
    * **POST** (`cmd="seleccionatraza"`)
      Payload: `{"cmd": "seleccionatraza", "ruta": <ruta_id>, "cliente_id": <cliente_id>, ...}`
      Propósito: Obtiene los puntos de la traza vectorial (coordenadas) y las paradas.
    * **POST** (`cmd="consultacocheporruta"`)
      Payload: `{"cmd": "consultacocheporruta", "ruta": <ruta_id>, "coche": "0", "cliente": <cliente_id>, ...}`
      Propósito: Retorna la telemetría en tiempo real (posiciones GPS, rumbo, velocidad) de las unidades en marcha.


EXCEPCIONES CONTROLADAS
-----------------------
* `TuBondiError`: Excepción base del módulo.
* `TuBondiAuthError`: Falla en el protocolo de enlace (Handshake) o extracción de tokens.
* `TuBondiConnectionError`: Errores de red, HTTP status != 200 o fallo en el decorador `@auto_reconnect`.


DECORADORES DE COMPORTAMIENTO
-----------------------------
* `@auto_reconnect`: Captura respuestas HTTP 408 (Session Timeout), reinicializa el handshake de forma transparente y reintenta la petición sin perder la continuidad del flujo.
"""

import os
import re
import json
import random
import hashlib
import logging
import functools
import requests

# Configuración del logger del módulo.
logger = logging.getLogger(__name__)




# ==============================================================================
# EXCEPCIONES DE CLASE
# ==============================================================================
class TuBondiError(Exception):
    """Excepción base para todos los errores del cliente TuBondi."""
    pass

class TuBondiConnectionError(TuBondiError):
    """Lanzada cuando ocurre un error de red, timeout o el servidor no responde (ej. 500)."""
    pass

class TuBondiAuthError(TuBondiError):
    """Lanzada cuando falla el handshake de sesión o la obtención de tokens."""
    pass


# ==============================================================================
# DECORADOR DE RECONEXIÓN
# ==============================================================================
def auto_reconnect(func):
    """Decorador que envuelve peticiones HTTP para manejar caducidad de sesión (408).
    
    Si el servidor devuelve HTTP 408, reinicializa la sesión y reintenta la petición.
    Si hay un error de red o un código de estado distinto de 200, lanza TuBondiConnectionError.
    Si todo es exitoso, parsea y retorna el JSON directamente.
    """
    @functools.wraps(func)
    def wrapper(self, *args, **kwargs):
        try:
            logger.debug(f"Ejecutando petición a través de {func.__name__}")
            res = func(self, *args, **kwargs)
        except requests.exceptions.RequestException as e:
            logger.debug(f"Falla de red inicial en {func.__name__}: {e}")
            raise TuBondiConnectionError(f"Error de red conectando con Tu Bondi: {e}") from e

        # Verificación de caducidad de sesión
        if res.status_code == 408:
            logger.debug("Sesión caducada (HTTP 408). Iniciando protocolo de reconexión...")
            self._inicializar_sesion()
            try:
                # Reintento con la nueva sesión
                res = func(self, *args, **kwargs)
            except requests.exceptions.RequestException as e:
                logger.debug(f"Falla de red durante el reintento en {func.__name__}: {e}")
                raise TuBondiConnectionError(f"Error de red tras reconexión: {e}") from e

        # Verificación de éxito final
        if res.status_code != 200:
            logger.debug(f"El backend rechazó la consulta con HTTP {res.status_code}. Respuesta: {res.text}")
            raise TuBondiConnectionError(f"El servidor retornó error HTTP {res.status_code}")

        # Retorna directamente el diccionario parseado
        try:
            return res.json()
        except ValueError as e:
            logger.debug(f"Error parseando JSON de la respuesta: {e}")
            raise TuBondiConnectionError("El servidor no devolvió un JSON válido.") from e

    return wrapper



class TuBondiClient:
    def __init__(self, conf="cbaciudad"):
        """Inicializa el cliente de Tu Bondi y precarga los catálogos en memoria."""
        self.conf = conf
        self.site_entry_url = f"https://micronauta4.dnsalias.net/usuario/urbano.php?conf={self.conf}"
        self.cmd_url = "https://micronauta4.dnsalias.net/usuario/urbano2_cmd.php"
        
        self.device_flaker = hashlib.sha256(os.urandom(32)).hexdigest()
        self.operacion_token = "29135374953728091548"  # Fallback
        self.session = None

        # Inicialización y caché
        self._inicializar_sesion()
        self.lineas_y_rutas_JSON = None
        
        # Datos iniciales necesarios para resolver
        logger.debug("Precargando catálogos en memoria durante inicialización...")
        self.obtener_lineas_y_rutas(refresh=True)
        self.mapa_rutas_clientes = self._build_map_rutas_cliente()

    def _inicializar_sesion(self):
        """Ejecuta el handshake con el backend para obtener la cookie PHPSESSID y el token operacion."""
        logger.debug("Iniciando nueva sesión HTTP...")
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
            logger.debug(f"Petición GET a {self.site_entry_url}")
            res_entry = self.session.get(self.site_entry_url, timeout=10)
            res_entry.raise_for_status()
            
            tokens = re.findall(r"operacion\s*[:=]\s*['\"]?(\d{15,22})['\"]?", res_entry.text)
            if tokens:
                self.operacion_token = tokens[0]
                logger.debug(f"Token dinámico obtenido: {self.operacion_token}")
            else:
                logger.debug("Token dinámico no encontrado. Se usará fallback.")

            logger.debug("Enviando fingerprint POST a cmd=vista...")
            res_vista = self.session.post(
                f"{self.cmd_url}?cmd=vista",
                data={"fp": self.device_flaker, "conf": self.conf},
                headers={"Content-Type": "application/x-www-form-urlencoded"},
                timeout=10
            )
            res_vista.raise_for_status()

            if "PHPSESSID" in self.session.cookies:
                logger.debug("PHPSESSID almacenado correctamente.")
            else:
                logger.debug("Advertencia: No se detectó PHPSESSID en la sesión.")

        except Exception as e:
            logger.debug(f"Fallo crítico en handshake: {e}", exc_info=True)
            raise TuBondiAuthError(f"No se pudo inicializar la sesión con el servidor: {e}") from e

    @auto_reconnect
    def _post(self, payload: dict) -> requests.Response:
        """Método base protegido para unificar peticiones POST al endpoint de comandos.
        El decorador @auto_reconnect captura la respuesta de este método.
        """
        # Aseguramos que el payload siempre use el token más reciente
        payload["operacion"] = self.operacion_token
        payload["conf"] = self.conf
        
        logger.debug(f"Enviando POST payload: {payload}")
        return self.session.post(
            self.cmd_url, 
            data=payload, 
            headers={"Content-Type": "application/x-www-form-urlencoded"},
            timeout=12
        )

    def _build_map_rutas_cliente(self) -> dict:
        """Construye un mapa unívoco para resolver a qué empresa pertenece una ruta."""
        mapa = {}
        if self.lineas_y_rutas_JSON and "lineas" in self.lineas_y_rutas_JSON:
            for linea in self.lineas_y_rutas_JSON["lineas"]:
                cliente_id = str(linea.get("cliente", ""))
                linea_nombre = str(linea.get("linea_nombre", "")).strip()

                for ruta in linea.get("rutas", []):
                    ruta_id = str(ruta.get("ruta_id", ""))
                    if ruta_id and cliente_id:
                        mapa[f"{linea_nombre}_{ruta_id}"] = cliente_id
                        mapa[ruta_id] = cliente_id  # Fallback directo
        logger.debug(f"Se mapearon {len(mapa)} claves de rutas a clientes.")
        return mapa

    def obtener_cliente_de_ruta(self, ruta: str | int, linea: str = None) -> str:
        """Resuelve dinámicamente el cliente_id asociado a una ruta.

        Args:
            ruta (str | int): ID o código de la ruta.
            linea (str, optional): Nombre de la línea para resolver ambigüedades.

        Returns:
            str: Identificador numérico de la empresa (cliente_id).

        Raises:
            TuBondiError: Si no se puede determinar la empresa para la ruta/línea.
        """
        ruta_key = str(ruta).strip()

        # Búsqueda por clave compuesta unívoca (Línea + Ruta)
        if linea:
            clave_compuesta = f"{str(linea).strip()}_{ruta_key}"
            if clave_compuesta in self.mapa_rutas_clientes:
                return self.mapa_rutas_clientes[clave_compuesta]

        # Búsqueda por ID de ruta directo (Fallback)
        if ruta_key in self.mapa_rutas_clientes:
            return self.mapa_rutas_clientes[ruta_key]

        # Si no existe en el catálogo, lanzamos la excepción
        linea_info = f" de la línea '{linea}'" if linea else ""
        raise TuBondiError(
            f"No se pudo determinar el cliente_id para la ruta '{ruta_key}'{linea_info}. "
            "Asegurate de que la ruta exista en el catálogo o especifica la línea correspondiente."
        )

    def obtener_lineas_y_rutas(self, refresh=False) -> dict:
        """Obtiene la totalidad de líneas, rutas, grupos y clientes del sistema."""
        if not refresh and self.lineas_y_rutas_JSON:
            return self.lineas_y_rutas_JSON

        logger.debug("Solicitando catálogo de líneas y rutas al servidor...")
        payload = {"cmd": "lineasyrutas"}
        datos = self._post(payload)
        
        self.lineas_y_rutas_JSON = datos
        self.mapa_rutas_clientes = self._build_map_rutas_cliente()
        return datos

    def obtener_nombres_clientes(self) -> dict:
        """Extrae el mapeo oficial de ID de Empresa (Cliente) a su Nombre Comercial.
        
        Returns:
            dict: Mapeo { "411": "CONIFERAL", "408": "TAMSE", ... }
        """
        catalogo = self.obtener_lineas_y_rutas()
        mapa_nombres = {}
        
        # El backend suele enviar el nodo "clientes"
        for cliente_obj in catalogo.get("clientes", []):
            c_id = str(cliente_obj.get("id", "")).strip()
            c_nombre = str(cliente_obj.get("nombre", cliente_obj.get("cliente_nombre", "Desconocido"))).strip()
            
            if c_id:
                mapa_nombres[c_id] = c_nombre
                
        logger.debug(f"Nombres de empresas extraídos: {mapa_nombres}")
        return mapa_nombres

    def consultar_coche_por_ruta(self, ruta, coche="0", parada_seleccionada="", cliente=None) -> dict:
        """Consulta la posición GPS y telemetría en tiempo real de los coches de una ruta.
        
        Args:
            ruta (str): Identificador de la ruta.
            coche (str): Unidad específica (0 para todas).
            parada_seleccionada (str): ID de parada para predecir tiempos.
            cliente (str, opcional): ID de la empresa. Si es None, se autodetecta.
            
        Raises:
            TuBondiConnectionError: Si hay fallos de red o de servidor.
        """
        cliente_final = str(cliente) if cliente is not None else self.obtener_cliente_de_ruta(ruta)
        
        payload = {
            "cmd": "consultacocheporruta",
            "ruta": str(ruta),
            "coche": str(coche),
            "cliente": cliente_final,
            "parada_seleccionada": str(parada_seleccionada)
        }
        logger.debug(f"Consultando telemetría para ruta={ruta}, cliente={cliente_final}")
        return self._post(payload)

    def obtener_traza_por_ruta(self, ruta, cliente=None) -> dict:
        """Obtiene las coordenadas geográficas del recorrido y ubicación de paradas."""
        cliente_final = str(cliente) if cliente is not None else self.obtener_cliente_de_ruta(ruta)
        
        payload = {
            "cmd": "seleccionatraza",
            "ruta": str(ruta),
            "cliente_id": cliente_final
        }
        logger.debug(f"Consultando traza vectorial para ruta={ruta}, cliente={cliente_final}")
        return self._post(payload)

    def obtener_rutas_por_linea(self, linea: str = None) -> dict:
        """Agrupa las rutas del sistema según el nombre de la línea."""
        catalogo = self.obtener_lineas_y_rutas()
        mapeo_lineas = {}

        for elemento in catalogo.get("lineas", []):
            linea_nombre = str(elemento.get("linea_nombre", "")).strip()
            if linea_nombre:
                mapeo_lineas[linea_nombre] = elemento.get("rutas", [])

        if linea:
            return {str(linea): mapeo_lineas.get(str(linea), [])}
        return mapeo_lineas

    def obtener_lineas_por_cliente(self, cliente: str = None) -> dict:
        """Agrupa las líneas del sistema según su ID de empresa (cliente_id)."""
        catalogo = self.obtener_lineas_y_rutas()
        mapeo_clientes = {}

        for l in catalogo.get("lineas", []):
            cliente_id = str(l.get("cliente", ""))
            if cliente_id not in mapeo_clientes:
                mapeo_clientes[cliente_id] = []
            mapeo_clientes[cliente_id].append(l)

        if cliente:
            return {str(cliente): mapeo_clientes.get(str(cliente), [])}
        return mapeo_clientes


if __name__ == "__main__":
    logging.basicConfig(
        level=logging.DEBUG,
        format="%(asctime)s [%(levelname)s]: %(message)s",
        datefmt="%Y-%m-%d %H:%M:%S"
    )

    print("\n==================================================")
    print("   INICIANDO MÓDULO TU BONDI - MODO TEST")
    print("==================================================\n")

    try:
        # Instanciación (Llama a inicialización y carga de catálogos)
        bot = TuBondiClient()
        
        # Testeo de obtención de nombres de empresas
        print("\n--- EMPRESAS OPERADORAS ---")
        empresas = bot.obtener_nombres_clientes()
        for c_id, c_name in empresas.items():
            print(f"ID: {c_id} -> {c_name}")
            
        # Testeo de mapas en memoria
        mapa_clientes_obj = bot.obtener_lineas_por_cliente()
        
        if not mapa_clientes_obj:
            raise ValueError("No se pudieron cargar las líneas por cliente.")

        # Selecciones aleatorias para pruebas dinámicas
        cliente_azar = random.choice(list(mapa_clientes_obj.keys()))
        nombre_empresa = empresas.get(cliente_azar, f"Empresa {cliente_azar}")
        
        lineas_empresa = mapa_clientes_obj[cliente_azar]
        linea_azar = random.choice(lineas_empresa)
        linea_nombre = linea_azar.get("linea_nombre", "Desconocida")
        
        rutas_linea = linea_azar.get("rutas", [])
        if not rutas_linea:
            print(f"\nLa línea {linea_nombre} no tiene rutas. Terminando test.")
            exit(0)
            
        ruta_azar = random.choice(rutas_linea)
        ruta_id = str(ruta_azar.get("ruta_id"))
        ruta_nombre = ruta_azar.get("ruta_nombre", "")
        
        print("\n--- SELECCIÓN ALEATORIA DE TEST ---")
        print(f"Empresa: {nombre_empresa} (ID {cliente_azar})")
        print(f"Línea  : {linea_nombre}")
        print(f"Ruta   : {ruta_nombre} (ID {ruta_id})")
        
        # Testeo de traza
        print("\n--- TEST: OBTENER TRAZA ---")
        traza_data = bot.obtener_traza_por_ruta(ruta=ruta_id, cliente=cliente_azar)
        paradas = traza_data.get("paradas", [])
        print(f"Se obtuvieron {len(paradas)} paradas para esta ruta.")
        if paradas:
            print(f"Ejemplo Parada 1: {paradas[0].get('parada_nombre', 'Sin Nombre')}")

        # Testeo de Telemetría (Coches)
        print("\n--- TEST: CONSULTA TELEMETRÍA DE COCHES ---")
        telemetria_data = bot.consultar_coche_por_ruta(ruta=ruta_id, cliente=cliente_azar)
        coches = telemetria_data.get("coches", [])
        
        if coches:
            print(f"¡Éxito! Hay {len(coches)} colectivos circulando en la ruta {ruta_id}.")
            coche_ejemplo = coches[0]
            print("\nDatos del primer coche detectado:")
            print(json.dumps(coche_ejemplo, indent=2, ensure_ascii=False))
        else:
            print(f"Consulta exitosa, pero no hay coches circulando en este momento para la ruta {ruta_id}.")

    except TuBondiAuthError as e:
        print(f"\n[ERROR FATAL DE AUTENTICACIÓN] {e}")
    except TuBondiConnectionError as e:
        print(f"\n[ERROR DE CONEXIÓN] {e}")
    except Exception as e:
        print(f"\n[ERROR INESPERADO] {e}")

    print("\n==================================================")
    print("   TEST FINALIZADO")
    print("==================================================")