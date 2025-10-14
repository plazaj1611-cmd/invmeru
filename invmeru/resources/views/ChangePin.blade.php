<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Usuarios</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 font-sans text-gray-800">

    <!-- Botón de salida -->
    <div class="absolute top-4 right-6">
        <button onclick="location.href='{{ route('login') }}'" 
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('images/salir.png') }}" alt="Salir" class="w-5 h-5">
            Salir
        </button>
    </div>

    <!-- Contenedor -->
    <div class="max-w-5xl mx-auto bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl mt-10">

        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Administrar Usuarios</h2>

        {{-- Mensajes de éxito o error --}}
        @if(session('success'))
            <div class="p-4 mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 shadow">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 shadow">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 shadow">
                <ul class="list-disc pl-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Botones --}}
        <div class="flex justify-end gap-3 mb-4">
            <a href="{{ route('usuarios.create') }}" 
               class="px-5 py-2 rounded-lg shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition duration-300 hover:scale-105">
                Crear Usuario
            </a>
            <a href="{{ route('administrador') }}" 
               class="px-5 py-2 rounded-lg shadow-md bg-gray-600 hover:bg-gray-700 text-white font-semibold transition duration-300 hover:scale-105">
                Volver
            </a>
        </div>

        {{-- Tabla de usuarios --}}
        <div class="overflow-x-auto">
            <table class="w-full border-collapse rounded-lg overflow-hidden shadow">
                <thead>
                    <tr class="bg-blue-600 text-white text-left">
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $user->id }}</td>
                        <td class="px-4 py-2">{{ $user->usuario }}</td>
                        <td class="px-4 py-2">{{ $user->rol }}</td>
                        <td class="px-4 py-2">
                            @if($user->estado)
                                <span class="px-2 py-1 text-xs rounded-lg bg-green-500 text-white font-medium">Activo</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-lg bg-red-500 text-white font-medium">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 flex gap-2 justify-center">
                            <a href="{{ route('usuarios.edit', $user) }}" 
                               class="px-4 py-2 rounded-lg shadow-md bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium transition duration-300 hover:scale-105">
                                Editar
                            </a>
                            <form action="{{ route('usuarios.toggle', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                    class="px-4 py-2 rounded-lg shadow-md 
                                           {{ $user->estado ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} 
                                           text-white text-sm font-medium transition duration-300 hover:scale-105">
                                    {{ $user->estado ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
