import requests
import json

class MBTApiClient:
    def __init__(self, token=None, base_url="https://api-v3.mbta.com"):
        """
        Instancia el cliente de la API.
        Permite pasar el token (API Key) al instanciar.
        """
        self.base_url = base_url
        self.session = requests.Session()
        
        # Configurar la autenticación mediante el header x-api-key si se provee un token
        if token:
            self.session.headers.update({"x-api-key": token})
        
        # Lista para almacenar el historial de respuestas JSON
        self.json_respuestas = []

    def obtener_vehiculos_por_ruta(self, route_id):
        """
        Obtiene los vehículos sobre una ruta en base al ID de la ruta.
        Almacena y devuelve la respuesta JSON.
        """
        endpoint = f"{self.base_url}/vehicles"
        
        # Parámetro para filtrar los vehículos por ruta
        params = {
            "filter[route]": route_id
        }
        
        response = self.session.get(endpoint, params=params)
        response.raise_for_status()
        
        datos_json = response.json()
        
        # Almacenar la respuesta JSON en la instancia
        self.json_respuestas.append({
            "route_id": route_id,
            "data": datos_json
        })
        
        return datos_json


# Ejemplo de uso:
if __name__ == "__main__":
    # Instanciar pasando el token
    cliente = MBTApiClient(token="TU_TOKEN_AQUI")
    
    # Consultar vehículos para la ruta "Red" (ejemplo)
    vehiculos = cliente.obtener_vehiculos_por_ruta("Red")
    
    print(json.dumps(vehiculos, indent=2))