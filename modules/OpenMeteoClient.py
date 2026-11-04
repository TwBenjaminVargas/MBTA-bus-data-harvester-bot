import json

import openmeteo_requests
import requests_cache
from retry_requests import retry

class OpenMeteoClient:
    """
    Wrapper para la API de Open-Meteo para obtener datos meteorológicos actuales.
    """
    
    def __init__(self, cache_expire: int = 3600, retries: int = 5, backoff_factor: float = 0.2):
        # Configurar el cliente con caché y reintentos automáticos
        cache_session = requests_cache.CachedSession('.cache', expire_after=cache_expire)
        retry_session = retry(cache_session, retries=retries, backoff_factor=backoff_factor)
        self.client = openmeteo_requests.Client(session=retry_session)
        self.url = "https://api.open-meteo.com/v1/forecast"

    def get_current_weather(self, latitude: float, longitude: float) -> dict:
        """
        Obtiene las condiciones meteorológicas actuales para unas coordenadas dadas.
        
        Args:
            latitude (float): Latitud de la ubicación.
            longitude (float): Longitud de la ubicación.
            
        Returns:
            dict: Diccionario con la información meteorológica actual solicitada.
        """
        params = {
            "latitude": latitude,
            "longitude": longitude,
            "current": [
                "temperature_2m", 
                "precipitation", 
                "is_day", 
                "weather_code", 
                "relative_humidity_2m"
            ],
        }
        
        # Realizar la petición a la API
        responses = self.client.weather_api(self.url, params=params)
        response = responses[0]
        current = response.Current()
        
        # Extraer los datos asegurando el mismo orden que en la petición
        return {
            "metadata": {
                "latitude": response.Latitude(),
                "longitude": response.Longitude(),
                "elevation": response.Elevation(),
                "utc_offset_seconds": response.UtcOffsetSeconds(),
                "time": current.Time()
            },
            "current": {
                "temperature_2m": current.Variables(0).Value(),
                "precipitation": current.Variables(1).Value(),
                "is_day": current.Variables(2).Value(),
                "weather_code": current.Variables(3).Value(),
                "relative_humidity_2m": current.Variables(4).Value()
            }
        }

# Ejemplo de uso:
if __name__ == "__main__":
    # Instanciar el wrapper (la conexión se configura una sola vez)
    clima_api = OpenMeteoClient()
    
    # Coordenadas de ejemplo (Berlín)
    lat = 28.0836
    lon = -80.6081
    
    # Obtener los datos
    datos = clima_api.get_current_weather(latitude=lat, longitude=lon)
    
    print(json.dumps(datos, indent=2))  # Imprimir la respuesta completa en formato JSON
    
    # Imprimir los resultados
    print(f"Coordenadas: {datos['metadata']['latitude']}°N {datos['metadata']['longitude']}°E")
    print(f"Temperatura: {datos['current']['temperature_2m']:.2f}°C")
    print(f"Humedad relativa: {datos['current']['relative_humidity_2m']:.2f}%")
    print(f"Precipitación: {datos['current']['precipitation']:.2f} mm")
    print(f"Es de día: {'Sí' if datos['current']['is_day'] else 'No'}")
    print(f"Código del clima: {datos['current']['weather_code']}")