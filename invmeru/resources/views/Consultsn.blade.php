<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Repuestos</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind desde CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- DaisyUI --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.css" rel="stylesheet" type="text/css" />
    {{-- Animate.css --}}
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-100 font-sans text-gray-800">
    {{-- Botón de salida --}}
    <div class="absolute top-4 right-6">
        <button onclick="location.href='{{ route('login') }}'" 
            class="btn btn-error btn-sm rounded-lg shadow-md hover:scale-105 transition duration-300">
            Salir
        </button>
    </div>

    <div class="w-full max-w-2xl bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl animate__animated animate__fadeIn">
        
        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo1.jpg') }}" alt="Logo de la empresa" class="w-36 rounded-lg shadow-md">
        </div>

        {{-- Títulos --}}
        <h2 class="text-3xl font-bold text-blue-600 text-center mb-2">Consulta de repuestos</h2>
        <h3 class="text-lg text-gray-600 text-center mb-6">Escriba el nombre del repuesto que desea consultar</h3>

        @php($nombreConsulta = request('nombreConsulta'))

        {{-- Formulario --}}
        <form method="POST" action="{{ route('consultar.normal') }}" class="space-y-4">
            @csrf
            <div>
                <input type="text" name="nombre" id="nombreConsulta" 
                       placeholder="Buscar repuesto..." 
                       autocomplete="off"
                       value="{{ old('nombreConsulta', $nombreConsulta) }}"
                       class="input input-bordered w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring focus:ring-blue-200" />
                <div id="autocomplete-list" 
                     class="hidden mt-2 bg-white border border-gray-300 rounded-lg max-h-40 overflow-y-auto text-sm shadow-md"></div>
            </div>

            <div class="flex justify-center gap-3">
                <button type="submit" class="px-6 py-3 rounded-xl shadow-md  bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">Buscar</button>
            </div>
        </form>

        {{-- Resultado --}}
        @isset($resultado)
            @if($resultado)
                @if($resultado->estado == 1)
                    {{-- Tarjeta solo si está activo --}}
                    <div class="mt-6 p-4 rounded-lg bg-green-50 border border-green-400 text-green-700 shadow">
                        <p><strong>Nombre:</strong> {{ $resultado->nombre }}</p>
                        <p><strong>Marca:</strong> {{ $resultado->nombre_fabricante }}</p>
                        <p><strong>Descripción:</strong> {{ $resultado->descripcion }}</p>
                        <p><strong>Estado del Repuesto:</strong> {{ $resultado->estado_repuesto }}</p>
                        <p><strong>Cantidad:</strong> {{ $resultado->cantidad }}</p>

                        <div class="mt-4">
                            <a href="{{ route('retirar.normal.form', ['id' => $resultado->id, 'nombre' => urlencode($resultado->nombre)]) }}" 
                               class="px-6 py-3 rounded-xl shadow-md  bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">Salida</a>
                        </div>
                    </div>
                @else
                    <div class="mt-6 p-4 rounded-lg bg-yellow-50 border border-yellow-400 text-yellow-700 shadow">
                        El repuesto <strong>{{ $resultado->nombre }}</strong> está desactivado, no se pueden realizar retiros.
                    </div>
                @endif
            @else
                <div class="mt-6 p-4 rounded-lg bg-red-50 border border-red-400 text-red-700 shadow">
                    No se encontró el repuesto "<strong>{{ $nombreConsulta }}</strong>"
                </div>
            @endif
        @endisset
    </div>

    <script>
    const input = document.getElementById('nombreConsulta');
    const lista = document.getElementById('autocomplete-list');

    input.addEventListener('input', function() {
        const query = this.value.trim();
        lista.innerHTML = '';
        lista.classList.add('hidden');

        if (query.length < 2) return;

        fetch(`/api/repuestos/buscar?term=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                lista.innerHTML = '';
                if (data.length > 0) {
                    data.forEach((item) => {
                        const nombre = item.nombre ?? item;
                        const div = document.createElement('div');
                        div.textContent = nombre;
                        div.className = "px-3 py-2 cursor-pointer hover:bg-blue-100 rounded";
                        div.addEventListener('click', function() {
                            input.value = nombre;
                            lista.innerHTML = '';
                            lista.classList.add('hidden');
                        });
                        lista.appendChild(div);
                    });
                    lista.classList.remove('hidden');
                }
            })
            .catch(err => console.error("Error al obtener sugerencias:", err));
    });

    document.addEventListener('click', function(e) {
        if (!lista.contains(e.target) && e.target !== input) {
            lista.innerHTML = '';
            lista.classList.add('hidden');
        }
    });
    </script>
</body>
</html>
