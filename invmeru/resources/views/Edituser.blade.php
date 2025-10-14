<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.css" rel="stylesheet" type="text/css" />
    <!-- Animate.css -->
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 flex items-center justify-center font-sans">

    <!-- Botón de salida -->
    <div class="absolute top-4 right-6">
        <button onclick="location.href='{{ route('login') }}'" 
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('images/salir.png') }}" alt="Salir" class="w-5 h-5">
            Salir
        </button>
    </div>

    <div class="w-full max-w-md bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl animate__animated animate__fadeIn">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Editar Usuario</h2>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="alert alert-error shadow-lg mb-4 animate__animated animate__fadeIn">
                <ul class="list-disc pl-5 m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('usuarios.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-medium">Usuario</span>
                </label>
                <input type="text" name="usuario" value="{{ old('usuario', $user->usuario) }}" required
                       class="input input-bordered w-full">
            </div>

            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-medium">Nuevo PIN</span>
                </label>
                <input type="password" name="pin" id="pin" class="input input-bordered w-full">
            </div>

            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-medium">Confirmar PIN</span>
                </label>
                <input type="password" name="pin_confirmation" id="pin_confirmation" class="input input-bordered w-full">
            </div>

            <div class="flex justify-between mt-4">
                <button type="submit" class="btn btn-primary shadow-md hover:scale-105 transition transform duration-300">
                    Actualizar
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary shadow-md hover:scale-105 transition transform duration-300">
                    Volver
                </a>
            </div>
        </form>
    </div>

</body>
</html>
