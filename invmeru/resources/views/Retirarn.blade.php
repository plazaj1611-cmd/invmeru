<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retiro Normal de Repuestos</title>
    <link rel="icon" href="{{ asset('images/iconomeru.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            class="flex items-center gap-2 btn btn-error btn-sm rounded-lg shadow-md hover:scale-105 transition transform duration-300 text-white">
            <img src="{{ asset('images/salir.png') }}" alt="Salir" class="w-6 h-6">
            Salir
        </button>
    </div>

    <!-- Contenedor principal -->
    <div class="max-w-lg mx-auto bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl animate__animated animate__fadeIn">

        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Retiro de Repuesto</h2>

        <!-- Formulario -->
        <form id="retiroFormNormal" class="space-y-4">
            <input type="hidden" name="repuesto_id" value="{{ $repuesto->id }}" />
    
            <div>
                <label for="nombre_repuesto" class="block font-semibold text-gray-700">Repuesto seleccionado:</label>
                <input type="text" id="nombre_repuesto" name="nombre_repuesto" readonly 
                    value="{{ urldecode(request('nombre')) }}"
                    class="input input-bordered w-full bg-gray-100" />
            </div>
            
            <div class="border border-gray-200 rounded-xl bg-gray-50 shadow-inner p-4 max-h-[350px] overflow-y-auto mb-4">
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

            <div>
                <label for="deposito_id" class="block font-semibold text-gray-700">Depósito:</label>
                <select id="deposito_id" name="deposito_id" required class="select select-bordered w-full">
                    <option value="" disabled selected>Selecciona un depósito</option>
                    @foreach($depositos as $deposito)
                        <option value="{{ $deposito->id }}">{{ $deposito->nombre }} — {{ $deposito->ubicacion }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="cantidad" class="block font-semibold text-gray-700">Cantidad a retirar:</label>
                <input type="number" name="cantidad" id="cantidad" min="1" required class="input input-bordered w-full" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="solicita" class="block font-semibold text-gray-700">Solicita:</label>
                    <input type="text" name="solicita" id="solicita" required class="input input-bordered w-full" />
                </div>
                <div>
                    <label for="entrega" class="block font-semibold text-gray-700">Entrega:</label>
                    <input type="text" name="entrega" id="entrega" required class="input input-bordered w-full" />
                </div>
                <div>
                    <label for="autoriza" class="block font-semibold text-gray-700">Autoriza:</label>
                    <input type="text" name="autoriza" id="autoriza" required class="input input-bordered w-full" />
                </div>
            </div>

            <div>
                <label for="observaciones" class="block font-semibold text-gray-700 mt-4">Observaciones:</label>
                <textarea name="observaciones" id="observaciones" rows="3"
                    class="textarea textarea-bordered w-full resize-none"
                    placeholder="Escribe aquí cualquier comentario adicional..."></textarea>
            </div>

            <!-- Errores -->
            <div id="erroresValidacion" class="hidden mt-4 text-sm text-red-600 space-y-1"></div>

            <!-- Botón principal -->
            <div class="flex justify-center mt-6">
                <button type="submit"
                    class="px-6 py-3 rounded-xl w-full shadow-md bg-blue-600 hover:bg-blue-700 text-white font-semibold transition transform duration-300 hover:scale-105">
                    Realizar Retiro
                </button>
            </div>
        </form>

        <!-- Mensaje dinámico -->
        <div id="msgSalida" class="hidden mt-4"></div>

        <!-- Botones de navegación -->
        <div class="flex flex-col gap-2 mt-6">
            <a href="{{ route('consultar.normal') }}"
               class="px-6 py-3 rounded-xl shadow-md bg-gray-500 hover:bg-gray-600 text-white font-semibold transition transform duration-300 hover:scale-105 text-center">
               Volver a Consulta
            </a>
        </div>
    </div>

    <script>
        const form = document.getElementById('retiroFormNormal');
        const msg = document.getElementById('msgSalida');
        const erroresDiv = document.getElementById('erroresValidacion');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            msg.className = "alert mt-4 shadow-md";
            msg.classList.remove("hidden");
            msg.textContent = "Procesando retiro...";
            erroresDiv.innerHTML = '';
            erroresDiv.classList.add('hidden');

            fetch('{{ route("retirar.normal") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                msg.textContent = res.mensaje;
                msg.className = res.exito 
                    ? "alert alert-success mt-4 shadow-md"
                    : "alert alert-error mt-4 shadow-md";

                if (res.exito) {
                    form.reset();
                } else if (res.errores) {
                    erroresDiv.classList.remove('hidden');
                    Object.values(res.errores).forEach(errArray => {
                        errArray.forEach(err => {
                            const p = document.createElement('p');
                            p.textContent = err;
                            erroresDiv.appendChild(p);
                        });
                    });
                }
            })
            .catch(() => {
                msg.textContent = "Error al conectar con el servidor.";
                msg.className = "alert alert-error mt-4 shadow-md";
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
        const tabla = document.getElementById('tablaDepositosRetirar');
        const repNombreInput = document.getElementById('nombre_repuesto');
        const repuestoId = document.querySelector('input[name="repuesto_id"]').value;
        console.log('🧩 ID del repuesto:', repuestoId);

        if (!repuestoId) return;

        tabla.innerHTML = `<tr>
            <td colspan="2" class="py-6 text-gray-500 text-center">Cargando información...</td>
        </tr>`;

        fetch(`/repuestos/${repuestoId}/depositosn`)
            .then(res => res.json())
            .then(data => {
                if (!data.exito) {
                    tabla.innerHTML = `<tr>
                        <td colspan="2" class="py-6 text-red-500 text-center">
                            ${data.mensaje ?? "Error al obtener los datos."}
                        </td>
                    </tr>`;
                    return;
                }

                if (data.repuesto && repNombreInput) {
                    repNombreInput.value = data.repuesto;
                }

                if (!data.depositos || data.depositos.length === 0) {
                    tabla.innerHTML = `<tr>
                        <td colspan="2" class="py-6 text-gray-500 text-center">
                            No hay depósitos registrados para este repuesto.
                        </td>
                    </tr>`;
                    return;
                }

                tabla.innerHTML = '';
                data.depositos.forEach(dep => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td class="px-4 py-2 border-b border-gray-200">${dep.nombre}</td>
                        <td class="px-4 py-2 border-b border-gray-200">${dep.existencia}</td>
                    `;
                    tabla.appendChild(fila);
                });
            })
            .catch(err => {
                console.error(err);
                tabla.innerHTML = `<tr>
                    <td colspan="2" class="py-6 text-red-500 text-center">
                        Error al cargar los datos del repuesto.
                    </td>
                </tr>`;
            });
        });
    </script>
</body>
</html>
