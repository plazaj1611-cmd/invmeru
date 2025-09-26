<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Stock</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- DaisyUI --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.css" rel="stylesheet" type="text/css" />
    {{-- Animate.css --}}
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 font-sans text-gray-800 p-6">

    <!-- Botón de salida -->
    <div class="absolute top-4 right-6">
        <button onclick="location.href='{{ route('login') }}'" 
            class="btn btn-error btn-sm rounded-lg shadow-md hover:scale-105 transition duration-300">
            Salir
        </button>
    </div>

    <!-- Contenedor -->
    <div class="max-w-lg mx-auto bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl animate__animated animate__fadeIn">
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Agregar Stock</h2>

        <form id="agregarForm" class="space-y-4">
            <div>
                <label for="nombreAgregar" class="block font-semibold text-gray-700">Nombre del producto</label>
                <input type="text" name="nombre" id="nombreAgregar" autocomplete="off" required 
                       oninput="buscarSimilaresAgregar()"
                       class="input input-bordered w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <!-- Sugerencias -->
            <div id="sugerenciasAgregar" class="hidden mt-2 bg-white border border-gray-300 rounded-lg max-h-40 overflow-y-auto text-sm shadow-md"></div>

            <!-- Info Repuesto -->
            <div id="infoRepuesto" class="hidden p-4 rounded-lg bg-blue-50 border border-blue-300 shadow">
                <p><strong>Nombre:</strong> <span id="repNombre"></span></p>
                <p><strong>Descripción:</strong> <span id="repDescripcion"></span></p>
                <p><strong>Marca:</strong> <span id="repMarca"></span></p>
                <p><strong>Stock actual:</strong> <span id="repStock"></span></p>
            </div>

            <div>
                <label for="cantidad" class="block font-semibold text-gray-700">Cantidad a agregar</label>
                <input type="number" name="cantidad" min="1" required
                       class="input input-bordered w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <!-- Botones -->
            <div class="flex gap-3 justify-center">
                <button type="submit" class="px-6 py-3 rounded-xl shadow-md  bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">Agregar</button>
                <a href="/inventario" class="px-6 py-3 rounded-xl shadow-md  bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">Volver</a>
            </div>
        </form>

        <!-- Mensaje -->
        <div id="agregarMsg" class="hidden mt-4 p-3 rounded-lg text-center font-semibold shadow"></div>
    </div>

    <script>
        const form = document.getElementById('agregarForm');
        const msg = document.getElementById('agregarMsg');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            msg.className = "mt-4 p-3 rounded-lg text-center font-semibold shadow bg-blue-50 text-blue-700 animate__animated animate__fadeIn";
            msg.textContent = 'Agregando stock...';
            msg.classList.remove('hidden');

            const formData = new FormData(form);

            fetch('/repuestos/agregar', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                msg.classList.remove('hidden');
                msg.textContent = data.mensaje;

                if (data.exito) {
                    msg.className = "mt-4 p-3 rounded-lg text-center font-semibold shadow bg-green-50 text-green-700 animate__animated animate__fadeIn";
                    form.reset();
                    document.getElementById('infoRepuesto').classList.add('hidden');
                } else {
                    msg.className = "mt-4 p-3 rounded-lg text-center font-semibold shadow bg-red-50 text-red-700 animate__animated animate__shakeX";
                }
            })
            .catch(() => {
                msg.classList.remove('hidden');
                msg.textContent = "Error al conectar con el servidor.";
                msg.className = "mt-4 p-3 rounded-lg text-center font-semibold shadow bg-red-50 text-red-700 animate__animated animate__shakeX";
            });
        });

        function buscarSimilaresAgregar() {
            const input = document.getElementById('nombreAgregar');
            const sugerencias = document.getElementById('sugerenciasAgregar');
            const query = input.value.trim();

            if (query.length < 2) {
                sugerencias.innerHTML = '';
                sugerencias.classList.add('hidden');
                return;
            }

            fetch(`/api/repuestos/buscar?term=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    sugerencias.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.textContent = item;
                            div.className = "px-3 py-2 cursor-pointer hover:bg-blue-100 rounded transition";

                            div.onclick = function() {
                                input.value = this.textContent;
                                sugerencias.innerHTML = '';
                                sugerencias.classList.add('hidden');

                                fetch(`/api/repuestos/info?nombre=${encodeURIComponent(this.textContent)}`)
                                    .then(res => res.json())
                                    .then(rep => {
                                        if (rep) {
                                            document.getElementById('infoRepuesto').classList.remove('hidden');
                                            document.getElementById('repNombre').textContent = rep.nombre;
                                            document.getElementById('repDescripcion').textContent = rep.descripcion ?? 'Sin descripción';
                                            document.getElementById('repMarca').textContent = rep.marca ?? 'N/A';
                                            document.getElementById('repStock').textContent = rep.cantidad ?? 0;
                                        }
                                    })
                                    .catch(err => {
                                        console.error("Error cargando repuesto:", err);
                                        document.getElementById('infoRepuesto').classList.add('hidden');
                                    });
                            };

                            sugerencias.appendChild(div);
                        });
                        sugerencias.classList.remove('hidden');
                    }
                });
        }
    </script>
</body>
</html>
