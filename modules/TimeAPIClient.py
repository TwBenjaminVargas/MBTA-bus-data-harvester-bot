import requests
from typing import Dict, Any, Optional

class TimeAPIClient:
    """
    Un wrapper en Python para interactuar con Time API (timeapi.io).
    """
    
    BASE_URL = "https://timeapi.io"

    def __init__(self, timeout: int = 10):
        """
        Inicializa el cliente de la API.
        
        :param timeout: Tiempo máximo de espera para la respuesta en segundos.
        """
        self.timeout = timeout

    def get_time_by_coordinates(self, latitude: float, longitude: float) -> Optional[Dict[str, Any]]:
        """
        Obtiene la hora actual de una zona horaria utilizando coordenadas geográficas.

        :param latitude: Latitud en formato decimal (rango de -90 a 90).
        :param longitude: Longitud en formato decimal (rango de -180 a 180).
        :return: Un diccionario con los datos de tiempo o None si ocurre un error.
        """
        # Validación básica de coordenadas según la especificación de la API
        if not (-90 <= latitude <= 90):
            raise ValueError("La latitud debe estar entre -90 y 90.")
        if not (-180 <= longitude <= 180):
            raise ValueError("La longitud debe estar entre -180 y 180.")

        endpoint = f"{self.BASE_URL}/api/v1/time/current/coordinate"
        params = {
            "latitude": latitude,
            "longitude": longitude
        }

        try:
            response = requests.get(endpoint, params=params, timeout=self.timeout)
            
            # Lanzar una excepción si el código de estado HTTP indica un error (ej. 400 o 500)
            response.raise_for_status()
            
            # Retornar el esquema 'CurrentTime' parseado como diccionario de Python
            return response.json()
            
        except requests.exceptions.HTTPError as http_err:
            print(f"Error HTTP al consultar la API: {http_err} - Detalle: {response.text}")
        except requests.exceptions.RequestException as req_err:
            print(f"Error de conexión: {req_err}")
            
        return None

# --- Ejemplo de uso ---
if __name__ == "__main__":
    # Instanciamos el wrapper
    time_api = TimeAPIClient()

    # Coordenadas de ejemplo (Buenos Aires, Argentina)
    lat = -34.6037
    lng = -58.3816

    print(f"Consultando la hora para Lat: {lat}, Lng: {lng}...\n")
    
    datos_tiempo = time_api.get_time_by_coordinates(lat, lng)

    if datos_tiempo:
        print("¡Datos obtenidos con éxito!\n")
        print(f"Zona horaria: {datos_tiempo.get('timezone')}")
        print(f"Fecha y Hora: {datos_tiempo.get('date_time')}")
        print(f"Día de la semana: {datos_tiempo.get('day_of_week')}")
        print(f"Desfase UTC (segundos): {datos_tiempo.get('utc_offset_seconds')}")
        print(f"Horario de verano activo (DST): {datos_tiempo.get('dst_active')}")
    else:
        print("No se pudo obtener la información del tiempo.")