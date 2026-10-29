#!/usr/bin/env python3
"""
TrazaProcessor (MapMatcher & Motor de Métricas Espacio-Temporales)
================================================================================
- Autor:          Benjamin Vargas
- Fecha:          2026-10-29
- Versión:        1.0.2


DESCRIPCIÓN GENERAL
-------------------
    Módulo encargado de realizar el Map Matching (proyección ortogonal de coordenadas GPS
    sobre la traza vectorial) y el cálculo de variables cinemáticas (velocidad instantánea,
    dwell time, avance acumulado y detección geométrica de desvíos).

ESTRUCTURA Y DEPENDENCIAS
-------------------------
    Requiere 'shapely', 'pyproj' y 'numpy' para el cálculo preciso de proyecciones planas (UTM/Metros).
================================================================================
"""
import logging
from datetime import datetime
from typing import List, Tuple, Dict, Optional, Any
import numpy as np
from shapely.geometry import LineString, Point
from shapely import transform
import pyproj

logger = logging.getLogger(__name__)


class TrazaProcessor:
    """Motor geométrico y cinemático para la proyección de telemetría sobre trazas vectoriales."""

    def __init__(self, umbral_desvio_m: float = 50.0):
        """Inicializa el procesador de traza.

        Args:
            umbral_desvio_m (float): Distancia ortogonal límite en metros para considerar desvío.
        """
        self.umbral_desvio_m: float = umbral_desvio_m

        # Estados del procesador
        self.traza_linestring: Optional[LineString] = None
        self.traza_utm: Optional[LineString] = None
        self.longitud_total_traza_m: float = 0.0

        # Proyecciones WGS84 (GPS) <-> UTM Zone 20S (Córdoba, Argentina)
        self.wgs84 = pyproj.CRS("EPSG:4326")
        self.utm_20s = pyproj.CRS("EPSG:32720")
        
        transformer = pyproj.Transformer.from_crs(self.wgs84, self.utm_20s, always_xy=True)
        # Adaptador NumPy para mantener la forma (N, 2) requerida por Shapely 2.0+
        self.proj_to_utm = lambda coords: np.column_stack(
            transformer.transform(coords[:, 0], coords[:, 1])
        )

        # Snapshot actual
        self.lat_actual: Optional[float] = None
        self.lon_actual: Optional[float] = None
        self.timestamp_actual: Optional[datetime] = None

        # Snapshot anterior
        self.snap_previo: Optional[Dict[str, Any]] = None

    # ==========================================================================
    # SETTERS DE ESTADO
    # ==========================================================================

    def set_traza(self, traza_coordenadas: List[Tuple[float, float]]) -> None:
        """Establece la traza vectorial oficial del recorrido."""
        if not traza_coordenadas or len(traza_coordenadas) < 2:
            raise ValueError("La traza debe contener al menos 2 puntos para formar una geometría válida.")

        puntos_clean = [(pt[0], pt[1]) for pt in traza_coordenadas]
        
        self.traza_linestring = LineString(puntos_clean)
        # Transformación en Shapely 2.0+
        self.traza_utm = transform(self.traza_linestring, self.proj_to_utm)
        self.longitud_total_traza_m = self.traza_utm.length
        logger.debug(f"Traza cargada exitosamente. Longitud total: {self.longitud_total_traza_m:.2f} m")

    def set_ubicacion_actual(self, lat: float, lon: float, timestamp: datetime) -> None:
        """Establece la ubicación y estampa de tiempo instantánea del colectivo."""
        self.lat_actual = lat
        self.lon_actual = lon
        self.timestamp_actual = timestamp

    def set_snapshot_previa(self, snap_previo: Dict[str, Any]) -> None:
        """Establece la información del snapshot previo del mismo colectivo."""
        self.snap_previo = snap_previo

    # ==========================================================================
    # MÉTODOS DE CÁLCULO
    # ==========================================================================

    def calcular_avance_m(self) -> Tuple[float, float]:
        """Proyecta la ubicación GPS actual sobre la traza (Map Matching)."""
        if self.traza_utm is None:
            raise RuntimeError("Debe establecer primero la traza llamando a set_traza().")
        if self.lat_actual is None or self.lon_actual is None:
            raise RuntimeError("Debe establecer la ubicación actual llamando a set_ubicacion_actual().")

        punto_wgs84 = Point(self.lon_actual, self.lat_actual)
        punto_utm = transform(punto_wgs84, self.proj_to_utm)

        distancia_traza_m = float(self.traza_utm.project(punto_utm))
        distancia_offset_m = float(self.traza_utm.distance(punto_utm))

        return distancia_traza_m, distancia_offset_m

    def es_desvio(self, umbral_m: Optional[float] = None) -> bool:
        """Determina si la unidad está desviada de la traza oficial."""
        umbral_limite = umbral_m if umbral_m is not None else self.umbral_desvio_m
        _, offset_m = self.calcular_avance_m()
        return offset_m > umbral_limite

    def calcular_velocidad_instantanea(self) -> float:
        """Calcula la velocidad instantánea (km/h) derivada por delta."""
        if not self.snap_previo or self.timestamp_actual is None:
            return 0.0

        dist_actual_m, _ = self.calcular_avance_m()
        dist_previa_m = float(self.snap_previo.get("distancia_traza_m", 0.0))
        time_previo = self.snap_previo.get("timestamp")

        if not time_previo:
            return 0.0

        delta_t_seg = (self.timestamp_actual - time_previo).total_seconds()
        delta_d_m = dist_actual_m - dist_previa_m

        if delta_t_seg > 0 and delta_d_m >= 0:
            vel_derived = (delta_d_m / delta_t_seg) * 3.6  # m/s -> km/h
            return round(vel_derived, 2)

        return 0.0

    def calcular_dwell_time(self, vel_kmh: float) -> float:
        """Calcula el Dwell Time acumulado y lo reinicia al reanudar la marcha."""
        if not self.snap_previo or self.timestamp_actual is None:
            return 0.0

        dist_actual_m, _ = self.calcular_avance_m()
        dist_previa_m = float(self.snap_previo.get("distancia_traza_m", 0.0))
        time_previo = self.snap_previo.get("timestamp")

        if not time_previo:
            return 0.0

        delta_t_seg = (self.timestamp_actual - time_previo).total_seconds()
        delta_d_m = abs(dist_actual_m - dist_previa_m)
        dwell_anterior = float(self.snap_previo.get("dwell_time_previo_seg", 0.0))

        if vel_kmh < 2.0 and delta_d_m < 5.0:
            return round(dwell_anterior + delta_t_seg, 2)
        else:
            return 0.0


