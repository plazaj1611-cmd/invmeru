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

    <style>
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in {
            animation: fadeInScale 0.25s ease-out forwards;
        }
    </style>
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
            <div class="relative">
                <input type="text" name="nombreConsulta" id="nombreConsulta"
                       placeholder="Buscar repuesto..."
                       autocomplete="off"
                       value="{{ old('nombreConsulta', $nombreConsulta) }}"
                       class="input input-bordered w-full" />
                <div id="autocomplete-list" 
                     class="absolute w-full bg-blue-50 border border-blue-200 rounded-lg mt-1 shadow-md hidden z-10 max-h-40 overflow-y-auto"></div>
            </div>

            <div class="flex justify-center gap-3">
                <button type="submit" 
                        class="px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                    Buscar
                </button>
            </div>
        </form>

        {{-- Resultado --}}
        @isset($resultado)
            @if($resultado)
                @if($resultado->estado == 1)
                    <!-- Cuadro principal simplificado -->
                    <div class="mt-6 p-4 rounded-lg bg-green-50 border border-green-400 text-green-700 shadow-md">
                        <input type="hidden" id="repuesto_id" value="{{ $resultado->id }}">
                        <p><strong>Código:</strong> {{ $resultado->codigo }}</p>
                        <p><strong>Nombre:</strong> {{ $resultado->nombre }}</p>
                        <p><strong>Cantidad:</strong> {{ $resultado->existencia }}</p>

                        <div class="mt-4 flex space-x-3">
                            <a href="{{ route('retirar.normal.form', ['id' => $resultado->id, 'nombre' => urlencode($resultado->nombre)]) }}" 
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow transition transform hover:scale-105">
                            Salida
                            </a>
                            <button onclick="openDetallesModal()" 
                                    class="px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition transform hover:scale-105">
                                Ver detalles
                            </button>
                        </div>
                    </div>

                    <!-- Modal Detalles -->
                    <div id="detallesModal" class="fixed inset-0 hidden flex items-center justify-center z-50">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 animate-fade-in">
                            <h2 class="text-xl font-bold text-green-700 mb-4">Detalles del Repuesto</h2>

                            <div class="space-y-2 text-gray-700">
                                <p><strong>Marca:</strong> {{ $resultado->nombre_fabricante }}</p>
                                <p><strong>Descripción:</strong> {{ $resultado->descripcion }}</p>
                            </div>

                            <div class="mt-4 border-t pt-3">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Origen de Compra</h3>
                                <button onclick="openEntradasModal()" 
                                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow transition">
                                    Ver entradas
                                </button>
                            </div>

                            <div class="mt-4 border-t pt-3">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Depósitos Disponibles</h3>
                                <button onclick="openDepositosModal()" 
                                        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow transition">
                                    Ver depósitos
                                </button>
                            </div>

                            <div class="mt-6 text-right">
                                <button onclick="closeDetallesModal()" 
                                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg shadow transition">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL ENTRADAS -->
                    <div id="entradasModal" 
                        class="fixed inset-0 hidden flex items-center justify-center z-50">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl p-6 relative animate-fade-in">
                            <button onclick="closeEntradasModal()" 
                                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                    class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <h2 class="text-2xl font-bold text-blue-600 text-center mb-4">Entradas del Repuesto</h2>
                            <div id="entradasContent" 
                                class="max-h-[420px] overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50 shadow-inner">
                                <p class="text-center text-gray-500 py-10">Cargando entradas...</p>
                            </div>
                            <div class="flex justify-center mt-6">
                                <button onclick="closeEntradasModal()" 
                                        class="px-5 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700 font-medium transition duration-300">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Depósitos -->
                    <div id="depositosModal" class="fixed inset-0 hidden flex items-center justify-center z-50">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6 animate-fade-in">
                            <h2 class="text-xl font-bold text-yellow-700 mb-4">Depósitos Disponibles</h2>
                            <div class="border border-gray-200 rounded-xl bg-gray-50 shadow-inner p-4 max-h-[350px] overflow-y-auto">
                                <table class="w-full text-center">
                                    <thead class="bg-blue-600 text-white text-sm uppercase">
                                        <tr>
                                            <th class="px-4 py-2 border-b border-blue-500">Depósito</th>
                                            <th class="px-4 py-2 border-b border-blue-500">Existencia</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaDepositosRetirar" class="divide-y divide-gray-200 text-gray-700 text-sm">
                                        <tr>
                                            <td colspan="2" class="py-6 text-gray-500 text-center">Cargando información...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-6 text-right">
                                <button onclick="closeDepositosModal()" 
                                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg shadow transition">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mt-6 shadow-md">
                        El repuesto <strong>{{ $resultado->nombre }}</strong> está desactivado, no se pueden realizar retiros.
                    </div>
                @endif
            @else
                <div class="alert alert-error mt-6 shadow-md">
                    No se encontró el repuesto "<strong>{{ $nombreConsulta }}</strong>"
                </div>
            @endif
        @endisset
    </div>

    <script>
    // --- Autocompletado ---
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
                        div.classList.add("p-2", "hover:bg-blue-100", "cursor-pointer", "rounded-md");
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

    document.addEventListener('click', (e) => {
        if (!lista.contains(e.target) && e.target !== input) {
            lista.innerHTML = '';
            lista.classList.add('hidden');
        }
    });

    // --- Modales ---
    function openDetallesModal() { document.getElementById('detallesModal').classList.remove('hidden'); }
    function closeDetallesModal() { document.getElementById('detallesModal').classList.add('hidden'); }
    function closeEntradasModal() { document.getElementById('entradasModal').classList.add('hidden'); }
    function closeDepositosModal() { document.getElementById('depositosModal').classList.add('hidden'); }

    // --- Modal Entradas dinámico ---
