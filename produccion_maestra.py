import os, json, qrcode

URL_BASE = "https://rmt1965-cloud.github.io/Cantabrana/"

def sincronizar_sistema():
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
        inventario[clave] = [f for f in os.listdir(ruta) if f.lower().endswith(('.mp3', '.jpg', '.png', '.jpeg'))]

    with open('galeria.json', 'w', encoding='utf-8') as f:
        json.dump(inventario, f, indent=4, ensure_ascii=False)
    
    qr_dir = "QRS_IMPRIMIR"
    if not os.path.exists(qr_dir): os.makedirs(qr_dir)
    for jota in inventario["jotas"]:
        qrcode.make(f"{URL_BASE}?jota={jota}").save(os.path.join(qr_dir, f"QR_{jota.replace('.mp3', '')}.png"))
    print("💎 SISTEMA SINCRONIZADO: 8K Ready.")

if __name__ == "__main__":
    sincronizar_sistema()