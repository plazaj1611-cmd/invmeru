<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
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

    <!-- Contenedor principal -->
    <div class="w-full max-w-lg bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl animate__animated animate__fadeIn">

        {{-- Logo opcional --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo1.jpg') }}" alt="Logo de la empresa" class="w-24 rounded-lg shadow-md">
        </div>

        {{-- Título --}}
        <h2 class="text-3xl font-bold text-blue-600 text-center mb-6">Crear Usuario</h2>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="alert alert-error shadow-md mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Usuario</label>
                <input type="text" name="usuario" value="{{ old('usuario') }}" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">PIN (4 dígitos)</label>
                <input type="password" name="pin" maxlength="4" pattern="\d{4}" inputmode="numeric" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">Confirmar PIN</label>
                <input type="password" name="pin_confirmation" maxlength="4" pattern="\d{4}" inputmode="numeric" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block font-semibold mb-1">Rol</label>
                <select name="rol" required class="select select-bordered w-full">
                    <option value="">Seleccione un rol</option>
                    <option value="admin" {{ old('rol')=='admin' ? 'selected':'' }}>Administrador</option>
                    <option value="usuario" {{ old('rol')=='usuario' ? 'selected':'' }}>Usuario</option>
                </select>
            </div>

            <div class="flex justify-center gap-3 pt-4">
                <button type="submit" class="px-6 py-3 rounded-xl shadow-md  bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                    Guardar
                </button>
                <a href="{{ route('usuarios.index') }}" class="px-6 py-3 rounded-xl shadow-md  bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                    Volver
                </a>
            </div>
        </form>
    </div>
</body>
</html>
