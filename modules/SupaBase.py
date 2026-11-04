import psycopg2
from psycopg2 import sql

class Supabase:
    def __init__(self, db_url: str, password: str = None):
        """
        Instancia la conexión a Supabase.
        Puedes pasar la URL de conexión completa de PostgreSQL de Supabase, 
        o la URL y la contraseña por separado.
        Ejemplo de db_url: "postgresql://postgres.tu_proyecto:[PASSWORD]@aws-0-sa-east-1.pooler.supabase.com:6543/postgres"
        """
        if password and "[YOUR-PASSWORD]" in db_url:
            self.conn_string = db_url.replace("[YOUR-PASSWORD]", password)
        else:
            self.conn_string = db_url
            
        self._inicializar_tabla()

    def _obtener_conexion(self):
        return psycopg2.connect(self.conn_string)

    def _inicializar_tabla(self):
        """
        Crea la tabla 'metricas' basándose en la estructura requerida si aún no existe.
        """
        query_creacion = """
        CREATE TABLE IF NOT EXISTS metricas (
            id SERIAL PRIMARY KEY,
            bearing FLOAT,
            current_status VARCHAR,
            direction_id INT,
            occupancy_status VARCHAR,
            latitude DECIMAL(10,8),
            longitude DECIMAL(10,8),
            label VARCHAR,
            route_id VARCHAR,
            route_pattern_id VARCHAR,
            shape_id VARCHAR,
            agency_id VARCHAR,
            line_id VARCHAR,
            distancia_en_traza FLOAT,
            distancia_fuera_traza FLOAT,
            es_desvio BOOLEAN,
            date_time TIMESTAMP WITH TIME ZONE,
            timezone VARCHAR,
            temperature_2m FLOAT,
            precipitation FLOAT,
            relative_humidity_2m FLOAT,
            weather_code INT,
            is_day BOOLEAN,
            current_speed FLOAT,
            free_flow_speed FLOAT,
            current_travel_time INT,
            freeflow_travel_time INT,
            confidence FLOAT,
            road_closure BOOLEAN
        );
        """
        try:
            with self._obtener_conexion() as conn:
                with conn.cursor() as cur:
                    cur.execute(query_creacion)
                conn.commit()
        except Exception as e:
            print(f"Error al inicializar la tabla: {e}")

    def registrar_metrica(self, datos: dict):
        """
        Registra una nueva métrica. 
        Si el diccionario incluye valores `None`, se guardarán automáticamente como `NULL` en la base de datos.
        """
        if not datos:
            return
        
        columnas = datos.keys()
        # En Python, el valor None se traduce automáticamente a NULL en PostgreSQL vía psycopg2
        valores = tuple(datos.values())
        
        # Construcción segura de la query dinámica para evitar inyecciones SQL
        query_insercion = sql.SQL("INSERT INTO metricas ({}) VALUES ({})").format(
            sql.SQL(', ').join(map(sql.Identifier, columnas)),
            sql.SQL(', ').join(sql.Placeholder() * len(valores))
        )
        
        try:
            with self._obtener_conexion() as conn:
                with conn.cursor() as cur:
                    cur.execute(query_insercion, valores)
                conn.commit()
        except Exception as e:
            print(f"Error al registrar la métrica: {e}")
    
if __name__ == "__main__":
    # Reemplaza con la cadena de conexión real de la sección "Database" de tu panel de Supabase
    DB_URL = "urlbase_de_tu_base_de_datos"  # Reemplaza con tu URL real
    PASSWORD = "password_de_tu_base_de_datos"  # Reemplaza con tu contraseña real

    # Instancia (creará la tabla automáticamente si no existe)
    db = Supabase(db_url=DB_URL, password=PASSWORD)

    # Ejemplo de diccionario de entrada (con nulos incluidos)
    datos_entrada = {
        "bearing": 180.5,
        "current_status": "IN_TRANSIT_TO",
        "direction_id": 1,
        "occupancy_status": None, # Se registrará como NULL en Supabase para limpiarlo luego
        "latitude": -34.603722,
        "longitude": -58.381592,
        "label": "1918",
        "route_id": "1",
        "distancia_en_traza": 15.2,
        "es_desvio": False,
        "temperature_2m": 22.5,
        "precipitation": None, # Se registrará como NULL
        "current_speed": 45.0,
        "road_closure": False
    }

    # Registra la métrica
    db.registrar_metrica(datos_entrada)