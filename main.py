import os
import sys
import time
import argparse
import logging
from typing import Dict, Any
from dotenv import load_dotenv
from geopy.distance import geodesic
from datetime import datetime
import zoneinfo

# Importación de módulos locales
import modules.MBTApiClient as mbta_client
import modules.ProcesadorGTFS as gtfs_processor
import modules.TimeAPIClient as time_api_client
import modules.OpenMeteoClient as open_meteo_client
import modules.TomTomClient as tomtom_client
import modules.SupaBase as supabase_module

# Carga de variables de entorno (.env)
load_dotenv()

# Configuración de colores para los logs mediante Formatter de Logging
class ColoredFormatter(logging.Formatter):
    COLOR_CODES = {
        logging.INFO: "\033[92m",     # Verde
        logging.WARNING: "\033[93m",  # Amarillo
        logging.ERROR: "\033[91m",    # Rojo
        logging.CRITICAL: "\033[95m", # Púrpura/Rojo Intenso
    }
    RESET = "\033[0m"

    def format(self, record):
        color = self.COLOR_CODES.get(record.levelno, self.RESET)
        message = super().format(record)
        return f"{color}{message}{self.RESET}"

def setup_logger(log_level_str: str) -> logging.Logger:
    level = getattr(logging, log_level_str.upper(), logging.INFO)
    logger = logging.getLogger("HarvesterBot")
    logger.setLevel(level)
    
    if not logger.handlers:
        ch = logging.StreamHandler(sys.stdout)
        ch.setLevel(level)
        formatter = ColoredFormatter("%(asctime)s - [%(levelname)s] - %(message)s")
        ch.setFormatter(formatter)
        logger.addHandler(ch)
        
    return logger

def parse_args():
    parser = argparse.ArgumentParser(description="Harvester Bot para recolección de métricas de transporte.")
    parser.add_argument("--gtfs", type=str, default="GTFS/GTFS.zip", help="ruta al archivo GTFS.zip (por defecto: GTFS/GTFS.zip)")
    parser.add_argument("--frecuencia", type=int, default=30, help="Frecuencia de consulta en segundos (por defecto: 30)")
    parser.add_argument("--umbral-desvio", type=float, default=70.0, help="Umbral de distancia fuera de traza en metros para definir desvío (por defecto: 70.0m)")
    parser.add_argument("--umbral-trafico-km", type=float, default=1.0, help="Distancia mínima recorrida en km para volver a consultar la API de TomTom (por defecto: 1.0 km)")
    parser.add_argument("--log-level", type=str, default="INFO", choices=["DEBUG", "INFO", "WARNING", "ERROR"], help="Nivel de logs (DEBUG, INFO, WARNING, ERROR)")
    return parser.parse_args()

