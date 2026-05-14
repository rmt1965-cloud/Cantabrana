import os
import json
import qrcode

# === CONFIGURACIÓN ===
# IMPORTANTE: Cambia esta URL por la dirección real de tu GitHub Pages
URL_BASE = "https://rmt1965-cloud.github.io/Cantabrana/"

def auditar_y_generar():
    # Estructura de carpetas requerida
    directorios = {
        "jotas": "jotas",
        "fotos_arcos": "fotos/arcos_puertas",
        "fotos_detalles": "fotos/detalles",
        "pergaminos": "assets/pergaminos",
        "ambientes": "ambiente",
        "sellos": "assets/sellos"
    }

    # Crear carpetas si no existen
    for ruta in directorios.values():
        if not os.path.exists(ruta):
            os.makedirs(ruta)
            print(f"Creada carpeta: {ruta}")

    # Escanear archivos
    inventario = {}
    for clave, ruta in directorios.items():
        # Filtramos solo archivos de audio e imagen
        archivos = [f for f in os.listdir(ruta) if f.lower().endswith(('.mp3', '.jpg', '.jpeg', '.png'))]
        inventario[clave] = archivos

    # 1. Generar galeria.json
    with open('galeria.json', 'w', encoding='utf-8') as f:
        json.dump(inventario, f, indent=4, ensure_ascii=False)
    print("✅ galeria.json actualizado con éxito.")

    # 2. Generar Códigos QR
    qr_dir = "QRS_IMPRIMIR"
    if not os.path.exists(qr_dir):
        os.makedirs(qr_dir)

    for jota in inventario["jotas"]:
        nombre_qr = f"QR_{jota.replace('.mp3', '')}.png"
        ruta_qr = os.path.join(qr_dir, nombre_qr)
        
        # Si el QR no existe, lo creamos
        if not os.path.exists(ruta_qr):
            # La URL lleva el parámetro ?jota=nombre_del_archivo.mp3
            url_final = f"{URL_BASE}?jota={jota}"
            qr = qrcode.QRCode(version=1, box_size=10, border=5)
            qr.add_data(url_final)
            qr.make(fit=True)
            img = qr.make_image(fill_color="black", back_color="white")
            img.save(ruta_qr)
            print(f"📡 QR generado: {nombre_qr}")

    print(f"\n🚀 Proceso terminado. Sube todo a GitHub excepto la carpeta {qr_dir} (esa es para imprimir).")

if __name__ == "__main__":
    auditar_y_generar()