# ==============================================================================
# MAIN DE PRUEBAS / DEMOSTRACIÓN DEL MÓDULO
# ==============================================================================
if __name__ == "__main__":
    import logging
    from datetime import datetime

    logging.basicConfig(level=logging.INFO, format="%(asctime)s [%(levelname)s]: %(message)s")

    print("\n==================================================")
    print("   INICIANDO PRUEBAS DEL MÓDULO MapMatcher")
    print("==================================================\n")

    traza_ejemplo = [
        (-64.1950, -31.4110),
        (-64.1980, -31.4115),
        (-64.2010, -31.4120),
        (-64.2050, -31.4125)
    ]

    matcher = TrazaProcessor(umbral_desvio_m=50.0)
    matcher.set_traza(traza_ejemplo)

    # SNAPSHOT 1
    t0 = datetime(2026, 10, 29, 18, 0, 0)
    matcher.set_ubicacion_actual(lat=-31.4110, lon=-64.1950, timestamp=t0)
    
    dist_m1, offset_m1 = matcher.calcular_avance_m()
    desviado_1 = matcher.es_desvio()
    vel_1 = matcher.calcular_velocidad_instantanea()
    dwell_1 = matcher.calcular_dwell_time(vel_kmh=vel_1)

    print("--- SNAPSHOT 1 (Primera Detección) ---")
    print(f"Distancia en Traza : {dist_m1:.2f} m")
    print(f"Velocidad          : {vel_1} km/h")
    print(f"Dwell Time         : {dwell_1} s")

    snap_1_dict = {"timestamp": t0, "distancia_traza_m": dist_m1, "dwell_time_previo_seg": dwell_1}

    # SNAPSHOT 2
    t1 = datetime(2026, 10, 29, 18, 0, 30)
    matcher.set_snapshot_previa(snap_1_dict)
    matcher.set_ubicacion_actual(lat=-31.4115, lon=-64.1980, timestamp=t1)

    dist_m2, offset_m2 = matcher.calcular_avance_m()
    vel_2 = matcher.calcular_velocidad_instantanea()
    dwell_2 = matcher.calcular_dwell_time(vel_kmh=vel_2)

    print("\n--- SNAPSHOT 2 (Llega a Parada y Frena - 30s después) ---")
    print(f"Distancia en Traza : {dist_m2:.2f} m")
    print(f"Velocidad          : {vel_2} km/h (Vino circulando)")
    print(f"Dwell Time         : {dwell_2} s (Comienza la parada)")

    snap_2_dict = {"timestamp": t1, "distancia_traza_m": dist_m2, "dwell_time_previo_seg": dwell_2}

    # SNAPSHOT 3
    t2 = datetime(2026, 10, 29, 18, 1, 0)
    matcher.set_snapshot_previa(snap_2_dict)
    matcher.set_ubicacion_actual(lat=-31.4115, lon=-64.1980, timestamp=t2)

    dist_m3, _ = matcher.calcular_avance_m()
    vel_3 = matcher.calcular_velocidad_instantanea()
    dwell_3 = matcher.calcular_dwell_time(vel_kmh=vel_3)

    print("\n--- SNAPSHOT 3 (Sigue Detenido en Parada - 30s acumulados) ---")
    print(f"Velocidad          : {vel_3} km/h")
    print(f"Dwell Time         : {dwell_3} s  <-- (Acumuló 30 segundos parado)")

    snap_3_dict = {"timestamp": t2, "distancia_traza_m": dist_m3, "dwell_time_previo_seg": dwell_3}

    # SNAPSHOT 4
    t3 = datetime(2026, 10, 29, 18, 1, 30)
    matcher.set_snapshot_previa(snap_3_dict)
    matcher.set_ubicacion_actual(lat=-31.4115, lon=-64.1980, timestamp=t3)

    dist_m4, _ = matcher.calcular_avance_m()
    vel_4 = matcher.calcular_velocidad_instantanea()
    dwell_4 = matcher.calcular_dwell_time(vel_kmh=vel_4)

    print("\n--- SNAPSHOT 4 (Sigue Detenido - 60s acumulados) ---")
    print(f"Velocidad          : {vel_4} km/h")
    print(f"Dwell Time         : {dwell_4} s  <-- (Acumuló 60 segundos parado)")

    snap_4_dict = {"timestamp": t3, "distancia_traza_m": dist_m4, "dwell_time_previo_seg": dwell_4}

    # SNAPSHOT 5
    t4 = datetime(2026, 10, 29, 18, 2, 0)
    matcher.set_snapshot_previa(snap_4_dict)
    matcher.set_ubicacion_actual(lat=-31.4200, lon=-64.1980, timestamp=t4)

    dist_m5, offset_m5 = matcher.calcular_avance_m()
    desviado_5 = matcher.es_desvio(umbral_m=50.0)
    vel_5 = matcher.calcular_velocidad_instantanea()
    dwell_5 = matcher.calcular_dwell_time(vel_kmh=vel_5)

    print("\n--- SNAPSHOT 5 (Arrancó y se Desvió) ---")
    print(f"Offset Ortogonal   : {offset_m5:.2f} m")
    print(f"¿Está Desviado?    : {desviado_5}")
    print(f"Velocidad          : {vel_5} km/h")
    print(f"Dwell Time         : {dwell_5} s  <-- (Se reinició a 0 al moverse)")

    print("\n==================================================")
    print("   PRUEBAS FINALIZADAS CON ÉXITO")
    print("==================================================")