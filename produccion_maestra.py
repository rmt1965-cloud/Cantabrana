import os
import json
import qrcode

# === CONFIGURACIÓN ===
# IMPORTANTE: Cambia esta URL por la dirección real de tu GitHub Pages
URL_BASE = "https://rmt1965-cloud.github.io/Cantabrana/"

def auditar_y_sincronizar():
    # Estructura de carpetas requerida según el guion
    directorios = {
        "jotas": "jotas",
        "fotos_arcos": "fotos/arcos_puertas",
        "fotos_detalles": "fotos/detalles",
        "pergaminos": "assets/pergaminos",
        "ambientes": "ambiente",
        "sellos": "assets/sellos"
    }

    # Comprobación y creación de carpetas
    print("--- Auditando Carpetas ---")
    inventario = {}
    for clave, ruta in directorios.items():
        if not os.path.exists(ruta):
            os.makedirs(ruta)
            print(f"Carpeta creada: {ruta}")
        
        # Filtramos archivos válidos (audio e imagen)
        archivos = [f for f in os.listdir(ruta) if f.lower().endswith(('.mp3', '.jpg', '.jpeg', '.png'))]
        inventario[clave] = archivos
        print(f"[{clave.upper()}]: {len(archivos)} archivos encontrados.")

    # 1. Generar galeria.json (El cerebro del sistema)
    with open('galeria.json', 'w', encoding='utf-8') as f:
        json.dump(inventario, f, indent=4, ensure_ascii=False)
    print("\n✅ galeria.json generado correctamente.")

    # 2. Generar Códigos QR en la carpeta QRS_IMPRIMIR
    qr_dir = "QRS_IMPRIMIR"
    if not os.path.exists(qr_dir):
        os.makedirs(qr_dir)

    print("\n--- Generando Códigos QR ---")
    for jota in inventario["jotas"]:
        nombre_qr = f"QR_{jota.replace('.mp3', '')}.png"
        ruta_qr = os.path.join(qr_dir, nombre_qr)
        
        # Creamos la URL que incluye el nombre exacto del archivo jota
        url_final = f"{URL_BASE}?jota={jota}"
        
        qr = qrcode.QRCode(version=1, box_size=10, border=5)
        qr.add_data(url_final)
        qr.make(fit=True)
        img = qr.make_image(fill_color="black", back_color="white")
        img.save(ruta_qr)
        print(f"📡 QR generado: {nombre_qr} -> {url_final}")

    print("\n🚀 PROCESO FINALIZADO.")
    print("RECUERDA: Sube 'galeria.json' y todas tus carpetas a GitHub.")

if __name__ == '__main__':
    try:
        import qrcode
    except ImportError:
        print("❌ ERROR: La librería 'qrcode' no está instalada.")
        print("Ejecuta: pip install qrcode pillow")
    else:
        auditar_y_sincronizar()