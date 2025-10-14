<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800 min-h-screen font-sans">
    <!-- Botón de salida -->
    <div class="absolute top-4 right-6">
        <button onclick="location.href='{{ route('login') }}'" 
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow-md hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('images/salir.png') }}" alt="Salir" class="w-5 h-5">
            Salir
        </button>
    </div>

    <div class="max-w-8xl mx-auto py-10 px-6">
        <h1 class="text-4xl font-semibold text-center text-blue-700 mb-10 tracking-tight">
            Gerenar Reportes
        </h1>

        <!-- Formulario -->
        <form id="formReportes" class="bg-white/70 backdrop-blur-sm border border-gray-200 rounded-2xl shadow-sm p-8 text-center space-y-6 transition-all">
            <div class="flex flex-wrap justify-center gap-6">
                <div class="flex flex-col text-left">
                    <label for="mes" class="text-gray-700 font-medium mb-1">Mes</label>
                    <select name="mes" id="mes" class="border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-gray-50">
                        <option value="">-- Todos --</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex flex-col text-left">
                    <label for="dia" class="text-gray-700 font-medium mb-1">Día</label>
                    <select name="dia" id="dia" class="border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <option value="">-- Todos --</option>
                        @for($d = 1; $d <= 31; $d++)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex flex-col text-left">
                    <label for="anio" class="text-gray-700 font-medium mb-1">Año</label>
                    <input type="number" name="anio" id="anio" min="2000" max="{{ date('Y') }}" value="{{ date('Y') }}"
                        class="border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 bg-gray-50 w-32 text-center">
                </div>
            </div>

            <!-- Botones principales -->
            <div class="flex justify-center flex-wrap gap-4 mt-6">
                <button type="button" onclick="generarReporte('{{ route('reports.general') }}')" 
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition">
                     Reporte General
                </button>

                <button type="button" onclick="generarReporte('{{ route('reports.detallado') }}')" 
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition">
                     Reporte Detallado
                </button>

                <a href="{{ route('home') }}" 
                   class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition">
                     Volver al inicio
                </a>
            </div>
        </form>

        <!-- Botones rápidos -->
        <div class="flex justify-center gap-4 mt-10">
            <button type="button" onclick="generarReporteFecha('{{ route('reports.hoy') }}')" 
                class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg shadow-sm transition">
                 Reporte de Hoy
            </button>

            <button type="button" onclick="generarReporteFecha('{{ route('reports.ayer') }}')" 
                class="px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white font-medium rounded-lg shadow-sm transition">
                 Reporte de Ayer
            </button>
        </div>

        <!-- Resultado -->
        <div id="resultado" class="mt-10 bg-white rounded-2xl shadow-sm p-8 overflow-x-auto text-base border border-gray-100">
            <p class="italic text-gray-500 text-center">Aquí aparecerán los resultados...</p>
        </div>
    </div>

    <script>
    async function generarReporte(url) {
        const formData = new FormData(document.getElementById('formReportes'));
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) throw new Error('Error en la solicitud');

            const resultado = await response.json();
            mostrarResultado(resultado, url.includes('general') ? 'general' : 'detallado');
        } catch (err) {
            console.error(err);
            document.getElementById('resultado').innerHTML =
                '<p class="text-red-600 text-center mt-4">❌ Error al generar el reporte.</p>';
        }
    }

    async function generarReporteFecha(url) {
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) throw new Error('Error en la solicitud');

            const data = await response.json();
            mostrarResultado(data, 'detallado');
        } catch (err) {
            console.error(err);
            document.getElementById('resultado').innerHTML =
                '<p class="text-red-600 text-center mt-4">❌ Error al generar el reporte.</p>';
        }
    }

    function mostrarResultado(data, tipo) {
        let html = '';

        const baseTableClass = `
            w-full text-sm border-collapse rounded-xl overflow-hidden
        `;

        const thClass = `
            bg-blue-50 text-blue-700 font-semibold text-left px-4 py-3 border-b border-gray-200
        `;

        const tdClass = `
            px-4 py-2 border-b border-gray-100 text-gray-700
        `;

        if (tipo === 'general') {
            html += `
            <table class="${baseTableClass}">
                <thead>
                    <tr>
                        <th class="${thClass}">Repuesto</th>
                        <th class="${thClass}">Depósito</th>
                        <th class="${thClass} text-center">Entradas</th>
                        <th class="${thClass} text-center">Salidas</th>
                        <th class="${thClass} text-center">Existencia</th>
                    </tr>
                </thead>
                <tbody>`;

            data.resumen.forEach(item => {
                html += `
                <tr class="hover:bg-gray-50 transition">
                    <td class="${tdClass}">${item.nombre_repuesto}</td>
                    <td class="${tdClass}">${item.nombre_deposito || 'Sin depósito'}</td>
                    <td class="${tdClass} text-green-600 text-center font-semibold">${item.total_entrada || 0}</td>
                    <td class="${tdClass} text-red-600 text-center font-semibold">${item.total_salida || 0}</td>
                    <td class="${tdClass} text-center font-bold">${item.existencia || 0}</td>
                </tr>`;
            });

            html += '</tbody></table>';
        } else {
            html += `
            <table class="${baseTableClass}">
                <thead>
                    <tr>
                        <th class="${thClass}">Repuesto</th>
                        <th class="${thClass}">Depósito</th>
                        <th class="${thClass}">Tipo</th>
                        <th class="${thClass} text-center">Cantidad</th>
                        <th class="${thClass}">Solicita</th>
                        <th class="${thClass}">Entrega</th>
                        <th class="${thClass}">Autoriza</th>
                        <th class="${thClass}">Fecha</th>
                        <th class="${thClass}">Responsable</th>
                        <th class="${thClass}">Observaciones</th>
                    </tr>
                </thead>
                <tbody>`;

            data.forEach(item => {
                html += `
                <tr class="hover:bg-gray-50 transition">
                    <td class="${tdClass}">${item.nombre_repuesto}</td>
                    <td class="${tdClass}">${item.nombre_deposito || 'Sin depósito'}</td>
                    <td class="${tdClass}">${item.tipo_movimiento}</td>
                    <td class="${tdClass} text-center font-semibold">${item.cantidad}</td>
                    <td class="${tdClass}">${item.solicita || '—'}</td>
                    <td class="${tdClass}">${item.entrega || '—'}</td>
                    <td class="${tdClass}">${item.autoriza || '—'}</td>
                    <td class="${tdClass}">${item.fecha_hora}</td>
                    <td class="${tdClass}">${item.nombre_responsable || '—'}</td>
                    <td class="${tdClass}">${item.observaciones || '—'}</td>
                </tr>`;
            });

            html += '</tbody></table>';
        }

        document.getElementById('resultado').innerHTML = html || '<p class="text-gray-500 text-center mt-4">No se encontraron resultados.</p>';
    }
    </script>

</body>
</html>
