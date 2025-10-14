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

    <!-- Botón de salida -->
    <div class="absolute top-4 right-6">
        <button onclick="location.href='{{ route('login') }}'" 
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('images/salir.png') }}" alt="Salir" class="w-5 h-5">
            Salir
        </button>
    </div>

    <div class="w-full max-w-[1400px] mx-auto px-4 py-6">
        
        {{-- Título --}}
        <h2 class="text-3xl font-bold text-blue-600 text-center mb-6">Inventario de Repuestos</h2>

        {{-- Barra de búsqueda con autocompletado --}}
        <div class="mb-6 relative max-w-md">
            <input type="text" id="busquedaRepuesto" 
                placeholder="Buscar por código o nombre..."
                autocomplete="off"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200" />
            <div id="listaSugerencias" 
                class="absolute w-full bg-blue-50 border border-blue-200 rounded-lg mt-1 shadow-md hidden z-10 max-h-40 overflow-y-auto"></div>
        </div>

        {{-- Botones superiores --}}
        <div class="flex gap-4 mb-6">
            <a href="/admin/crear_registro" 
               class="px-5 py-2 rounded-lg shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition duration-300 hover:scale-105">
               Crear Nuevo
            </a>
            <a href="{{ route('agregar.existencias') }}" 
               class="px-5 py-2 rounded-lg shadow-md bg-green-600 hover:bg-green-700 text-white font-semibold transition duration-300 hover:scale-105">
               Agregar
            </a>
            <a href="/admin" 
               class="px-5 py-2 rounded-lg shadow-md bg-gray-600 hover:bg-gray-700 text-white font-semibold transition duration-300 hover:scale-105">
               Volver al panel
            </a>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full border-collapse rounded-lg overflow-hidden shadow">
                <thead>
                    <tr class="bg-blue-600 text-white text-left">
                        <th class="px-4 py-3">Código</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3">Nuevo/Usado</th>
                        <th class="px-4 py-3">Marca</th>
                        <th class="px-4 py-3">Existencia</th>
                        <th class="px-4 py-3">Depósito</th>
                        <th class="px-4 py-3">Última Modificación</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($repuestos as $rep)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-2">{{ $rep->codigo }}</td>
                            <td class="px-4 py-2">{{ $rep->nombre }}</td>
                            <td class="px-4 py-2">{{ $rep->descripcion }}</td>
                            <td class="px-4 py-2">{{ $rep->estado_repuesto }}</td>
                            <td class="px-4 py-2">{{ $rep->nombre_fabricante }}</td>
                            <td class="px-4 py-2">{{ $rep->existencia }}</td>
                            <td class="px-4 py-2 text-center">
                                <button type="button" 
                                    data-id="{{ $rep->id }}" 
                                    class="ver-depositos px-3 py-1 rounded-lg shadow-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition duration-300 hover:scale-105">
                                    Ver depósitos
                                </button>
                            </td>
                            <td class="px-4 py-2">{{ $rep->updated_at }}</td>
                            <td class="px-4 py-2">
                                @if($rep->estado)
                                    <span class="px-2 py-1 text-xs rounded-lg bg-green-500 text-white font-medium">Activo</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-lg bg-red-500 text-white font-medium">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                {{-- Botón activar/desactivar --}}
                                <form action="{{ route('repuestos.toggle', $rep) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" 
                                        data-id="{{ $rep->id }}" 
                                        class="toggle-estado px-4 py-2 rounded-lg shadow-md 
                                            {{ $rep->estado ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700' }} 
                                            text-white text-sm font-medium transition duration-300 hover:scale-105">
                                        {{ $rep->estado ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                                {{-- Botón Ver Entradas --}}
                                <button type="button" 
                                    data-id="{{ $rep->id }}" 
                                    class="ver-entradas px-4 py-2 rounded-lg shadow-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition duration-300 hover:scale-105">
                                    Ver Entradas
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- Modal Entradass --}}
    <div id="entradasModal" 
        class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl p-6 relative animate-fade-in">
            
            <!-- Botón cerrar -->
            <button onclick="cerrarModal()" 
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Título -->
            <h2 class="text-2xl font-bold text-blue-600 text-center mb-4">
                Entradas del Repuesto
            </h2>

            <!-- Contenido -->
            <div id="entradasContent" 
                class="max-h-[420px] overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50 shadow-inner">
                <p class="text-center text-gray-500 py-10">Selecciona un repuesto para ver sus entradas...</p>
            </div>

            <!-- Pie -->
            <div class="flex justify-center mt-6">
                <button onclick="cerrarModal()" 
                        class="px-5 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700 font-medium transition duration-300">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Animación Tailwind personalizada -->
    <style>
    @keyframes fade-in {
    from { opacity: 0; transform: scale(0.97); }
    to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in {
    animation: fade-in 0.25s ease-out;
    }
    </style>
    {{-- Modal Ver Depósitos --}}
    <div id="modalDepositos" 
        class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative animate-fade-in">

            <!-- Botón cerrar -->
            <button onclick="cerrarModalDepositos()" 
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Título -->
            <h2 id="tituloModalDepositos" 
                class="text-2xl font-bold text-blue-600 text-center mb-6">
                Ubicación del Repuesto
            </h2>

            <!-- Contenido -->
            <div class="overflow-y-auto max-h-[350px] border border-gray-200 rounded-xl bg-gray-50 shadow-inner">
                <table class="w-full text-center">
                    <thead class="bg-blue-600 text-white text-sm uppercase">
                        <tr>
                            <th class="px-4 py-3 border-b border-blue-500">Depósito</th>
                            <th class="px-4 py-3 border-b border-blue-500">Existencia</th>
                        </tr>
                    </thead>
                    <tbody id="tablaDepositos" class="divide-y divide-gray-200 text-gray-700 text-sm">
                        <tr>
                            <td colspan="2" class="py-6 text-gray-500">
                                Cargando información...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Botón inferior -->
            <div class="flex justify-center mt-6">
                <button onclick="cerrarModalDepositos()" 
                        class="px-5 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700 font-medium transition duration-300">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Animación -->
    <style>
    @keyframes fade-in {
    from { opacity: 0; transform: scale(0.97); }
    to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in {
    animation: fade-in 0.25s ease-out;
    }
    </style>
    {{-- Script --}}
    <script>
    function cerrarModalDepositos() {
        document.getElementById('modalDepositos').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.ver-depositos').forEach(btn => {
            btn.addEventListener('click', () => {
                const repuestoId = btn.dataset.id;
                const modal = document.getElementById('modalDepositos');
                const tabla = document.getElementById('tablaDepositos');
                const titulo = document.getElementById('tituloModalDepositos');

                // Mostrar modal y limpiar contenido
                modal.classList.remove('hidden');
                tabla.innerHTML = `<tr><td colspan="2" class="py-3 text-gray-500">Cargando...</td></tr>`;
                titulo.textContent = '';

                // Llamada al backend
                fetch(`/repuestos/${repuestoId}/depositos`)
                    .then(res => res.json())
                    .then(data => {
                        titulo.textContent = `Ubicación del Repuesto`;
                        tabla.innerHTML = '';

                        if (data.depositos.length === 0) {
                            tabla.innerHTML = `<tr><td colspan="2" class="py-3 text-gray-500">No hay depósitos registrados</td></tr>`;
                        } else {
                            data.depositos.forEach(dep => {
                                tabla.innerHTML += `
                                    <tr>
                                        <td class="border px-3 py-2">${dep.nombre}</td>
                                        <td class="border px-3 py-2">${dep.existencia}</td>
                                    </tr>
                                `;
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        tabla.innerHTML = `<tr><td colspan="2" class="py-3 text-red-500">Error al cargar depósitos</td></tr>`;
                    });
            });
        });
    });

    function cerrarModal() {
        document.getElementById('entradasModal').classList.add('hidden');
    }
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.ver-entradas').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const repuestoId = btn.dataset.id;
                        const url = `/repuestos/${repuestoId}/entradas`;

                        // Mostrar modal
                        const modal = document.getElementById('entradasModal');
                        modal.classList.remove('hidden');

                        // Mostrar cargando
                        document.getElementById('entradasContent').innerHTML = 
                            '<p class="text-center text-gray-500">Cargando entradas...</p>';

                        // Obtener contenido con fetch
                        fetch(url)
                            .then(res => res.text())
                            .then(html => {
                                document.getElementById('entradasContent').innerHTML = html;
                            });
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                const input = document.getElementById('busquedaRepuesto');
                const lista = document.getElementById('listaSugerencias');
                const filas = document.querySelectorAll('tbody tr');

                input.addEventListener('input', () => {
                    const termino = input.value.toLowerCase().trim();
                    lista.innerHTML = '';

                    if (termino === '') {
                        lista.classList.add('hidden');
                        filas.forEach(fila => fila.classList.remove('hidden'));
                        return;
                    }

                    // Filtrar filas de la tabla
                    let coincidencias = 0;
                    filas.forEach(fila => {
                        const codigo = fila.children[0].textContent.toLowerCase();
                        const nombre = fila.children[1].textContent.toLowerCase();

                        if (codigo.includes(termino) || nombre.includes(termino)) {
                            fila.classList.remove('hidden');
                            coincidencias++;
                        } else {
                            fila.classList.add('hidden');
                        }
                    });

                    // Mostrar sugerencias (opcional visual)
                    if (coincidencias > 0) {
                        lista.innerHTML = '<div class="px-3 py-2 text-gray-600 text-sm">Resultados encontrados: ' + coincidencias + '</div>';
                        lista.classList.remove('hidden');
                    } else {
                        lista.innerHTML = '<div class="px-3 py-2 text-gray-500 text-sm">No hay coincidencias.</div>';
                        lista.classList.remove('hidden');
                    }
                });

                // Ocultar lista al perder foco
                input.addEventListener('blur', () => {
                    setTimeout(() => lista.classList.add('hidden'), 200);
                });
            });
            document.querySelectorAll('.toggle-estado').forEach(btn => {
            btn.addEventListener('click', () => {
                const repuestoId = btn.dataset.id;
                fetch(`/inventario/${repuestoId}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        // Cambiar el botón y el estado visual
                        btn.textContent = data.estado ? 'Desactivar' : 'Activar';
                        btn.classList.toggle('bg-green-600');
                        btn.classList.toggle('bg-yellow-500');
                        btn.classList.toggle('hover:bg-green-700');
                        btn.classList.toggle('hover:bg-yellow-600');

                        const estadoSpan = btn.closest('tr').querySelector('td:nth-child(9) span');
                        if(data.estado) {
                            estadoSpan.textContent = 'Activo';
                            estadoSpan.classList.remove('bg-red-500');
                            estadoSpan.classList.add('bg-green-500');
                        } else {
                            estadoSpan.textContent = 'Inactivo';
                            estadoSpan.classList.remove('bg-green-500');
                            estadoSpan.classList.add('bg-red-500');
                        }
                    } else {
                        alert('No se pudo cambiar el estado.');
                    }
                })
                .catch(err => console.error(err));
            });
        });
    </script>
</body>
</html>
