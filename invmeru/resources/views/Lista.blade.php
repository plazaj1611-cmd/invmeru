@if($entradas->isEmpty())
    <p class="text-center text-gray-500">No hay entradas registradas para este repuesto.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 rounded-lg shadow">
            <thead>
                <tr class="bg-blue-600 text-white text-left">
                    <th class="px-4 py-2">Fecha de Compra</th>
                    <th class="px-4 py-2">Origen de Compra</th>
                    <th class="px-4 py-2">Depósito</th>
                    <th class="px-4 py-2">Cantidad</th>
                    <th class="px-4 py-2">Precio Unitario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entradas as $e)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $e->fecha_compra }}</td>
                        <td class="px-4 py-2">{{ $e->origen_compra }}</td>
                        <td class="px-4 py-2">{{ $e->deposito->nombre ?? 'Sin depósito' }}</td>
                        <td class="px-4 py-2">{{ $e->cantidad_adquirida }}</td>
                        <td class="px-4 py-2">{{ number_format($e->precio_unitario, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
