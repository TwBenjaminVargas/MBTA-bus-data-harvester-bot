import requests
from typing import Dict, Any

class TomTomTrafficClient:
    """
    Wrapper para la API de TomTom Maps - Traffic.
    """
    
    def __init__(self, api_key: str):
        """
        Instancia el cliente con la API key de TomTom.
        """
        self.api_key = api_key
        self.base_url = "https://api.tomtom.com"

    def get_flow_segment_data(self, 
                              latitude: float, 
                              longitude: float, 
                              zoom: int = 10, 
                              style: str = "relative0", 
                              unit: str = "KMPH") -> Dict[str, Any]:
        """
        Obtiene los datos del segmento de flujo de tráfico más cercano a las coordenadas dadas.

        :param latitude: Latitud del punto.
        :param longitude: Longitud del punto.
        :param zoom: Nivel de zoom de la cámara (0 a 22). Afecta la visibilidad de calles secundarias.
        :param style: Estilo de renderizado para calcular las velocidades (ej. 'relative0', 'absolute').
        :param unit: Unidad de velocidad, 'KMPH' o 'MPH'.
        :return: Diccionario con la respuesta JSON de la API.
        """
        # El endpoint de Flow Segment Data utiliza la versión 4
        version_number = 4
        response_format = "json"
        
        endpoint_url = f"{self.base_url}/traffic/services/{version_number}/flowSegmentData/{style}/{zoom}/{response_format}"
        
        # Parámetros de la consulta (query string)
        params = {
            "key": self.api_key,
            "point": f"{latitude},{longitude}",
            "unit": unit
        }
        
        response = requests.get(endpoint_url, params=params)
        
        # Lanza una excepción si hay un error en la petición HTTP (400, 403, etc.)
        response.raise_for_status()
        
        return response.json()

# --- Ejemplo de uso ---
if __name__ == "__main__":
    # Reemplaza con tu API KEY real
    API_KEY = "Tu_API_KEY_Aquí"
    
    tomtom = TomTomTrafficClient(api_key=API_KEY)
    
    try:
        # Coordenadas de ejemplo 
        traffic_data = tomtom.get_flow_segment_data(latitude=42.37278747558594, longitude=-71.110571)
        print("Datos de tráfico obtenidos con éxito:")
        print(traffic_data)
    except requests.exceptions.RequestException as e:
        print(f"Error al consultar la API: {e}")