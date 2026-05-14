import os, json, qrcode

# REVISA ESTA URL: Debe ser la de tu proyecto en GitHub Pages
URL_BASE = "https://rmt1965-cloud.github.io/Cantabrana/"

def auditar():
    directorios = {
        "jotas": "jotas",
        "fotos_arcos": "fotos/arcos_puertas",
        "fotos_detalles": "fotos/detalles",
        "pergaminos": "assets/pergaminos",
        "ambientes": "ambiente",
        "sellos": "assets/sellos"
    }
    
    inventario = {}
    for clave, ruta in directorios.items():
        if not os.path.exists(ruta): os.makedirs(ruta)
        # Filtramos archivos para evitar errores con carpetas vacías
        inventario[clave] = [f for f in os.listdir(ruta) if f.lower().endswith(('.mp3', '.jpg', '.png', '.jpeg'))]

    # Guardamos el JSON con codificación UTF-8 para evitar errores con tildes
    with open('galeria.json', 'w', encoding='utf-8') as f:
        json.dump(inventario, f, indent=4, ensure_ascii=False)
    
    qr_dir = "QRS_IMPRIMIR"
    if not os.path.exists(qr_dir): os.makedirs(qr_dir)
    
    for jota in inventario["jotas"]:
        # Generamos el QR con la ruta completa
        url_final = f"{URL_BASE}?jota={jota}"
        qr = qrcode.make(url_final)
        qr.save(os.path.join(qr_dir, f"QR_{jota.replace('.mp3', '')}.png"))
    
    print("✅ galeria.json creado. QRs generados en QRS_IMPRIMIR.")

if __name__ == "__main__":
    auditar()