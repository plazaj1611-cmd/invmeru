<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use Illuminate\Http\Request;
use App\Models\Movimiento;
use Illuminate\Support\Facades\Auth;

class RepuestosController extends Controller
{
    public function index()
    {
        $repuestos = Repuesto::orderBy('nombre', 'asc')->get();
        return view('repuestos.index', compact('repuestos'));
    }

    public function create()
    {
        return view('repuestos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'            => 'required|string|max:255',
            'cantidad'          => 'required|integer|min:1',
            'descripcion'       => 'nullable|string',
            'nombre_fabricante' => 'nullable|string|max:255',
            'estado_repuesto'   => 'required|string|in:nuevo,reacondicionado',
        ]);

        try {
            $repuesto = Repuesto::create($validated);

            Movimiento::registrar($repuesto, $validated['cantidad'], 'entrada', 'Registro inicial de stock');

            return response()->json([
                'mensaje' => 'Repuesto creado exitosamente.',
                'exito'   => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al crear el repuesto: ' . $e->getMessage(),
                'exito'   => false
            ]);
        }
    }

    public function buscarAjax(Request $request)
    {
        $term = $request->get('term'); 

        $resultados = Repuesto::where('nombre', 'LIKE', "%{$term}%")
            ->pluck('nombre')   
            ->take(10);         

        return response()->json($resultados);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre'            => 'required|string|max:255',
            'descripcion'       => 'nullable|string',
            'cantidad'          => 'required|integer|min:0',
            'nombre_fabricante' => 'nullable|string|max:255',
            'estado_repuesto'   => 'required|string|in:nuevo,reacondicionado',
        ]);

        $repuesto = Repuesto::findOrFail($id);
        $cantidadAnterior = $repuesto->cantidad;

        $repuesto->update($validated);

        return redirect()->route('repuestos.index')
            ->with('success', 'Repuesto actualizado correctamente.');
    }

    public function toggleEstado(Repuesto $repuesto)
    {
        $repuesto->estado = !$repuesto->estado; 
        $repuesto->save();

        return redirect()->route('inventario.index')->with('success', 'Estado del repuesto se ha actualizado correctamente.');
    }

    public function buscar(Request $request)
    {
        $nombre = $request->input('nombreConsulta');

        $resultado = Repuesto::where('nombre', 'like', "%{$nombre}%")->first();

        return view('repuestos.consulta', [
            'resultado'      => $resultado,
            'nombreConsulta' => $nombre
        ]);
    }

    public function agregarExistencia(Request $request)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1'
        ]);

        $repuesto = Repuesto::where('nombre', $validated['nombre'])->first();

        if (!$repuesto) {
            return response()->json([
                'mensaje' => 'Repuesto no encontrado.',
                'exito'   => false
            ]);
        }

        $repuesto->cantidad += $validated['cantidad'];
        $repuesto->save();

        Movimiento::registrar($repuesto, $validated['cantidad'], 'entrada', 'Agregado desde el módulo de existencias');

        return response()->json([
            'mensaje' => 'Stock actualizado correctamente.',
            'exito'   => true
        ]);
    }
    
    public function infoRepuesto(Request $request)
{
    $nombre = $request->get('nombre');

    $repuesto = Repuesto::where('nombre', $nombre)->first();

    if (!$repuesto) {
        return response()->json(null, 404);
    }

    return response()->json([
        'nombre'      => $repuesto->nombre,
        'descripcion' => $repuesto->descripcion,
        'marca'       => $repuesto->nombre_fabricante,
        'cantidad'    => $repuesto->cantidad,
    ]);
}

    public function inventario()
    {
        $repuestos = Repuesto::orderBy('nombre', 'asc')->get();
        return view('inventario', compact('repuestos'));
    }

    // RETIRAR PARA ADMIN
    public function retirarExistenciaAdmin(Request $request)
    {
        $request->validate([
            'repuesto_id'   => 'required|exists:repuestos,id',
            'cantidad'      => 'required|integer|min:1',
            'descripcion'   => 'nullable|string|max:255',
        ]);

        $repuesto = Repuesto::findOrFail($request->repuesto_id);

        if ($request->cantidad > $repuesto->cantidad) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'No hay suficiente stock para realizar el retiro.'
            ], 400);
        }

        $repuesto->cantidad -= $request->cantidad;
        $repuesto->save();

        Movimiento::registrar(
            $repuesto,
            $request->cantidad,
            'salida',
            $request->descripcion ?? 'Retiro de stock (admin)'
        );

        return response()->json([
            'exito'       => true,
            'mensaje'     => 'Retiro realizado correctamente (admin).',
            'nuevo_stock' => $repuesto->cantidad
        ]);
    }

    public function retirarFormAdmin($id)
    {
        $repuesto = Repuesto::findOrFail($id);
        return view('Retirar', compact('repuesto'));
    }

    // RETIRAR PARA USUARIO NORMAL
    public function retirarExistenciaNormal(Request $request)
    {
        $request->validate([
            'repuesto_id'   => 'required|exists:repuestos,id',
            'cantidad'      => 'required|integer|min:1',
            'descripcion'   => 'nullable|string|max:255',
        ]);

        $repuesto = Repuesto::findOrFail($request->repuesto_id);

        if ($request->cantidad > $repuesto->cantidad) {
            return response()->json([
                'message' => 'No hay suficiente stock para realizar el retiro.'
            ], 400);
        }

        $repuesto->cantidad -= $request->cantidad;
        $repuesto->save();

        Movimiento::registrar(
            $repuesto,
            $request->cantidad,
            'salida',
            $request->descripcion ?? 'Retiro de stock (usuario)'
        );

        return response()->json([
            'message' => 'Retiro realizado correctamente (usuario).',
            'nuevo_stock' => $repuesto->cantidad
        ]);
    }

    public function retirarFormNormal($id)
    {
        $repuesto = Repuesto::findOrFail($id);
        return view('Retirarn', compact('repuesto'));
    }
}
