<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Repuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovementController extends Controller
{
    public function create()
    {
        $repuestos = Repuesto::orderBy('nombre', 'asc')->get();
        return view('movimientos.create', compact('repuestos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'repuesto_id' => 'required|exists:repuestos,id',
            'tipo_movimiento' => 'required|in:entrada,salida,removido',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255'
    ]);

    $repuesto = Repuesto::findOrFail($request->repuesto_id);

    if ($request->tipo_movimiento === 'salida' && $repuesto->cantidad < $request->cantidad) {
        return back()->withErrors(['cantidad' => 'No hay suficiente stock para realizar la salida.'])->withInput();
    }

    switch ($request->tipo_movimiento) {
        case 'entrada':
            $repuesto->cantidad += $request->cantidad;
            break;
        case 'salida':
            $repuesto->cantidad -= $request->cantidad;
            break;
        case 'removido':
            $repuesto->cantidad = $request->cantidad;
            break;
    }
    $repuesto->save();
    
    Movimiento::create([
        'repuesto_id'     => $repuesto->id,
        'nombre_repuesto' => $repuesto->nombre,
        'cantidad'        => $request->cantidad,
        'tipo'            => $request->tipo_movimiento,
        'descripcion'     => $request->descripcion ?? '',
        'fecha'           => now(),
        'usuario_id'      => Auth::id(),
        'usuario_nombre'  => Auth::user()->usuario,
    ]);

    return redirect()->route('repuestos.index')->with('success', 'Movimiento registrado correctamente.');
}

}