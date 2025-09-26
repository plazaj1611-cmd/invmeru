<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">

    {{-- Tailwind desde CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 font-sans text-gray-800">

    {{-- Botón de salida --}}
    <div class="absolute top-4 right-6">
        <button onclick="location.href='{{ route('login') }}'" 
            class="px-4 py-2 rounded-lg shadow-md bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition duration-300 hover:scale-105">
            Salir
        </button>
    </div>

    <div class="max-w-6xl mx-auto bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl mt-10">
        
        {{-- Título --}}
        <h2 class="text-3xl font-bold text-blue-600 text-center mb-6">Inventario de Repuestos</h2>

        {{-- Botones superiores --}}
        <div class="flex gap-4 mb-6">
            <a href="/admin/crear_registro" 
               class="px-5 py-2 rounded-lg shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition duration-300 hover:scale-105">
               Crear Nuevo
            </a>
            <a href="/admin/agregar_existencia" 
               class="px-5 py-2 rounded-lg shadow-md bg-green-600 hover:bg-green-700 text-white font-semibold transition duration-300 hover:scale-105">
               Agregar
            </a>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full border-collapse rounded-lg overflow-hidden shadow">
                <thead>
                    <tr class="bg-blue-600 text-white text-left">
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3">Nuevo/Reacondionado</th>
                        <th class="px-4 py-3">Marca</th>
                        <th class="px-4 py-3">Existencia</th>
                        <th class="px-4 py-3">Última Modificación</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($repuestos as $rep)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-2">{{ $rep->nombre }}</td>
                            <td class="px-4 py-2">{{ $rep->descripcion }}</td>
                            <td class="px-4 py-2">{{ $rep->estado_repuesto }}</td>
                            <td class="px-4 py-2">{{ $rep->nombre_fabricante }}</td>
                            <td class="px-4 py-2">{{ $rep->cantidad }}</td>
                            <td class="px-4 py-2">{{ $rep->updated_at }}</td>
                            <td class="px-4 py-2">
                                @if($rep->estado)
                                    <span class="px-2 py-1 text-xs rounded-lg bg-green-500 text-white font-medium">Activo</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-lg bg-red-500 text-white font-medium">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <form action="{{ route('repuestos.toggle', $rep) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="px-4 py-2 rounded-lg shadow-md 
                                               {{ $rep->estado ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700' }} 
                                               text-white text-sm font-medium transition duration-300 hover:scale-105">
                                        {{ $rep->estado ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Botón volver --}}
        <div class="mt-6">
            <a href="/admin" 
               class="px-5 py-2 rounded-lg shadow-md bg-gray-600 hover:bg-gray-700 text-white font-semibold transition duration-300 hover:scale-105">
               Volver al panel
            </a>
        </div>
    </div>
</body>
</html>
