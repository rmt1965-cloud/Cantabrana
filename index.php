<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cantabrana: Omni-Kernel Ultimate v7.0</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;800&family=Playfair+Display:ital,wght=0,400;1,400;1,700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html { background: #000; overflow: hidden; font-family: 'Cinzel', serif; height: 100vh; width: 100vw; }

        /* LIENZO WEBGL SHADER */
        #gl-canvas { position: fixed; inset: 0; width: 100vw; height: 100vh; z-index: 1; opacity: 1; pointer-events: none; }

        /* ESCENOGRAFÍAS */
        .escena { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 1.5s ease-in-out; z-index: 2; background: transparent; }
        
        #capa-inicio { display: flex; opacity: 1; z-index: 9999; background: #000; }
        .btn-ritual { background: none; border: 2px solid #d4af37; color: #d4af37; padding: 30px 60px; font-size: 1.5rem; letter-spacing: 10px; cursor: pointer; text-transform: uppercase; transition: all 0.5s; }
        .btn-ritual:hover { background: #d4af37; color: #000; box-shadow: 0 0 30px rgba(212, 175, 55, 0.4); }

        .txt-fuego { text-align: center; font-size: clamp(2.2rem, 8vw, 4.5rem); font-weight: 800; color: #ffca28; text-shadow: 0 0 25px #ff4500, 0 0 50px #ff0000; transition: opacity 1s; opacity: 0; }

        #escena-escritorio { overflow: hidden; }
        .fondo-rustico { position: absolute; inset: 0; background-image: url('https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=1920'); background-size: cover; background-position: center; filter: brightness(0.22) contrast(1.1) sepia(0.2); z-index: -1; transition: transform 14s cubic-bezier(0.1, 0.6, 0.2, 1); }
        .vinyeta-luz { position: absolute; inset: 0; background: radial-gradient(circle at 45% 55%, rgba(255, 170, 70, 0.08) 0%, rgba(0, 0, 0, 0.7) 60%, rgba(0, 0, 0, 0.95) 100%); mix-blend-mode: multiply; }
        .parpadeo-vela { position: absolute; inset: 0; background: radial-gradient(circle at 45% 55%, rgba(255, 140, 40, 0.06) 0%, rgba(0,0,0,0) 55%); mix-blend-mode: screen; }
        .anim-zoom-out { transform: scale(1.15); }

        .lienzo-papel { position: absolute; width: 85%; text-align: center; }
        .texto-elegante { color: #ebdcb9; font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(1.8rem, 5vw, 2.8rem); opacity: 0; transition: opacity 1s ease-in-out; text-shadow: 0 4px 12px rgba(0,0,0,0.9); }

        /* EL PERGAMINO REDISEÑADO CON CONTENEDOR RELATIVO ABSOLUTO */
        #contenedor-pergamino { flex-direction: column; position: relative; }
        #pergamino-img { width: 92%; max-width: 550px; height: auto; max-height: 85vh; object-fit: contain; filter: drop-shadow(0 0 35px rgba(0,0,0,1)); }
        
        /* FUENTE AMIGABLE, GRANDE,CENTRADA Y CON AJUSTE TOTAL AL PERGAMINO */
        #advertencia { 
            position: absolute; 
            width: 75%; 
            max-width: 420px;
            color: #260f0a; 
            font-weight: bold; 
            font-family: 'Playfair Display', serif; 
            font-style: italic;
            font-size: clamp(1.4rem, 5.5vw, 2.3rem); 
            text-align: center; 
            line-height: 1.5; 
            opacity: 0; 
            transition: opacity 1.2s ease-in-out; 
            z-index: 26; 
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #visor { background: transparent; }
        .foto-film { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; opacity: 0; transition: opacity 2s ease-in-out, transform 7s cubic-bezier(0.1, 0.2, 0.3, 1); transform: scale(1.08); }
        .zoom-cine { transform: scale(1) !important; }

        .creditos-scroll { position: absolute; bottom: 50px; left: 0; width: 100%; text-align: center; z-index: 50; display: flex; flex-direction: column; gap: 8px; opacity: 0; transition: opacity 1.5s; }
        .titulo-proyecto { color: #ebdcb9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 8px; }
        .autor { color: #8c7d63; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 5px; }

        #portada-img { max-width: 100%; max-height: 100%; object-fit: contain; transform: scale(0.9); transition: transform 6s cubic-bezier(0.1, 0.6, 0.2, 1); }
        #contenedor-cera { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; z-index: 30; }
        .cera-fluida { width: 170px; height: 170px; background: #6b0909; border-radius: 50%; opacity: 0; filter: blur(2px); transform: scale(0.1); transition: all 4s ease-out; box-shadow: inset 0 0 20px #000, 0 0 40px #b51212; }
        .sello-impreso { position: absolute; width: 125px; height: 125px; object-fit: contain; opacity: 0; transform: scale(4.0); transition: transform 0.18s cubic-bezier(0.175, 0.885, 0.32, 1.4), opacity 0.1s ease; }

        #escena-estela { perspective: 1200px; }
        .contenedor-3d { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; transform-style: preserve-3d; }
        .estela-piedra { max-height: 55vh; object-fit: contain; filter: drop-shadow(0 0 40px rgba(235, 220, 185, 0.25)); transform: rotateX(12deg) translateZ(20px); transition: transform 3s ease; }
        .botones-piedra { display: flex; gap: 40px; margin-top: 45px; transform: translateZ(80px); }
        .btn-piedra { background: rgba(15,15,20,0.9); border: 2px solid #5c5240; color: #8c7d63; padding: 18px 45px; font-size: 1.1rem; letter-spacing: 6px; cursor: pointer; transition: all 0.3s; box-shadow: 0 10px 25px rgba(0,0,0,0.8); }
        .btn-piedra:hover { border-color: #ebdcb9; color: #ebdcb9; text-shadow: 0 0 10px #ebdcb9; transform: scale(1.08); }

        #pulso-despedida { position: fixed; inset: 0; background: #fff; opacity: 0; z-index: 99999; pointer-events: none; transition: opacity 0.08s ease-in; }
    </style>
</head>
<body>

    <canvas id="gl-canvas"></canvas>
    
    <div id="capa-inicio"><button class="btn-ritual" id="btn-play">INICIAR</button></div>

    <div id="escena-texto" class="escena">
        <div id="fase-texto" class="txt-fuego"></div>
    </div>

    <div id="escena-escritorio" class="escena">
        <div class="fondo-rustico" id="fondo"></div>
        <div class="vinyeta-luz"></div>
        <div class="parpadeo-vela" id="vela"></div>
        <div class="lienzo-papel">
            <h1 class="texto-elegante" id="texto-papel"></h1>
        </div>
    </div>

    <div id="contenedor-pergamino" class="escena">
        <img id="pergamino-img" src="">
        <div id="advertencia">Estas canciones son de otra época, y alguna puede herir su sensibilidad</div>
    </div>

    <div id="visor" class="escena"></div>

    <div id="capa-creditos" class="creditos-scroll">
        <span class="titulo-proyecto">CANTABRANA</span>
        <span id="cr-sub" class="autor">Sinfonía de Materias Vivas</span>
    </div>

    <div id="escena-portada" class="escena">
        <img id="portada-img" src="portada1.png">
        <div id="contenedor-cera">
            <div id="gota-cera" class="cera-fluida"></div>
            <img id="sello-img" class="sello-impreso" src="">
        </div>
    </div>

    <div id="escena-estela" class="escena">
        <div class="contenedor-3d">
            <img id="estela-main" class="estela-piedra" src="estela.png">
            <div class="botones-piedra">
                <button class="btn-piedra" onclick="ejecutarSalida()">SALIR</button>
                <button class="btn-piedra" onclick="ejecutarReiniciar()">REINICIAR</button>
            </div>
        </div>
    </div>

    <div id="pulso-despedida"></div>

    <audio id="snd-amb" loop></audio>
    <audio id="snd-jota"></audio>

    <script>
        const params = new URLSearchParams(window.location.search);
        let jotaId = params.get('jota');
        let materialQR = params.get('mat') !== null ? parseFloat(params.get('mat')) : null;

        const delay = ms => new Promise(res => setTimeout(res, ms));
        
        let dataGlobal = null;
        let gl, glProgram, materialIDLocation, timeLocation, resolutionLocation;
        let shaderStartTime = 0, currentMaterialID = 9.0, isShaderRunning = false;

        const familiasTexturas = {
            terciopelo: [9.0, 15.0],     
            brumaPolvo: [20.0, 21.0, 2.0], 
            magmaFuego: [0.0, 6.0, 10.0],   
            ceraViscosa: [2.0, 9.0, 6.0],  
            cenizaFinal: [21.0, 2.0]     
        };

        function obtenerTexturaFamilia(familia) {
            if (familia === 'qr' && materialQR !== null) return parseFloat(materialQR);
            const pool = familiasTexturas[familia] || [9.0];
            return pool[Math.floor(Math.random() * pool.length)];
        }

        const vertexShaderSource = `attribute vec2 position; void main() { gl_Position = vec4(position, 0.0, 1.0); }`;
        const fragmentShaderSource = `
            precision highp float;
            uniform float iTime;
            uniform vec2 iResolution;
            uniform float uMaterialID;

            vec3 hash(vec3 p) {
                p = vec3(dot(p, vec3(127.1, 311.7,  74.7)), dot(p, vec3(269.5, 183.3, 246.1)), dot(p, vec3(113.5, 271.9, 124.6)));
                return -1.0 + 2.0 * fract(sin(p) * 43758.5453123);
            }
            float noise(in vec3 p) {
                vec3 i = floor(p); vec3 f = fract(p);
                vec3 u = f * f * (3.0 - 2.0 * f);
                return mix(mix(mix(dot(hash(i+vec3(0,0,0)), f-vec3(0,0,0)), dot(hash(i+vec3(1,0,0)), f-vec3(1,0,0)), u.x),
                           mix(dot(hash(i+vec3(0,1,0)), f-vec3(0,1,0)), dot(hash(i+vec3(1,1,0)), f-vec3(1,1,0)), u.x), u.y),
                           mix(mix(dot(hash(i+vec3(0,0,1)), f-vec3(0,0,1)), dot(hash(i+vec3(1,0,1)), f-vec3(1,0,1)), u.x),
                           mix(dot(hash(i+vec3(0,1,1)), f-vec3(0,1,1)), dot(hash(i+vec3(1,1,1)), f-vec3(1,1,1)), u.x), u.y), u.z);
            }
            float fbm(vec3 p) {
                float v = 0.0, a = 0.5;
                for(int i = 0; i < 6; i++) { v += a * noise(p); p *= 2.02; a *= 0.5; }
                return v;
            }
            void main() {
                vec2 fragCoord = gl_FragCoord.xy;
                vec2 uv = (2.0 * fragCoord - iResolution.xy) / iResolution.y;
                float t = iTime * 0.15;
                uv.y += sin(uv.x * 2.5 + t * 2.0) * 0.06;
                uv.x += cos(uv.y * 2.0 + t * 1.5) * 0.04;
                vec3 p = vec3(uv * 2.5, t);
                float n = fbm(p + fbm(p + fbm(p)));
                float e = 0.01;
                vec3 normal = normalize(vec3(fbm(p + vec3(e, 0.0, 0.0)) - n, fbm(p + vec3(0.0, e, 0.0)) - n, e));
                vec3 lightDir = normalize(vec3(0.5, 0.5, 1.0));
                float diff = max(dot(normal, lightDir), 0.0);
                float spec = pow(max(dot(reflect(-lightDir, normal), vec3(0.0, 0.0, 1.0)), 0.0), 30.0);
                vec3 col = vec3(0.0);
                float ID = uMaterialID;
                if(ID == 9.0 || ID == 15.0) { 
                    vec3 baseColor = (ID == 9.0) ? vec3(0.28, 0.01, 0.05) : vec3(0.01, 0.02, 0.08);
                    col = mix(baseColor, baseColor * 0.3, n); col += vec3(0.7, 0.4, 0.5) * spec * 0.4;
                } else if(ID == 20.0 || ID == 21.0 || ID == 2.0) { 
                    col = mix(vec3(0.08, 0.08, 0.1), vec3(0.35, 0.32, 0.3), n);
                    if(ID == 21.0) col += vec3(0.9, 0.25, 0.0) * pow(n, 4.0) * 1.5;
                } else if(ID == 0.0 || ID == 6.0 || ID == 10.0) { 
                    col = mix(vec3(0.1, 0.02, 0.0), vec3(0.9, 0.3, 0.0), n); col += vec3(1.0, 0.8, 0.0) * spec * 0.5;
                } else { col = vec3(n * 0.4); }
                col = mix(col, vec3(0.0), dot(uv, uv) * 0.25);
                gl_FragColor = vec4(pow(col, vec3(0.4545)), 1.0);
            }
        `;

        function inicializarWebGL() {
            const canvas = document.getElementById('gl-canvas');
            gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
            if (!gl) return;
            canvas.width = window.innerWidth; canvas.height = window.innerHeight;
            gl.viewport(0, 0, gl.canvas.width, gl.canvas.height);
            const vs = gl.createShader(gl.VERTEX_SHADER); gl.shaderSource(vs, vertexShaderSource); gl.compileShader(vs);
            const fs = gl.createShader(gl.FRAGMENT_SHADER); gl.shaderSource(fs, fragmentShaderSource); gl.compileShader(fs);
            glProgram = gl.createProgram(); gl.attachShader(glProgram, vs); gl.attachShader(glProgram, fs); gl.linkProgram(glProgram); gl.useProgram(glProgram);
            const buffer = gl.createBuffer(); gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1,-1, 1,-1, -1,1, -1,1, 1,-1, 1,1]), gl.STATIC_DRAW);
            const pos = gl.getAttribLocation(glProgram, "position"); gl.enableVertexAttribArray(pos); gl.vertexAttribPointer(pos, 2, gl.FLOAT, false, 0, 0);
            timeLocation = gl.getUniformLocation(glProgram, "iTime"); resolutionLocation = gl.getUniformLocation(glProgram, "iResolution"); materialIDLocation = gl.getUniformLocation(glProgram, "uMaterialID");
            shaderStartTime = Date.now(); isShaderRunning = true; currentMaterialID = obtenerTexturaFamilia(materialQR !== null ? 'qr' : 'terciopelo'); renderShader();
        }

        function renderShader() {
            if (!isShaderRunning || !gl) return;
            requestAnimationFrame(renderShader);
            gl.uniform1f(timeLocation, (Date.now() - shaderStartTime) / 1000.0);
            gl.uniform2f(resolutionLocation, gl.canvas.width, gl.canvas.height);
            gl.uniform1f(materialIDLocation, currentMaterialID);
            gl.drawArrays(gl.TRIANGLES, 0, 6);
        }

        function mutarShader(familia) { if(gl) currentMaterialID = obtenerTexturaFamilia(familia); }

        async function motor() {
            // ANTI-CACHÉ ABSOLUTO PARA EL JSON
            try {
                const res = await fetch('galeria.json?v=' + Date.now());
                dataGlobal = await res.json();
            } catch(e) {}

            if (!dataGlobal || !dataGlobal.pergaminos) {
                dataGlobal = {
                    ambientes: ["ambient1.mp3"], jotas: ["jota1.mp3"], pergaminos: ["pergamino1.png"],
                    sellos: ["sello1.png"], fotos_arcos: [], fotos_detalles: []
                };
            }
            inicializarWebGL();
            document.getElementById('btn-play').addEventListener('click', comenzarRitual);
        }

        async function cambiarEscena(idVieja, idNueva, fading = 1500) {
            const vieja = document.getElementById(idVieja);
            const nueva = document.getElementById(idNueva);
            if(vieja) { vieja.style.opacity = '0'; await delay(fading); vieja.style.display = 'none'; }
            if(nueva) { nueva.style.display = 'flex'; await delay(50); nueva.style.opacity = '1'; }
        }

        async function comenzarRitual() {
            const amb = document.getElementById('snd-amb');
            const jota = document.getElementById('snd-jota');
            const txtFase = document.getElementById('fase-texto');
            
            amb.play().then(() => amb.pause()).catch(() => {});
            jota.play().then(() => jota.pause()).catch(() => {});

            mutarShader(materialQR !== null ? 'qr' : 'terciopelo');
            await cambiarEscena('capa-inicio', 'escena-texto');

            // SELECCIÓN CON ANTI-CACHÉ FORZADO POR PARÁMETRO
            if(dataGlobal.ambientes && dataGlobal.ambientes.length) {
                const randomAmb = dataGlobal.ambientes[Math.floor(Math.random() * dataGlobal.ambientes.length)];
                amb.src = "ambiente/" + randomAmb + "?v=" + Date.now();
                amb.volume = 0.4;
                amb.load();
                amb.play().catch(()=>{});
            }

            txtFase.innerText = "CANTABRANA"; txtFase.style.opacity = '1';
            await delay(2500); txtFase.style.opacity = '0'; await delay(1000);

            txtFase.innerText = "MEMORIA VIVA"; txtFase.style.opacity = '1';
            await delay(2500); txtFase.style.opacity = '0'; await delay(1000);

            mutarShader('brumaPolvo');
            await cambiarEscena('escena-texto', 'escena-escritorio');

            const txtPapel = document.getElementById('texto-papel');
            txtPapel.innerText = "Cantabrana llama al pasado,"; txtPapel.style.opacity = '1';
            await delay(3500); txtPapel.style.opacity = '0'; await delay(1000);

            txtPapel.innerText = "a través de sus puertas"; txtPapel.style.opacity = '1';
            await delay(3500); txtPapel.style.opacity = '0'; await delay(1000);

            document.getElementById('fondo').classList.add('anim-zoom-out');
            await delay(1000);

            // PASO AL PERGAMINO DIRECTO
            await cambiarEscena('escena-escritorio', 'contenedor-pergamino');
            
            if(dataGlobal.pergaminos && dataGlobal.pergaminos.length) {
                const randomPerg = dataGlobal.pergaminos[Math.floor(Math.random() * dataGlobal.pergaminos.length)];
                document.getElementById('pergamino-img').src = `assets/pergaminos/${randomPerg}?v=` + Date.now();
            }
            
            await delay(400);
            document.getElementById('advertencia').style.opacity = '1';
            
            // CRONÓMETRO INMUTABLE: A LOS 6 SEGUNDOS SALTA SÍ O SÍ
            await delay(6000); 
            document.getElementById('advertencia').style.opacity = '0';
            await delay(800);

            // SALTO AL VISOR FUERTE
            mutarShader('magmaFuego');
            await cambiarEscena('contenedor-pergamino', 'visor');
            
            amb.pause();

            let seleccionJota = jotaId;
            if(!seleccionJota && dataGlobal.jotas && dataGlobal.jotas.length) {
                seleccionJota = dataGlobal.jotas[Math.floor(Math.random() * dataGlobal.jotas.length)];
            } else if (!seleccionJota) { seleccionJota = "jota1.mp3"; }
            
            jota.src = "jotas/" + seleccionJota + "?v=" + Date.now();
            jota.load();
            await delay(300);
            jota.play().catch(() => {});
            
            const poolFotos = [];
            if(dataGlobal.fotos_arcos) dataGlobal.fotos_arcos.forEach(f => poolFotos.push("fotos/arcos_puertas/"+f));
            if(dataGlobal.fotos_detalles) dataGlobal.fotos_detalles.forEach(f => poolFotos.push("fotos/detalles/"+f));
            if(poolFotos.length === 0) poolFotos.push("portada1.png");

            poolFotos.sort(() => Math.random() - 0.5);

            document.getElementById('cr-sub').innerText = `Autor y Municipio: Cantabrana`;
            document.getElementById('capa-creditos').style.opacity = '0.9';

            ejecutarBucleFotosGarantizado(poolFotos, 5);
        }

        function ejecutarBucleFotosGarantizado(lista, limite) {
            const visor = document.getElementById('visor');
            let index = 0;

            const inyectarFoto = () => {
                if(index >= limite) { irAPortadaYClimaxFinal(); return; }
                const img = document.createElement('img');
                img.src = lista[index % lista.length] + "?v=" + Date.now();
                img.className = 'foto-film';
                visor.appendChild(img);

                setTimeout(() => {
                    img.style.opacity = '1'; img.classList.add('zoom-cine');
                    if(visor.children.length > 1) {
                        visor.children[0].style.opacity = '0';
                        setTimeout(() => { if(visor.children[0]) visor.removeChild(visor.children[0]); }, 2000);
                    }
                    index++;
                }, 100);
            };

            inyectarFoto();
            const relojCarrusel = setInterval(() => {
                if(index >= limite) { clearInterval(relojCarrusel); return; }
                inyectarFoto();
            }, 7000);
        }

        async function irAPortadaYClimaxFinal() {
            document.getElementById('capa-creditos').style.opacity = '0';
            document.getElementById('snd-jota').pause();
            mutarShader('ceraViscosa');
            
            const amb = document.getElementById('snd-amb');
            if(dataGlobal.ambientes && dataGlobal.ambientes.length) {
                const randomAmb2 = dataGlobal.ambientes[Math.floor(Math.random() * dataGlobal.ambientes.length)];
                amb.src = "ambiente/" + randomAmb2 + "?v=" + Date.now();
                amb.load(); amb.play().catch(()=>{});
            }

            await cambiarEscena('visor', 'escena-portada');
            document.getElementById('portada-img').style.transform = 'scale(1.2)';
            await delay(4000);

            document.getElementById('gota-cera').style.opacity = '1';
            document.getElementById('gota-cera').style.transform = 'scale(1)';
            await delay(4500);

            const sello = document.getElementById('sello-img');
            if(dataGlobal.sellos && dataGlobal.sellos.length) {
                const randomSello = dataGlobal.sellos[Math.floor(Math.random() * dataGlobal.sellos.length)];
                sello.src = `assets/sellos/${randomSello}?v=` + Date.now();
            }
            sello.style.opacity = '1'; sello.style.transform = 'scale(1)';
            await delay(4000);

            mutarShader('brumaPolvo');
            await cambiarEscena('escena-portada', 'escena-estela');
            document.getElementById('estela-main').style.transform = 'rotateX(15deg) translateZ(110px) scale(1.05)';
        }

        async function ejecutarSalida() {
            mutarShader('cenizaFinal');
            document.getElementById('escena-estela').style.opacity = '0';
            document.getElementById('snd-amb').pause();
            await delay(2000);
            isShaderRunning = false;
            document.getElementById('pulso-despedida').style.opacity = '1';
            await delay(200);
            document.body.innerHTML = "";
        }

        function ejecutarReiniciar() { window.location.reload(true); }

        window.onload = motor;
    </script>
</body>
</html>