import os
import qrcode
import urllib.parse
from PIL import Image

def main():
    # Rutas locales
    base_path = os.path.dirname(os.path.abspath(__file__))
    jotas_path = os.path.join(base_path, "jotas")
    output_path = os.path.join(base_path, "QRS_IMPRIMIR")
    plantilla_path = os.path.join(base_path, "plantilla.png")

    if not os.path.exists(output_path): os.makedirs(output_path)

    # DATOS EXACTOS DE TU GITHUB
    usuario = "rmt1965-cloud"
    repo = "Cantabrana"

    # 1. Leer archivos
    archivos = [f for f in os.listdir(jotas_path) if f.lower().endswith('.mp3')]
    
    for archivo in archivos:
        # 2. LIMPIEZA DE URL (Esto evita el 404)
        # GitHub es muy sensible a espacios y tildes. quote() los traduce a formato web.
        nombre_url = urllib.parse.quote(archivo)
        url_final = f"https://raw.githubusercontent.com/{usuario}/{repo}/main/jotas/{nombre_url}"
        
        # 3. Generar QR
        qr = qrcode.QRCode(version=1, box_size=10, border=4)
        qr.add_data(url_final)
        qr.make(fit=True)
        img_qr = qr.make_image(fill_color="black", back_color="white").convert('RGB')
        
        try:
            # 4. Pegar en plantilla
            fondo = Image.open(plantilla_path).convert('RGB')
            # Lo hacemos pequeño (250px) y lo subimos arriba (y=50) para no tapar NADA
            img_qr = img_qr.resize((250, 250), Image.Resampling.LANCZOS)
            
            pos_x = (fondo.width - 250) // 2
            pos_y = 50 
            
            fondo.paste(img_qr, (pos_x, pos_y))
            
            nombre_final = f"QR_{archivo.replace('.mp3', '')}.png"
            fondo.save(os.path.join(output_path, nombre_final))
            print(f"✅ Creado: {nombre_final}")
        except:
            # Si falla la plantilla, guarda el QR solo para no perder el trabajo
            img_qr.save(os.path.join(output_path, f"SOLO_QR_{archivo}.png"))
            print(f"⚠️ Guardado solo QR para: {archivo} (no encontré plantilla.png)")

if __name__ == "__main__":
    main()