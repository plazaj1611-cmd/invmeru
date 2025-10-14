<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada</title>
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
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('images/salir.png') }}" alt="Salir" class="w-5 h-5">
            Salir
        </button>
    </div>

    <!-- Contenedor -->
    <div class="max-w-lg mx-auto bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl animate__animated animate__fadeIn">
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Registrar Entrada</h2>

        <form id="entradaForm" class="space-y-4" method="POST" action="{{ route('entrada.store') }}">
            @csrf

            <!-- Campo Código -->
            <div>
                <label for="codigoAgregar" class="block font-semibold text-gray-700">Código del repuesto</label>
                <input type="text" name="codigo" id="codigoAgregar" autocomplete="off" required 
                    oninput="buscarPorCodigo()"
                    class="input input-bordered w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200" />
                <input type="hidden" name="repuesto_id" id="repuestoId">
            </div>

            <!-- Nombre (solo lectura) -->
            <div>
                <label for="nombreRepuesto" class="block font-semibold text-gray-700">Nombre del repuesto</label>
                <input type="text" id="nombreRepuesto" readonly
                    class="input input-bordered w-full bg-gray-100 text-gray-700 cursor-not-allowed rounded-lg border-gray-300" />
            </div>
            
            <!-- Información del repuesto -->
            <div id="infoRepuesto" class="hidden mt-4 p-4 rounded-lg bg-blue-50 border border-blue-200">
                <h3 class="text-lg font-bold text-blue-700 mb-2">Información del repuesto</h3>
                <p><span class="font-semibold">Nombre:</span> <span id="repNombre"></span></p>
                <p><span class="font-semibold">Descripción:</span> <span id="repDescripcion"></span></p>
                <p><span class="font-semibold">Marca:</span> <span id="repMarca"></span></p>
                <p><span class="font-semibold">Stock actual:</span> <span id="repStock"></span></p>
            </div>

            <!-- Origen -->
            <div>
                <label for="origenCompra" class="block font-semibold text-gray-700">Origen de compra</label>
                <input type="text" name="origen_compra" id="origenCompra" required
                    class="input input-bordered w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <!-- Precio -->
            <div>
                <label for="precioUnitario" class="block font-semibold text-gray-700">Precio unitario</label>
                <input type="number" name="precio_unitario" id="precioUnitario" min="0" step="0.01" required
                    class="input input-bordered w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <!-- Cantidad -->
            <div>
                <label for="cantidad" class="block font-semibold text-gray-700">Cantidad adquirida</label>
                <input type="number" name="cantidad_adquirida" id="cantidad" min="1" required
                    class="input input-bordered w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <!-- Fecha -->
            <div>
                <label for="fechaCompra" class="block font-semibold text-gray-700">Fecha de compra</label>
                <input type="date" name="fecha_compra" id="fechaCompra" required
                    class="input input-bordered w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <div>
                <label class="block font-semibold mb-1">Depósito Destino:</label>
                <select name="deposito_id" id="deposito_id" class="select select-bordered w-full" required>
                    <option value="">Seleccione un depósito</option>
                    @foreach($depositos as $deposito)
                        <option value="{{ $deposito->id }}">
                            {{ $deposito->nombre }} — {{ $deposito->ubicacion }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 justify-center">
                <button type="submit" class="px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">Registrar</button>
                <a href="/inventario" class="px-6 py-3 rounded-xl shadow-md bg-gray-500 hover:bg-gray-700 text-white font-semibold transition transform duration-300 hover:scale-105">Volver</a>
            </div>
        </form>


        <!-- Mensaje -->
        <div id="entradaMsg" class="hidden mt-4 p-3 rounded-lg text-center font-semibold shadow"></div>
    </div>

    <script>
        const form = document.getElementById('entradaForm');
        const msg = document.getElementById('entradaMsg');
        const repuestoId = document.getElementById('repuestoId');
        const nombreRepuesto = document.getElementById('nombreRepuesto');

        // Enviar formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            msg.className = "mt-4 p-3 rounded-lg text-center font-semibold shadow bg-blue-50 text-blue-700 animate__animated animate__fadeIn";
            msg.textContent = 'Registrando entrada...';
            msg.classList.remove('hidden');

            const formData = new FormData(form);

            fetch('{{ route('entrada.store') }}', {
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

            @if(session('success'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 rounded-lg shadow">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-3 mb-4 text-red-700 bg-red-100 rounded-lg shadow">
                    {{ session('error') }}
                </div>
            @endif

            })
            .catch(() => {
                msg.classList.remove('hidden');
                msg.textContent = "Error al conectar con el servidor.";
                msg.className = "mt-4 p-3 rounded-lg text-center font-semibold shadow bg-red-50 text-red-700 animate__animated animate__shakeX";
            });
        });

        // Buscar repuesto por código
        function buscarPorCodigo() {
            const codigo = document.getElementById('codigoAgregar').value.trim();

            if (codigo.length < 2) {
                repuestoId.value = '';
                nombreRepuesto.value = '';
                document.getElementById('infoRepuesto').classList.add('hidden');
                return;
            }

            fetch(`/api/repuestos/info?codigo=${encodeURIComponent(codigo)}`)
                .then(res => res.json())
                .then(rep => {
                    if (rep) {
                        repuestoId.value = rep.id;
                        nombreRepuesto.value = rep.nombre;

                        document.getElementById('infoRepuesto').classList.remove('hidden');
                        document.getElementById('repNombre').textContent = rep.nombre;
                        document.getElementById('repDescripcion').textContent = rep.descripcion ?? 'Sin descripción';
                        document.getElementById('repMarca').textContent = rep.marca ?? 'N/A';
                        document.getElementById('repStock').textContent = rep.existencia ?? 0;
                    } else {
                        repuestoId.value = '';
                        nombreRepuesto.value = '';
                        document.getElementById('infoRepuesto').classList.add('hidden');
                    }
                })
                .catch(err => {
                    console.error("Error cargando repuesto:", err);
                    repuestoId.value = '';
                    nombreRepuesto.value = '';
                    document.getElementById('infoRepuesto').classList.add('hidden');
                });
        }
    </script>
</body>
</html>
