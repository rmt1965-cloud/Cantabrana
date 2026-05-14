import os
import qrcode
import urllib.parse
import shutil
import json

def main():
    base_path = os.path.dirname(os.path.abspath(__file__))
    jotas_path = os.path.join(base_path, "jotas")
    output_path = os.path.join(base_path, "QRS_IMPRIMIR")
    
    # Rutas actualizadas: Se eliminó 'texturas' y se añadieron 'pergaminos' y 'sellos'
    rutas_activos = {
        "musica_ambiente": os.path.join(base_path, "ambiente"),
        "pergaminos": os.path.join(base_path, "assets", "pergaminos"),
        "sellos": os.path.join(base_path, "assets", "sellos")
    }

    if os.path.exists(output_path): 
        shutil.rmtree(output_path)
    os.makedirs(output_path)

    print("🧠 Sincronizando pergaminos, sellos y ambiente...")
    inventario = {k: [] for k in rutas_activos.keys()}
    
    for clave, path in rutas_activos.items():
        if os.path.exists(path):
            rel_path = os.path.relpath(path, base_path).replace(os.sep, "/")
            inventario[clave] = [
                f"{rel_path}/{f}" for f in os.listdir(path) 
                if f.lower().endswith(('.jpg', '.jpeg', '.png', '.webp', '.mp3'))
            ]
    
    # Genera el galeria.json que la web necesita
    with open(os.path.join(base_path, "galeria.json"), 'w', encoding='utf-8') as f:
        json.dump(inventario, f, indent=2, ensure_ascii=False)

    archivos_jotas = [f for f in os.listdir(jotas_path) if f.lower().endswith('.mp3')]
    for archivo in archivos_jotas:
        url_qr = f"https://rmt1965-cloud.github.io/Cantabrana/index.html?jota={urllib.parse.quote(archivo)}"
        qr = qrcode.QRCode(version=1, box_size=15, border=2)
        qr.add_data(url_qr)
        qr.make(fit=True)
        img_qr = qr.make_image(fill_color="black", back_color="white")
        nombre_qr = archivo.lower().replace('.mp3', '_qr.png')
        img_qr.save(os.path.join(output_path, nombre_qr))
        print(f"   ✅ QR generado: {nombre_qr}")

    print("\n✨ PROCESO COMPLETADO. Sube 'galeria.json' y las carpetas de assets a GitHub.")

if __name__ == '__main__':
    main()