# Harvester Bot 🚌📊

Harvester Bot es un script automatizado en Python diseñado para monitorear, recolectar y almacenar métricas integrales de transporte público. Específicamente, el bot realiza el seguimiento de la **Ruta 1 de la MBTA (Harvard Square - Nubian Station)** en ambos sentidos (ida y vuelta).

El sistema consolida datos de posicionamiento de vehículos, condiciones climáticas, estado del tráfico y métricas espaciales (mediante procesamiento de trazas GTFS), almacenando toda esta información en una base de datos PostgreSQL (Supabase) para su posterior uso en modelos de Machine Learning.

## 🏗️ Arquitectura y APIs Integradas

El sistema se alimenta de múltiples fuentes de datos orquestadas de manera eficiente:

1.  **MBTA API (v3):** Rastreo en tiempo real de la flota de colectivos (https://www.mbta.com/).
2.  **Datos GTFS:** Procesamiento espacial estático para determinar el progreso del vehículo en su recorrido (traza) y calcular desvíos.
3.  **TomTom Traffic API:** Medición de velocidades, congestión y tiempos de viaje en el punto exacto del vehículo (https://docs.tomtom.com/).
4.  **OpenMeteo API:** Condiciones climáticas exactas basadas en coordenadas geográficas.
5.  **TimeAPI:** Determinación de metadatos temporales locales del vehículo.
6.  **Supabase (PostgreSQL):** Almacenamiento final de las métricas recolectadas.

## 📋 Requisitos Previos (GTFS)

Antes de ejecutar el bot, es **obligatorio** descargar los datos estáticos GTFS de la MBTA, ya que el sistema necesita procesar las formas geográficas (`shapes`) y rutas (`routes`).

1. Descarga el archivo `GTFS.zip` desde el portal para desarrolladores de la MBTA: https://www.mbta.com/developers/gtfs
2. Crea una carpeta llamada `GTFS/` en la raíz del proyecto.
3. Coloca el archivo descargado dentro, de modo que la ruta sea `GTFS/GTFS.zip`. *(Este path se puede modificar mediante los parámetros de ejecución).*

## 🚀 Instalación

Se recomienda utilizar un entorno virtual (venv) para aislar las dependencias del proyecto.

```bash
# 1. Clonar el repositorio y entrar al directorio
# git clone <tu-repo> && cd <tu-directorio>

# 2. Crear el entorno virtual
python3 -m venv .venv

# 3. Activar el entorno virtual
# En Linux/macOS:
source .venv/bin/activate
# En Windows:
.venv\Scripts\activate

# 4. Instalar las dependencias
pip install -r requirements.txt
```

## ⚙️ Configuración (Variables de Entorno)

El sistema requiere de credenciales y tokens para funcionar. Crea un archivo `.env` en la raíz del proyecto (puedes basarte en un `.env.example`) con la siguiente estructura:

```env
# Token de MBTA gestionado en https://api-v3.mbta.com/portal
MBTA_TOKEN=tu_token_mbta_aqui

# API KEY de TOMTOM para trafico gestionado en https://docs.tomtom.com/
TOMTOM_API_KEY=tu_api_key_tomtom_aqui

# Credenciales de base de datos (Supabase)
DB_URL=tu_database_url_aqui
DB_PASSWORD=tu_password_aqui
```

## ▶️ Ejecución y Parámetros

El bot se ejecuta mediante la línea de comandos y permite personalizar su comportamiento a través de diversos argumentos (usando `argparse`):

```bash
python main.py [opciones]
```

### Opciones disponibles:

*   `--gtfs`: Ruta al archivo GTFS.zip (por defecto: `GTFS/GTFS.zip`).
*   `--frecuencia`: Frecuencia del ciclo de consulta general en segundos (por defecto: `30`).
*   `--umbral-desvio`: Umbral de distancia fuera de traza en metros. Si el vehículo supera esta distancia, se considera desviado (por defecto: `70.0`).
*   `--umbral-trafico-km`: Distancia mínima que debe recorrer el vehículo en km para volver a consumir la API de tráfico (por defecto: `1.0`).
*   `--log-level`: Nivel de detalle de la consola. Opciones: `DEBUG`, `INFO`, `WARNING`, `ERROR` (por defecto: `INFO`).

**Ejemplo de ejecución personalizada:**
```bash
python main.py --frecuencia 15 --umbral-desvio 100 --umbral-trafico-km 0.5 --log-level DEBUG
```

## 🧠 Optimización de API de Tráfico (TomTom) y Detección de Desvíos

Para ser eficientes y **no agotar los tokens/cuota de la API de TomTom**, el bot implementa un sistema de caché en memoria de la última instantánea de cada vehículo (`bus_id`). 

**Lógica de optimización:**
1. Solo se realiza una nueva petición a TomTom si la unidad se movió una distancia superior al `--umbral-trafico-km` (ej. 1 km) desde su última medición de tráfico.
2. **Bandera `es_desvio`:** El procesador GTFS proyecta el punto GPS real del colectivo sobre la ruta ideal. Si la distancia ortogonal entre ambos supera el `--umbral-desvio` (ej. 70 metros), la bandera binaria `es_desvio` se activa ($1$). 
3. **Bloqueo condicional:** Si la bandera `es_desvio` está activada (el bus no está en su ruta habitual), **no se consulta la API de TomTom**, ahorrando tokens, ya que la velocidad de tráfico en un punto fuera de la traza principal aporta ruido al dataset de la ruta.

## 📊 Tabla de Métricas (Base de Datos)

El bot unifica los datos de todas las fuentes y los inserta en la base de datos con la siguiente estructura:


| Origen / API | Campo | Tipo de Dato PostgreSQL | Modificadores / Detalle | Descripción / Uso en Machine Learning |
| --- | --- | --- | --- | --- |
| **API Vehículo** | `bearing` | `SMALLINT` | `CHECK (bearing >= 0 AND bearing <= 360)` | Dirección del movimiento en grados ($0^\circ-360^\circ$). Útil para patrones de trayectoria. |
|  | `current_status` | `VARCHAR(25)` | `NULLABLE` | Estado actual (`IN_TRANSIT_TO`, `IN_INCOMING_AT`, etc.). Feature categórica. |
|  | `direction_id` | `SMALLINT` | `CHECK (direction_id IN (0, 1))` | Sentido del recorrido ($0$ o $1$). Categoría clave de la ruta. |
|  | `occupancy_status` | `VARCHAR(30)` | `NULLABLE` | Ocupación del vehículo (`MANY_SEATS_AVAILABLE`, etc.). Target o feature categórica. |
|  | `latitude` | `DECIMAL(10,8)` | `NOT NULL` | Latitud exacta. Crucial para cálculos espaciales y clustering. |
|  | `longitude` | `DECIMAL(11,8)` | `NOT NULL` | Longitud exacta. Crucial para cálculos espaciales y clustering. |
|  | `bus_id` | `VARCHAR(20)` | `NOT NULL` | Identificador único de la unidad/colectivo (ej. `1918`). Útil para análisis por unidad. |
|  | `route_id` | `VARCHAR(20)` | `NOT NULL` | Identificador de la ruta principal (ej. `1`). Feature categórica fundamental. |
|  | `route_pattern_id` | `VARCHAR(30)` | `NULLABLE` | Variante del patrón de ruta (ej. `1-_-0`). Diferencia ramales o sub-recorridos. |
|  | `shape_id` | `VARCHAR(30)` | `NULLABLE` | Identificador del trazado geométrico exacto de la ruta (ej. `010154`). |
|  | `agency_id` | `VARCHAR(20)` | `NOT NULL` | Empresa u operador del servicio (ej. `1` o `3`). Feature categórica operativa. |
|  | `line_id` | `VARCHAR(30)` | `NULLABLE` | Línea comercial o corredor (ej. `line-Red`). Agrupador categórico. |
| **Traza (Procesado)** | `distancia_en_traza` | `REAL` | `NOT NULL` | Progreso en kilómetros dentro del recorrido planeado (precisión simple `FLOAT4`). |
|  | `distancia_fuera_traza` | `REAL` | `NOT NULL` | Distancia métrica de desvío espacial respecto a la ruta oficial. |
|  | `es_desvio` | `BOOLEAN` | `NOT NULL DEFAULT FALSE` | Flag binario (`TRUE`/`FALSE`). Se activa si supera el umbral tolerado. |
| **API Tiempo Local** | `date_time` | `TIMESTAMPTZ` | `NOT NULL` | Timestamp completo con offset/zona horaria (`TIMESTAMP WITH TIME ZONE`). |
|  | `timezone` | `VARCHAR(50)` | `NOT NULL` | Identificador de zona horaria (ej. `America/New_York`). |
| **API Clima** | `temperature_2m` | `REAL` | `NULLABLE` | Temperatura ambiental a 2 metros (°C). Influye en velocidad y demoras. |
|  | `precipitation` | `REAL` | `NULLABLE` | Precipitación acumulada (mm). Feature para condiciones adversas. |
|  | `relative_humidity_2m` | `REAL` | `NULLABLE` | Humedad relativa (%). Indicador de niebla o visibilidad reducida. |
|  | `weather_code` | `SMALLINT` | `NULLABLE` | Código WMO de estado del tiempo (lluvia, nieve, despejado). Feature categórica. |
|  | `is_day` | `BOOLEAN` | `NULLABLE` | Indicador binario día/noche (`TRUE`/`FALSE`). Afecta visibilidad y flujo. |
| **API TomTom** | `currentSpeed` | `REAL` | `NULLABLE` | Velocidad real actual detectada en el tramo (km/h). |
|  | `freeFlowSpeed` | `REAL` | `NULLABLE` | Velocidad teórica ideal en flujo libre del tramo (km/h). |
|  | `currentTravelTime` | `INTEGER` | `NULLABLE` | Tiempo actual de viaje en el tramo (segundos). |
|  | `freeFlowTravelTime` | `INTEGER` | `NULLABLE` | Tiempo de viaje ideal sin tráfico en el tramo (segundos). |
|  | `confidence` | `REAL` | `NULLABLE` | Confianza/precisión de la medición ($0.0$ a $1.0$). |
|  | `roadClosure` | `BOOLEAN` | `NOT NULL DEFAULT FALSE` | Cierre o corte total de la vía (`TRUE`/`FALSE`). |

---
