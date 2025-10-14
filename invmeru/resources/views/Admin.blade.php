<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opciones de Administrador</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">

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
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Opciones de Modificación</h2>

        <div class="flex flex-col gap-4">
            <a href="{{ route('inventario.index') }}" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                <img src="{{ asset('images/inventario.png') }}" alt="Consultar" class="w-10 h-10">
                Ver Inventario
            </a>

            <a href="{{ route('usuarios.index') }}" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                <img src="{{ asset('images/usuario.png') }}" alt="Consultar" class="w-10 h-10">
                Gestión de Usuarios
            </a>

            <a href="{{ route('depositos.index') }}" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                <img src="{{ asset('images/deposito.png') }}" alt="Consultar" class="w-10 h-10">
                Gestión de Depositos
            </a>

            <a href="{{ route('home') }}" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                <img src="{{ asset('images/volver.png') }}" alt="Consultar" class="w-10 h-10">
                Volver al Menú Principal
            </a>
        </div>
    </div>
</body>
</html>