def main():
    args = parse_args()
    logger = setup_logger(args.log_level)
    
    logger.info("=== Iniciando Harvester Bot ===")
    
    # Variables de entorno
    mbta_token = os.getenv("MBTA_TOKEN", None)
    tomtom_key = os.getenv("TOMTOM_API_KEY", None)
    db_url = os.getenv("DB_URL", None)
    db_password = os.getenv("DB_PASSWORD",None)
    
    if not db_url:
        logger.error("No se encontró la variable de entorno DB_URL / DB_URL")
        sys.exit(1)

    # Inicialización de clientes y dataframes
    logger.info("Inicializando cliente MBTApiClient...")
    mbta_api = mbta_client.MBTApiClient(token=mbta_token)
    
    logger.info("Cargando DataFrames de GTFS...")
    try:
        gtfs_proc = gtfs_processor.ProcesadorGTFS(zip_path=args.gtfs, tablas=["shapes", "routes", "trips"])
        logger.info("DataFrames de GTFS cargados exitosamente.")
    except Exception as e:
        logger.error(f"Error cargando DataFrames GTFS: {e}")
        sys.exit(1)

    logger.info("Inicializando servicios auxiliares (TimeAPI, OpenMeteo, TomTom)...")
    time_api = time_api_client.TimeAPIClient()
    open_meteo = open_meteo_client.OpenMeteoClient()
    tomtom_api = tomtom_client.TomTomTrafficClient(api_key=tomtom_key) if tomtom_key else None

    logger.info("Estableciendo conexión a la base de datos Supabase e inicializando tablas...")
    try:
        db = supabase_module.Supabase(db_url=db_url, password=db_password)
        logger.info("Conexión a la base de datos inicializada correctamente.")
    except Exception as e:
        logger.error(f"Error al conectar con Supabase: {e}")
        sys.exit(1)

    # Caché en memoria para optimizar peticiones a TomTom: {bus_id: {'lat': float, 'lon': float, 'data': dict}}
    traffic_cache: Dict[str, Dict[str, Any]] = {}

    logger.info(f"Bot en ejecución. Frecuencia: {args.frecuencia}s | Umbral desvío: {args.umbral_desvio}m | Distancia min. tráfico: {args.umbral_trafico_km}km")
    
    try:
        while True:
            try:
                logger.info("Consultando vehículos en la ruta 1 a MBTA...")
                respuesta = mbta_api.obtener_vehiculos_por_ruta("1")
                vehiculos = respuesta.get("data", [])
                
                if not vehiculos:
                    logger.warning("No se encontraron vehículos activos para la ruta 1.")
                
                for vehiculo in vehiculos:
                    bus_id = vehiculo.get("id")
                    attrs = vehiculo.get("attributes", {})
                    lat = attrs.get("latitude")
                    lon = attrs.get("longitude")
                    direction_id = attrs.get("direction_id")
                    
                    if lat is None or lon is None:
                        logger.warning(f"Vehículo ID {bus_id} omitido por falta de coordenadas.")
                        continue

                    #Lógica según direction_id para GTFS
                    if direction_id == 0:
                        shape_id = "010154"
                        route_pattern_id = "1-_-0"
                    elif direction_id == 1:
                        shape_id = "010156"
                        route_pattern_id = "1-_-1"
                    else:
                        shape_id = None
                        route_pattern_id = None

                    # Cálculo de traza y desvío
                    distancia_en_traza = None
                    distancia_fuera_traza = None
                    es_desvio = None
                    
                    if shape_id:
                        try:
                            distancia_en_traza = gtfs_proc.calcular_distancia_en_traza(shape_id, lon, lat)
                            distancia_fuera_traza = gtfs_proc.calcular_distancia_fuera_de_traza(shape_id, lon, lat)
                            es_desvio = distancia_fuera_traza > args.umbral_desvio
                            if es_desvio:
                                logger.warning(f"Bus {bus_id} está fuera de traza: {distancia_fuera_traza:.2f}m (> {args.umbral_desvio}m)")
                        except Exception as e:
                            logger.error(f"Error procesando GTFS para bus {bus_id} con shape_id {shape_id}: {e}")

                    # Obtención de Tiempo Local
                    date_time = None
                    timezone = None
                    try:
                        tiempo_res = time_api.get_time_by_coordinates(lat, lon)
                        if tiempo_res:
                            date_time = tiempo_res.get("date_time")
                            timezone = tiempo_res.get("timezone")
                            logger.debug(f"Bus {bus_id} | Hora local: {date_time} | Zona horaria: {timezone}")
                    except Exception as e:
                        logger.warning(f"Error obteniendo tiempo local para bus {bus_id}. Se utilizara FALLBACK: {e}")
                        now = datetime.now(zoneinfo.ZoneInfo("America/New_York"))
                        date_time = now.isoformat()
                        timezone = "America/New_York"

                    # Obtención de Clima
                    temp_2m = None
                    precip = None
                    rel_hum = None
                    w_code = None
                    is_day = None
                    try:
                        clima_res = open_meteo.get_current_weather(lat, lon)
                        if clima_res and "current" in clima_res:
                            curr = clima_res["current"]
                            temp_2m = curr.get("temperature_2m")
                            precip = curr.get("precipitation")
                            rel_hum = curr.get("relative_humidity_2m")
                            w_code = int(curr.get("weather_code")) if curr.get("weather_code") is not None else None
                            is_day = bool(curr.get("is_day")) if curr.get("is_day") is not None else None
                    except Exception as e:
                        logger.warning(f"Error obteniendo clima para bus {bus_id}: {e}")

                    # Estrategia de Caché e Integración con TomTom (Optimización de API)
                    traffic_data = None
                    hacer_peticion_tomtom = False

                    if tomtom_api:
                        if bus_id not in traffic_cache:
                            hacer_peticion_tomtom = True
                        else:
                            prev_pos = traffic_cache[bus_id]
                            dist_recorrida_km = geodesic((prev_pos['lat'], prev_pos['lon']), (lat, lon)).km
                            
                            # Criterio: Se consulta TomTom SI recorrió más del umbral Y la bandera es_desvio NO está encendida
                            if dist_recorrida_km > args.umbral_trafico_km and not es_desvio:
                                hacer_peticion_tomtom = True
                            else:
                                traffic_data = prev_pos['data']

                        if hacer_peticion_tomtom:
                            try:
                                logger.info(f"Consultando API TomTom para bus {bus_id}...")
                                resp_tomtom = tomtom_api.get_flow_segment_data(lat, lon)
                                traffic_data = resp_tomtom.get("flowSegmentData", {}) if resp_tomtom else None
                                # Actualizar caché
                                traffic_cache[bus_id] = {
                                    'lat': lat,
                                    'lon': lon,
                                    'data': traffic_data
                                }
                            except Exception as e:
                                logger.warning(f"Error al obtener datos de tráfico de TomTom para bus {bus_id}: {e}")

                    # Extraer atributos de TomTom
                    curr_speed = traffic_data.get("currentSpeed") if traffic_data else None
                    ff_speed = traffic_data.get("freeFlowSpeed") if traffic_data else None
                    curr_travel = traffic_data.get("currentTravelTime") if traffic_data else None
                    ff_travel = traffic_data.get("freeFlowTravelTime") if traffic_data else None
                    conf = traffic_data.get("confidence") if traffic_data else None
                    closure = traffic_data.get("roadClosure") if traffic_data else None

                    # Mapeo a la estructura final de la Base de Datos
                    metrica = {
                        "bearing": attrs.get("bearing"),
                        "current_status": attrs.get("current_status"),
                        "direction_id": direction_id,
                        "occupancy_status": attrs.get("occupancy_status"),
                        "latitude": lat,
                        "longitude": lon,
                        "bus_id": str(bus_id) if bus_id else None,
                        "route_id": "1",
                        "route_pattern_id": route_pattern_id,
                        "shape_id": shape_id,
                        "agency_id": "1",
                        "line_id": "line-1",
                        "distancia_en_traza": distancia_en_traza,
                        "distancia_fuera_traza": distancia_fuera_traza,
                        "es_desvio": es_desvio,
                        "date_time": date_time,
                        "timezone": timezone,
                        "temperature_2m": temp_2m,
                        "precipitation": precip,
                        "relative_humidity_2m": rel_hum,
                        "weather_code": w_code,
                        "is_day": is_day,
                        "current_speed": curr_speed,
                        "free_flow_speed": ff_speed,
                        "current_travel_time": curr_travel,
                        "freeflow_travel_time": ff_travel,
                        "confidence": conf,
                        "road_closure": closure
                    }

                    # Guardar en Base de Datos y Loggear
                    db.registrar_metrica(metrica)
                    logger.info(f"Métrica registrada para Bus {bus_id} | Traza: {distancia_en_traza:.2f}km | Fuera: {distancia_fuera_traza:.2f}m | Desvío: {es_desvio}")

            except Exception as e:
                logger.error(f"Error inesperado en el ciclo principal: {e}")

            time.sleep(args.frecuencia)
    except KeyboardInterrupt:
        logger.info("Bot detenido manualmente por el usuario.")
if __name__ == "__main__":
    main()