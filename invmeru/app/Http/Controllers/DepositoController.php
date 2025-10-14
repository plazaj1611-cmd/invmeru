<?php

namespace App\Http\Controllers;

use App\Models\Deposito;
use App\Models\EntradaRepuesto;
use App\Models\Repuesto;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositoController extends Controller
{
    public function index()
    {
        $depositos = Deposito::all();
        return view('Depositos', compact('depositos'));
    }

    public function show($id)
    {
        $deposito = Deposito::findOrFail($id);

        $repuestos = Repuesto::select('repuestos.id', 'repuestos.codigo', 'repuestos.nombre')
            ->join('entrada_repuestos', 'repuestos.id', '=', 'entrada_repuestos.repuesto_id')
            ->where('entrada_repuestos.deposito_id', $id)
            ->groupBy('repuestos.id', 'repuestos.codigo', 'repuestos.nombre')
            ->get()
            ->map(function ($r) use ($id) {
                $entradas = EntradaRepuesto::where('repuesto_id', $r->id)
                    ->where('deposito_id', $id)
                    ->sum('cantidad_adquirida');

                $salidas = Movimiento::where('repuesto_id', $r->id)
                    ->where('deposito_id', $id)
                    ->where('tipo_movimiento', 'salida')
                    ->sum('cantidad');

                $existencia = $entradas - $salidas;

                return [
                    'id' => $r->id,
                    'codigo' => $r->codigo,
                    'nombre' => $r->nombre,
                    'cantidad' => $existencia,
                ];
            });

        $movimientos = Movimiento::with(['repuesto', 'usuario'])
            ->where('deposito_id', $id)
            ->latest('fecha')
            ->take(10)
            ->get()
            ->map(function ($m) {
                return [
                    'tipo' => ucfirst($m->tipo_movimiento),
                    'repuesto' => optional($m->repuesto)->nombre ?? '—',
                    'cantidad' => $m->cantidad,
                    'fecha' => $m->fecha
                        ? \Carbon\Carbon::parse($m->fecha)->format('d/m/Y H:i')
                        : '—',
                ];
            });

        return response()->json([
            'nombre' => $deposito->nombre,
            'ubicacion' => $deposito->ubicacion,
            'descripcion' => $deposito->descripcion,
            'created_at' => $deposito->created_at
                ? \Carbon\Carbon::parse($deposito->created_at)->format('d/m/Y')
                : '—',
            'repuestos' => $repuestos,
            'movimientos' => $movimientos,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:depositos,nombre',
            'ubicacion' => 'nullable|string',
            'descripcion' => 'nullable|string',
        ]);

        Deposito::create($request->all());

        return redirect()->route('depositos.index')->with('success', 'Depósito creado exitosamente.');
    }

}
