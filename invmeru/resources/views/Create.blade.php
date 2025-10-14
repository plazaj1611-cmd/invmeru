<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Registro</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- DaisyUI --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.css" rel="stylesheet" type="text/css" />
    {{-- Animate.css --}}
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-100 font-sans text-gray-800">

    <!-- Botón de salida -->
    <div class="absolute top-4 right-6">
        <button onclick="location.href='{{ route('login') }}'" 
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('images/salir.png') }}" alt="Salir" class="w-5 h-5">
            Salir
        </button>
    </div>

    <!-- Contenedor principal -->
    <div class="w-full max-w-2xl bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl animate__animated animate__fadeIn">

        {{-- Logo opcional --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo1.jpg') }}" alt="Logo de la empresa" class="w-28 rounded-lg shadow-md">
        </div>

        {{-- Título --}}
        <h2 class="text-3xl font-bold text-blue-600 text-center mb-6">Nuevo Registro</h2>

        {{-- Formulario --}}
        <form id="crearForm" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Código del repuesto</label>
                <input type="text" name="codigo" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">Nombre</label>
                <input type="text" name="nombre" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">Cantidad</label>
                <input type="number" name="existencia" value="1" min="1" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">Descripción</label>
                <textarea name="descripcion" rows="3" class="textarea textarea-bordered w-full"></textarea>
            </div>

            <div>
                <label class="block font-semibold mb-1">Nombre del Fabricante</label>
                <input type="text" name="nombre_fabricante" class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">Origen de compra</label>
                <input type="text" name="origen_compra" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">Precio unitario</label>
                <input type="number" name="precio_unitario" min="0" step="0.01" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">Fecha de compra</label>
                <input type="date" name="fecha_compra" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">Estado del Repuesto</label>
                <select name="estado_repuesto" class="select select-bordered w-full">
                    <option value="nuevo">Nuevo</option>
                    <option value="usado">Usado</option>
                    <option value="reacondicionado">Reacondicionado</option>
                </select>
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

            <div class="flex justify-center gap-3 pt-4">
                <button type="submit" class="px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                    Guardar Registro
                </button>
                <a href="/inventario" class="px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                    Volver
                </a>
            </div>
        </form>

        {{-- Mensajes --}}
        <div id="crearMsg" class="mt-4 hidden"></div>
    </div>

    <script>
        document.getElementById('crearForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('_token', document.querySelector('[name=_token]').value);

            const msg = document.getElementById('crearMsg');
            msg.textContent = 'Guardando repuesto...';
            msg.className = "alert alert-info mt-4";

            fetch("{{ route('repuestos.store') }}", {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                msg.textContent = data.mensaje;
                msg.className = data.exito 
                    ? "alert alert-success mt-4 shadow-md" 
                    : "alert alert-error mt-4 shadow-md";
                if (data.exito) form.reset();
            })
            .catch(() => {
                msg.textContent = "Error al conectar con el servidor para crear el repuesto.";
                msg.className = "alert alert-error mt-4 shadow-md";
            });
        });
    </script>
</body>
</html>
