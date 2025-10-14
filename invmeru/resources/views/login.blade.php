<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventario de Repuestos</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">

    {{-- Tailwind desde CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- DaisyUI desde CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.css" rel="stylesheet" type="text/css" />
    {{-- Animate.css --}}
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 via-white to-gray-100 font-sans">

    <div class="w-full max-w-md p-8 bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl animate__animated animate__fadeIn">
        
        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo1.jpg') }}" alt="Logo de la empresa" class="w-28 rounded-xl shadow-lg animate__animated animate__pulse animate__infinite">
        </div>

        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6 tracking-wide">Iniciar Sesión</h2>

        @if(session('error'))
            <div class="alert alert-error shadow-lg mb-4">
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login.procesar') }}" class="space-y-5">
            @csrf

            {{-- Usuario --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text text-gray-700">Usuario</span>
                </label>
                <label class="input input-bordered flex items-center gap-2 bg-gray-50 border-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9.004 9.004 0 0112 15c2.485 0 4.735.998 6.379 2.621M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <input type="text" name="usuario" class="grow bg-transparent focus:outline-none text-gray-900" placeholder="Ingresa tu usuario" required>
                </label>
            </div>

            {{-- Pin --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text text-gray-700">Pin</span>
                </label>
                <label class="input input-bordered flex items-center gap-2 bg-gray-50 border-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657-1.343-3-3-3S6 9.343 6 11v2H5a2 2 0 00-2 2v6h18v-6a2 2 0 00-2-2h-1v-2c0-1.657-1.343-3-3-3s-3 1.343-3 3z"/>
                    </svg>
                    <input type="password" name="password" class="grow bg-transparent focus:outline-none text-gray-900" placeholder="••••••" required>
                </label>
            </div>

            {{-- Botón --}}
            <button type="submit" 
                class="btn btn-primary w-full mt-4 bg-blue-600 border-none hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition duration-300 animate__animated animate__fadeInUp">
                Ingresar
            </button>
        </form>

        {{-- Footer opcional --}}
        <p class="text-center text-gray-500 mt-6 text-sm">
            © {{ date('Y') }} Invmeru
        </p>
    </div>

</body>
</html>
