<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Depósitos</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.css" rel="stylesheet" type="text/css" />
    <!-- Animate.css -->
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">

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

    <!-- Contenedor principal -->
    <div class="max-w-5xl mx-auto bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl mt-10 animate__animated animate__fadeIn">
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Administrar Depósitos</h2>

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="p-4 mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 shadow animate__animated animate__fadeIn">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 shadow animate__animated animate__fadeIn">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 shadow animate__animated animate__fadeIn">
                <ul class="list-disc pl-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Botones de acciones --}}
        <div class="flex justify-between mb-4">
            <a href="{{ route('administrador') }}" 
            class="px-5 py-2 rounded-lg shadow-md bg-gray-600 hover:bg-gray-700 text-white font-semibold transition duration-300 hover:scale-105">
                Volver
            </a>

            <button onclick="document.getElementById('modalCrear').classList.remove('hidden'); document.getElementById('modalCrear').classList.add('flex');"
                class="px-5 py-2 rounded-lg shadow-md bg-green-600 hover:bg-green-700 text-white font-semibold transition duration-300 hover:scale-105">
                Crear Depósito
            </button>
        </div>

        {{-- Tabla de depósitos --}}
        <div class="overflow-x-auto">
            <table class="w-full border-collapse rounded-lg overflow-hidden shadow">
                <thead>
                    <tr class="bg-blue-600 text-white text-left">
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Ubicación</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($depositos as $deposito)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $deposito->nombre }}</td>
                        <td class="px-4 py-2">{{ $deposito->ubicacion }}</td>
                        <td class="px-4 py-2 text-center">
                            <button onclick="verDetalle({{ $deposito->id }})"
                                class="px-4 py-2 rounded-lg shadow-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition duration-300 hover:scale-105">
                                Ver Detalle
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">No hay depósitos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detalle -->
    <div id="modalDetalle" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-[700px] max-h-[80vh] overflow-y-auto animate__animated animate__fadeIn">
            <h3 class="text-xl font-bold text-blue-600 mb-4">Detalle del Depósito</h3>
            <div id="contenidoDetalle" class="text-gray-700 mb-4 space-y-2"></div>
            <div class="mt-6 text-right">
                <button onclick="cerrarModal()" class="px-4 py-2 rounded-lg shadow-md bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition duration-300 hover:scale-105">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Crear Depósito -->
    <div id="modalCrear" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-[500px] max-h-[80vh] overflow-y-auto animate__animated animate__fadeIn">
            <h3 class="text-xl font-bold text-green-600 mb-4">Nuevo Depósito</h3>

            <form method="POST" action="{{ route('depositos.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="ubicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="document.getElementById('modalCrear').classList.add('hidden'); document.getElementById('modalCrear').classList.remove('flex');"
                        class="px-4 py-2 rounded-lg shadow-md bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium transition duration-300 hover:scale-105">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg shadow-md bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition duration-300 hover:scale-105">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function verDetalle(id) {
        fetch(`/depositos/${id}`)
            .then(res => {
                if (!res.ok) throw new Error(`Error ${res.status}: No se pudo obtener el detalle`);
                return res.json();
            })
            .then(data => {
                // Contenido básico del depósito
                let contenido = `
                    <p><strong>Nombre:</strong> ${data.nombre}</p>
                    <p><strong>Ubicación:</strong> ${data.ubicacion ?? 'No especificada'}</p>
                    <p><strong>Descripción:</strong> ${data.descripcion ?? 'Sin descripción'}</p>
                    <p><strong>Creado:</strong> ${new Date(data.created_at).toLocaleDateString()}</p>
                `;

                // Stock actual
                let stockHTML = `<h4 class="text-lg font-semibold mt-6 mb-2">Stock actual</h4>
                    <table class="w-full border-collapse rounded-lg overflow-hidden shadow">
                        <thead>
                            <tr class="bg-blue-100 text-left">
                                <th class="px-3 py-2">Código</th>
                                <th class="px-3 py-2">Nombre</th>
                                <th class="px-3 py-2 text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>`;
                if (data.repuestos.length > 0) {
                    data.repuestos.forEach(r => {
                        stockHTML += `<tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2">${r.codigo}</td>
                            <td class="px-3 py-2">${r.nombre}</td>
                            <td class="px-3 py-2 text-right">${r.cantidad}</td>
                        </tr>`;
                    });
                } else {
                    stockHTML += `<tr>
                        <td colspan="3" class="text-center py-3 text-gray-500">No hay repuestos registrados en este depósito.</td>
                    </tr>`;
                }
                stockHTML += `</tbody></table>`;

                // Últimos movimientos
                let movimientosHTML = `<h4 class="text-lg font-semibold mt-6 mb-2">Últimos movimientos</h4>
                    <table class="w-full border-collapse rounded-lg overflow-hidden shadow">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="px-3 py-2">Tipo</th>
                                <th class="px-3 py-2">Repuesto</th>
                                <th class="px-3 py-2">Cantidad</th>
                                <th class="px-3 py-2">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>`;
                if (data.movimientos.length > 0) {
                    data.movimientos.forEach(m => {
                        movimientosHTML += `<tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2">${m.tipo}</td>
                            <td class="px-3 py-2">${m.repuesto}</td>
                            <td class="px-3 py-2">${m.cantidad}</td>
                            <td class="px-3 py-2">${m.fecha}</td>
                        </tr>`;
                    });
                } else {
                    movimientosHTML += `<tr>
                        <td colspan="4" class="text-center py-3 text-gray-500">No hay movimientos registrados.</td>
                    </tr>`;
                }
                movimientosHTML += `</tbody></table>`;

                document.getElementById('contenidoDetalle').innerHTML = contenido + stockHTML + movimientosHTML;

                const modal = document.getElementById('modalDetalle');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            })
            .catch(err => {
                console.error('Error al cargar el detalle:', err);
                alert('No se pudo cargar el detalle del depósito. Verifica la conexión o la ruta.');
            });
    }

    function cerrarModal() {
        const modal = document.getElementById('modalDetalle');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
    </script>

</body>
</html>
