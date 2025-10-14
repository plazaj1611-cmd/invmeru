<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports');
    }

    public function generarGeneral(Request $request)
    {
        $request->validate([
            'mes'  => 'nullable|integer|min:1|max:12',
            'dia'  => 'nullable|integer|min:1|max:31',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $query = Movimiento::with(['repuesto','deposito']);

        if ($request->mes) $query->whereMonth('fecha', $request->mes);
        if ($request->dia) $query->whereDay('fecha', $request->dia);
        $query->whereYear('fecha', $request->anio);

        $movimientos = $query->get();

        $resumen = $movimientos->groupBy(fn($m) => $m->repuesto_id.'-'.$m->deposito_id)
            ->map(function($items){
                $primero = $items->first();
                $entradas = $items->where('tipo_movimiento','entrada')->sum('cantidad');
                $salidas  = $items->where('tipo_movimiento','salida')->sum('cantidad');

                return [
                    'nombre_repuesto' => $primero->repuesto->nombre ?? '—',
                    'nombre_deposito' => $primero->deposito->nombre ?? '—',
                    'total_entrada'   => $entradas,
                    'total_salida'    => $salidas,
                    'existencia' => $primero->repuesto->existencia ?? 0,
                ];
            })->values();

        return response()->json(['resumen' => $resumen]);
    }

    public function generarDetallado(Request $request)
    {
        $request->validate([
            'mes'  => 'nullable|integer|min:1|max:12',
            'dia'  => 'nullable|integer|min:1|max:31',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $query = Movimiento::with(['repuesto','deposito','usuario']);
        if ($request->mes) $query->whereMonth('fecha', $request->mes);
        if ($request->dia) $query->whereDay('fecha', $request->dia);
        $query->whereYear('fecha', $request->anio);

        $movimientos = $query->orderBy('fecha','desc')->get();

        $reporte = $movimientos->map(fn($mov) => [
            'nombre_repuesto'    => $mov->repuesto->nombre ?? '—',
            'nombre_deposito'    => $mov->deposito->nombre ?? '—',
            'tipo_movimiento'    => $mov->tipo_movimiento,
            'cantidad'           => $mov->cantidad,
            'solicita'           => $mov->solicita ?? '—',
            'entrega'            => $mov->entrega ?? '—',
            'autoriza'           => $mov->autoriza ?? '—',
            'fecha_hora'         => Carbon::parse($mov->fecha)->format('d-m-Y H:i'),
            'nombre_responsable' => $mov->usuario_nombre ?? $mov->usuario->name ?? '—',
            'observaciones'      => $mov->observaciones ?? '—',
        ]);

        return response()->json($reporte);
    }

    public function hoy()
    {
        $hoy = Carbon::today();
        return $this->reportePorFecha($hoy);
    }

    public function ayer()
    {
        $ayer = Carbon::yesterday();
        return $this->reportePorFecha($ayer);
    }

    private function reportePorFecha($fecha)
    {
        $movimientos = Movimiento::with(['repuesto','deposito','usuario'])
            ->whereDate('fecha', $fecha)
            ->orderBy('fecha','desc')
            ->get();

        $reporte = $movimientos->map(fn($mov) => [
            'nombre_repuesto'    => $mov->repuesto->nombre ?? '—',
            'nombre_deposito'    => $mov->deposito->nombre ?? '—',
            'tipo_movimiento'    => $mov->tipo_movimiento,
            'cantidad'           => $mov->cantidad,
            'solicita'           => $mov->solicita ?? '—',
            'entrega'            => $mov->entrega ?? '—',
            'autoriza'           => $mov->autoriza ?? '—',
            'fecha_hora'         => Carbon::parse($mov->fecha)->format('d-m-Y H:i'),
            'nombre_responsable' => $mov->usuario_nombre ?? $mov->usuario->name ?? '—',
            'observaciones'      => $mov->observaciones ?? '—',
        ]);

        return response()->json($reporte);
    }
}