// --- Modal Entradas dinámico (versión HTML) ---
function openEntradasModal() {
    const modal = document.getElementById('entradasModal');
    const content = document.getElementById('entradasContent');
    modal.classList.remove('hidden');
    content.innerHTML = `<p class="text-center text-gray-500 py-10">Cargando entradas...</p>`;

    const repuestoId = "{{ $resultado->id ?? '' }}";
    if (!repuestoId) {
        content.innerHTML = `<p class="text-center text-red-500 py-10">No se pudo identificar el repuesto.</p>`;
        return;
    }

    fetch(`{{ url('/repuestos') }}/${repuestoId}/entradasn`)
        .then(res => res.text()) 
        .then(html => {
            if (html.trim().startsWith('<')) {
                content.innerHTML = html;
            } else {
                content.innerHTML = `<p class="text-center text-red-500 py-10">No se pudo cargar correctamente el contenido.</p>`;
            }
        })
        .catch(err => {
            console.error(err);
            content.innerHTML = `<p class="text-center text-red-500 py-10">Error al cargar las entradas del repuesto.</p>`;
        });
}
    // --- Modal Depósitos dinámico ---
    function openDepositosModal() {
        const modal = document.getElementById('depositosModal');
        const tabla = document.getElementById('tablaDepositosRetirar');
        const repuestoId = document.getElementById('repuesto_id')?.value;

        modal.classList.remove('hidden');
        tabla.innerHTML = `<tr><td colspan="2" class="py-6 text-gray-500 text-center">Cargando información...</td></tr>`;

        if (!repuestoId) {
            tabla.innerHTML = `<tr><td colspan="2" class="py-6 text-red-500 text-center">ID del repuesto no encontrado.</td></tr>`;
            return;
        }

        fetch(`/repuestos/${repuestoId}/depositosn`)
            .then(res => res.json())
            .then(data => {
                if (!data.exito) {
                    tabla.innerHTML = `<tr><td colspan="2" class="py-6 text-red-500 text-center">${data.mensaje ?? "Error al obtener los datos."}</td></tr>`;
                    return;
                }

                if (!data.depositos || data.depositos.length === 0) {
                    tabla.innerHTML = `<tr><td colspan="2" class="py-6 text-gray-500 text-center">No hay depósitos registrados para este repuesto.</td></tr>`;
                    return;
                }

                tabla.innerHTML = data.depositos.map(dep => `
                    <tr>
                        <td class="px-4 py-2 border-b border-gray-200">${dep.nombre}</td>
                        <td class="px-4 py-2 border-b border-gray-200">${dep.existencia}</td>
                    </tr>
                `).join('');
            })
            .catch(err => {
                console.error(err);
                tabla.innerHTML = `<tr><td colspan="2" class="py-6 text-red-500 text-center">Error al cargar los datos del repuesto.</td></tr>`;
            });
    }
    </script>
</body>
</html>
