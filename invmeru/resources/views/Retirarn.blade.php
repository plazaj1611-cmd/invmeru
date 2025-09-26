<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Retiro</title>
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
            class="btn btn-error btn-sm rounded-lg shadow-md hover:scale-105 transition duration-300">
            Salir
        </button>
    </div>

    <!-- Contenedor -->
    <div class="max-w-lg mx-auto bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl animate__animated animate__fadeIn">

        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Retiro de Productos</h2>

        <!-- Formulario -->
        <form id="retiroForm" class="space-y-4">
            <input type="hidden" id="repuesto_id" name="repuesto_id" value="{{ $repuesto->id }}" required />
            <input type="hidden" id="stock_actual" value="{{ $repuesto->stock }}" />

            <div>
                <label for="nombre_repuesto" class="block font-semibold text-gray-700">Producto seleccionado:</label>
                <input type="text" id="nombre_repuesto" value="{{ $repuesto->nombre }}" 
                    readonly class="input input-bordered w-full bg-gray-100" />
            </div>

            <div>
                <label for="cantidadSalida" class="block font-semibold text-gray-700">Cantidad a retirar:</label>
                <input type="number" id="cantidadSalida" name="cantidad" min="1" required 
                    class="input input-bordered w-full" />
            </div>

            <div>
                <label for="descripcion" class="block font-semibold text-gray-700">Nota de retiro:</label>
                <textarea id="descripcion" name="descripcion" rows="2"
                    placeholder="Ej: Entregado a (Responsable) al área de (Departamento)"
                    class="textarea textarea-bordered w-full"></textarea>
            </div>

            <div class="flex justify-center gap-4 mt-6">
                <button type="submit" 
                    class="px-6 py-3 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                    Realizar Retiro
                </button>

                <button onclick="location.href='{{ route('consulta.normal') }}'" 
                    class="px-6 py-3 rounded-xl shadow-md bg-gray-500 hover:bg-gray-600 text-white font-semibold transition transform duration-300 hover:scale-105">
                    Ir a Consulta
                </button>
            </div>

    </div>

    <script>
        const form = document.getElementById('retiroForm');
        const msg = document.getElementById('msgSalida');
        const stockActual = parseInt(document.getElementById('stock_actual').value);

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const cantidad = parseInt(form.cantidad.value);
            const restante = stockActual - cantidad;

            if (restante <= 5) {
                if (!confirm("⚠ Atención: este retiro dejará el stock en " + restante +
                             " unidades. ¿Desea continuar?")) {
                    return;
                }
            }

            const data = {
                repuesto_id: form.repuesto_id.value,
                cantidad: form.cantidad.value,
                descripcion: form.descripcion.value
            };

            msg.className = "alert mt-4 shadow-md";
            msg.classList.remove("hidden");
            msg.textContent = "Procesando retiro...";

            fetch('{{ route("retirar.normal") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(async res => {
                const json = await res.json();
                if (!res.ok) throw json;
                msg.textContent = json.message;
                msg.className = "alert alert-success mt-4 shadow-md";
                form.reset();
            })
            .catch(err => {
                msg.textContent = err.message || 'Error al conectar con el servidor.';
                msg.className = "alert alert-error mt-4 shadow-md";
            });
        });
    </script>
</body>
</html>
