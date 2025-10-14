<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Repuestos</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">

    {{-- Tailwind desde CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- DaisyUI desde CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.css" rel="stylesheet" type="text/css" />
    {{-- Animate.css --}}
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 via-white to-gray-100 font-sans text-gray-800">

    <!-- Botón de salida -->
    <div class="absolute top-4 right-6">
        <button onclick="location.href='{{ route('login') }}'" 
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('images/salir.png') }}" alt="Salir" class="w-5 h-5">
            Salir
        </button>
    </div>

    <div class="w-full max-w-2xl bg-white/90 backdrop-blur-xl p-10 rounded-2xl shadow-2xl text-center animate__animated animate__fadeIn">
        
        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo1.jpg') }}" alt="Logo de la empresa" class="w-36 rounded-xl shadow-lg animate__animated animate__pulse animate__infinite">
        </div>

        <h2 class="text-3xl font-bold text-blue-600 mb-2">Bienvenido al sistema de gestión de inventario</h2>
        <h3 class="text-lg text-gray-600 mb-8">Seleccione una opción para continuar:</h3>

        {{-- Menú --}}
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('consultar.producto') }}"
                class="flex items-center gap-2 px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                <img src="{{ asset('images/buscar.png') }}" alt="Consultar" class="w-10 h-10">
                Consultar Producto
            </a>
            <a href="{{ route('reports.index') }}" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                <img src="{{ asset('images/reporte.png') }}" alt="Consultar" class="w-10 h-10">
                Reportes
            </a>
            <a href="{{ route('administrador') }}" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                <img src="{{ asset('images/modificar.png') }}" alt="Consultar" class="w-10 h-10">
                Modificación
            </a>
        </div>
    </div>

</body>
</html>
