<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class ReportsController extends Controller
{
    public function index()
    {
        return view('Reports');
    }

public function generarGeneral(Request $request)
{
    $request->validate([
        'mes'  => 'nullable|integer|min:1|max:12',
        'dia'  => 'nullable|integer|min:1|max:31',
        'anio' => 'required|integer|min:2000|max:' . date('Y'),
    ]);

    $query = Movimiento::query();

    if ($request->mes) {
        $query->whereMonth('fecha', $request->mes);
    }

    if ($request->dia) {
        $query->whereDay('fecha', $request->dia);
    }

    $query->whereYear('fecha', $request->anio);

    // Unir movimientos con repuestos
    $resumen = $query->join('repuestos', 'movimientos.repuesto_id', '=', 'repuestos.id')
        ->select(
            'repuestos.id',
            'repuestos.nombre as nombre_repuesto',
            'repuestos.cantidad as existencia',
            DB::raw("SUM(CASE WHEN movimientos.tipo = 'entrada' THEN movimientos.cantidad ELSE 0 END) as total_entrada"),
            DB::raw("SUM(CASE WHEN movimientos.tipo = 'salida' THEN movimientos.cantidad ELSE 0 END) as total_salida"),
            DB::raw("SUM(CASE WHEN movimientos.tipo = 'removido' THEN movimientos.cantidad ELSE 0 END) as total_removidos")
        )
        ->groupBy('repuestos.id', 'repuestos.nombre', 'repuestos.cantidad')
        ->get();

    return response()->json([
        'anio'    => $request->anio,
        'mes'     => $request->mes,
        'dia'     => $request->dia,
        'resumen' => $resumen,
    ]);
}
    public function generarDetallado(Request $request)
    {
        $request->validate([
            'mes'  => 'nullable|integer|min:1|max:12',
            'dia'  => 'nullable|integer|min:1|max:31',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $query = Movimiento::with('usuario');

        if ($request->mes) {
            $query->whereMonth('fecha', $request->mes);
        }

        if ($request->dia) {
            $query->whereDay('fecha', $request->dia);
        }

        $query->whereYear('fecha', $request->anio);

        $movimientos = $query->get();

        $reporte = $movimientos->map(function ($mov) {
            return [
                'nombre_repuesto'      => $mov->nombre_repuesto,
                'tipo_movimiento'      => $mov->tipo,
                'cantidad_movida'      => $mov->cantidad,
                'fecha_hora'           => Carbon::parse($mov->fecha)->toDateTimeString(),
                'nombre_responsable'   => $mov->usuario_nombre ?? 'Desconocido',
                'departamento_destino' => $mov->descripcion,
            ];
        });

        return response()->json($reporte);
    }
}
