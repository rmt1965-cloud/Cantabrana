import os
import json

def generar_galeria():
    # Determinamos la ruta donde está este script
    base_path = os.path.dirname(os.path.abspath(__file__))
    
    # Definimos exactamente dónde están tus archivos de arte y sonido
    rutas_activos = {
        "musica_ambiente": os.path.join(base_path, "ambiente"),
        "pergaminos": os.path.join(base_path, "assets", "pergaminos"),
        "sellos": os.path.join(base_path, "assets", "sellos")
    }

    # Diccionario donde guardaremos la lista de archivos
    inventario = {
        "musica_ambiente": [],
        "pergaminos": [],
        "sellos": []
    }

    print("🔍 Escaneando activos para Cantabrana...")

    for clave, path in rutas_activos.items():
        if os.path.exists(path):
            # Obtenemos la ruta relativa para que funcione en la web de GitHub
            rel_path = os.path.relpath(path, base_path).replace(os.sep, "/")
            
            # Listamos archivos válidos (imágenes y audio)
            archivos = [
                f"{rel_path}/{f}" for f in os.listdir(path) 
                if f.lower().endswith(('.jpg', '.jpeg', '.png', '.webp', '.mp3'))
            ]
            inventario[clave] = archivos
            print(f"   ✅ {clave.capitalize()}: {len(archivos)} archivos encontrados.")
        else:
            print(f"   ⚠️ Advertencia: No se encontró la carpeta {path}")

    # Escribimos el archivo galeria.json
    try:
        ruta_json = os.path.join(base_path, "galeria.json")
        with open(ruta_json, 'w', encoding='utf-8') as f:
            json.dump(inventario, f, indent=2, ensure_ascii=False)
        print(f"\n✨ ¡ÉXITO! 'galeria.json' ha sido actualizado correctamente.")
        print("🚀 Recuerda subir este archivo a GitHub para activar los cambios en la web.")
    except Exception as e:
        print(f"\n❌ Error al guardar el archivo: {e}")

if __name__ == '__main__':
    generar_galeria()