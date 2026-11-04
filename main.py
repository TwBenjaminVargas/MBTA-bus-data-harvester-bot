import modules.MBTApiClient as mbta_client
import modules.ProcesadorGTFS as gtfs_processor
import os
import json
from dotenv import load_dotenv

load_dotenv()

mbta_token = os.getenv("MBTA_TOKEN", "no_token_provided")

mbta_client_instance = mbta_client.MBTAApiClient(token=mbta_token)
gtfs_processor_instance = gtfs_processor.ProcesadorGTFS(zip_path="GTFS/GTFS.zip", tablas=["shapes", "routes"])

vehiculos = mbta_client_instance.obtener_vehiculos_por_ruta("1")
vehiculos_data = vehiculos.get("data", [])

if vehiculos_data:
    # 1. Corregido el typo: "attributes" en lugar de "atributes"
    bus_1_attributes = vehiculos_data[0].get("attributes", {})
    
    # 2. Obtenemos latitud y longitud de forma segura
    lat = bus_1_attributes.get("latitude")
    lon = bus_1_attributes.get("longitude")

    if lat is not None and lon is not None:
        distancia_en_traza = gtfs_processor_instance.calcular_distancia_en_traza(shape_id="010154", lon=lon, lat=lat)
        distancia_fuera_de_traza = gtfs_processor_instance.calcular_distancia_fuera_de_traza(shape_id="010154", lon=lon, lat=lat)

        print(f"Vehículo 1: {json.dumps(bus_1_attributes, indent=2)}")
        print(f"Coorendadas: {lon},{lat}")
        print(f"Distancia en traza: {distancia_en_traza:.2f} km")
        print(f"Distancia fuera de traza: {distancia_fuera_de_traza:.2f} m")
    else:
        print("El vehículo no cuenta con coordenadas de latitud/longitud válidas.")
else:
    print("No se encontraron vehículos activos para la ruta indicada.")