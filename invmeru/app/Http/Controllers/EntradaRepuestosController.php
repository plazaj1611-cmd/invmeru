<?php

namespace App\Http\Controllers;

use App\Models\EntradaRepuesto;
use App\Models\Repuesto;
use App\Models\Movimiento;
use App\Models\User;
use App\Models\Deposito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntradaRepuestosController extends Controller
{
    // Mostrar todas las entradas
    public function index()
    {
        $entradas = EntradaRepuesto::with('repuesto')
                                   ->orderBy('fecha_compra', 'desc')
                                   ->get();

        return view('entradas.index', compact('entradas'));
    }

    // Formulario para crear una nueva entrada
    public function create()
    {
        $repuestos = Repuesto::orderBy('nombre', 'asc')->get();
        $depositos = Deposito::orderBy('nombre', 'asc')->get(); 

        return view('add', compact('repuestos', 'depositos')); 
    }



    // Guardar nueva entrada
    public function store(Request $request)
    {
        $validated = $request->validate([
            'repuesto_id'        => 'required|exists:repuestos,id',
            'origen_compra'      => 'required|string|max:255',
            'precio_unitario'    => 'required|numeric|min:0',
            'cantidad_adquirida' => 'required|integer|min:1',
            'fecha_compra'       => 'required|date',
            'deposito_id'        => 'required|exists:depositos,id',
            
        ]);

        try {
            /** @var \Illuminate\Contracts\Auth\Guard $auth */
            $auth = auth();

            // Crear entrada
            $entrada = EntradaRepuesto::create([
                'repuesto_id'         => $validated['repuesto_id'],
                'origen_compra'       => $validated['origen_compra'],
                'precio_unitario'     => $validated['precio_unitario'],
                'cantidad_adquirida'  => $validated['cantidad_adquirida'],
                'fecha_compra'        => $validated['fecha_compra'],
                'deposito_id'         => $validated['deposito_id'],
                'usuario_id'          => $auth->id(),
            ]);

            // Actualizar stock del repuesto
            $repuesto = Repuesto::find($validated['repuesto_id']);
            if ($repuesto) {
                $repuesto->existencia = ($repuesto->existencia ?? 0) + $validated['cantidad_adquirida'];
                $repuesto->save();
            }

            // Registrar movimiento
            /** @var \App\Models\User $usuario */
            $usuario = auth()->user();
            Movimiento::create([
                'entrada_id'      => $entrada->id,
                'repuesto_id'     => $entrada->repuesto_id,
                'deposito_id'     => $entrada->deposito_id,
                'tipo_movimiento' => 'entrada',
                'cantidad'        => $entrada->cantidad_adquirida,
                'usuario_id'      => $auth->id(),
                'usuario_nombre'  => Auth::user()->usuario ?? '—',
                'fecha'           => now(),
            ]);

            return response()->json([
                'exito'   => true,
                'mensaje' => 'Entrada registrada correctamente.',
                'entrada' => $entrada
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'Error al registrar entrada: ' . $e->getMessage()
            ], 500);
        }
    }

    // Mostrar detalle de una entrada
    public function show($id)
    {
        $entrada = EntradaRepuesto::with('repuesto')->findOrFail($id);
        return view('entradas.show', compact('entrada'));
    }

    // Eliminar entrada si no tiene movimientos asociados
    public function destroy($id)
    {
        $entrada = EntradaRepuesto::findOrFail($id);

        $movimientos = Movimiento::where('entrada_id', $entrada->id)->count();
        if ($movimientos > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar esta entrada porque tiene movimientos asociados.');
        }

        $entrada->delete();

        return redirect()->route('entradas.index')
                         ->with('success', 'Entrada eliminada correctamente.');
    }

    public function getEntradas($id)
    {
        $entradas = EntradaRepuesto::with('repuesto')
                                ->where('repuesto_id', $id)
                                ->orderBy('fecha_compra', 'desc')
                                ->get();

        return view('Lista', compact('entradas'));
    }

}
