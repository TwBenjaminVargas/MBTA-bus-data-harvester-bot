import modules.MBTApiClient as mbta_client
import os
import json
from dotenv import load_dotenv

load_dotenv()

mbta_token = os.getenv("MBTA_TOKEN", "no_token_provided")

mbta_client_instance = mbta_client.MBTAApiClient(token=mbta_token)

vehiculos = mbta_client_instance.obtener_vehiculos_por_ruta("1")
print(json.dumps(vehiculos, indent=2))