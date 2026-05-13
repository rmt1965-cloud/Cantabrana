import json
import os
from collections import Counter
from datetime import datetime

RUTA = 'analytics/eventos.json'
SALIDA = 'analytics/reportes/'

os.makedirs(SALIDA, exist_ok=True)

with open(RUTA, 'r', encoding='utf-8') as f:
    eventos = json.load(f)

inicios = [e for e in eventos if e['evento'] == 'inicio']
finales = [e for e in eventos if e['evento'] == 'finalizacion']

municipios = Counter([
    e.get('municipio', 'Desconocido')
    for e in inicios
])

escuchas_completas = len([
    e for e in finales
    if e.get('completado')
])

media_duracion = 0

if finales:
    media_duracion = sum([
        e.get('duracion', 0)
        for e in finales
    ]) / len(finales)

reduced_motion = len([
    e for e in inicios
    if e.get('reducedMotion')
])

screen_readers = len([
    e for e in inicios
    if e.get('screenReader')
])

fecha = datetime.now().strftime('%Y-%m-%d_%H-%M')

reporte = {
    'fecha': fecha,
    'escaneos_totales': len(inicios),
    'escuchas_completas': escuchas_completas,
    'duracion_media_segundos': round(media_duracion, 2),
    'municipios_mas_escuchados': municipios.most_common(10),
    'usuarios_reduce_motion': reduced_motion,
    'usuarios_lector_pantalla': screen_readers
}

with open(
    f'{SALIDA}/reporte_{fecha}.json',
    'w',
    encoding='utf-8'
) as f:

    json.dump(
        reporte,
        f,
        indent=4,
        ensure_ascii=False
    )

print('✅ Reporte generado.')
