<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use App\Models\EntradaRepuesto;
use App\Models\Movimiento;
use App\Models\Deposito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RepuestosController extends Controller
{
    // Listar todos los repuestos
    public function index()
    {
        $repuestos = Repuesto::orderBy('nombre', 'asc')->get();
        return view('create', compact('repuestos'));
    }

    // Mostrar formulario para crear un repuesto
    public function create()
    {
        $depositos = Deposito::all();
        return view('create', compact('depositos'));
    }

    // Guardar repuesto nuevo
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'codigo'              => 'required|string|max:255',
                'nombre'              => 'required|string|max:255',
                'descripcion'         => 'nullable|string|max:1000',
                'existencia'          => 'required|integer|min:1',
                'nombre_fabricante'   => 'nullable|string|max:255',
                'estado_repuesto'     => 'required|in:nuevo,usado,reacondicionado',
                'deposito_id'         => 'required|exists:depositos,id',
                'origen_compra'       => 'required|string|max:255',
                'precio_unitario'     => 'required|numeric|min:0',
                'fecha_compra'        => 'required|date',
            ]);

            $repuesto = Repuesto::create($validated);

            EntradaRepuesto::create([
                'repuesto_id'        => $repuesto->id,
                'cantidad_adquirida' => $validated['existencia'],
                'fecha_compra'       => $validated['fecha_compra'],
                'origen_compra'      => $validated['origen_compra'],
                'precio_unitario'    => $validated['precio_unitario'],
                'deposito_id'        => $validated['deposito_id'],
                'usuario_id'         => Auth::id(), 
            ]);

            Movimiento::create([
                'entrada_id'       => null,
                'repuesto_id'      => $repuesto->id,
                'usuario_id'       => Auth::id(),
                'tipo_movimiento'  => 'entrada',
                'cantidad'         => $validated['existencia'],
                'solicita'         => null,
                'entrega'          => null,
                'autoriza'         => null,
                'fecha'            => now(),
                'observaciones'    => $validated['descripcion'],
                'deposito_id'      => $validated['deposito_id'],
            ]);

            return response()->json([
                'exito'   => true,
                'mensaje' => 'Repuesto registrado correctamente en ambas tablas.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error en store()', [
                'mensaje' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'exito'   => false,
                'mensaje' => 'Error al registrar el repuesto.',
            ], 500);
        }
    }

    // Mostrar formulario para agregar una entrada de repuesto
    public function createEntrada($repuesto_id)
    {
        $repuesto = Repuesto::findOrFail($repuesto_id);
        return view('add', compact('repuesto'));
    }

    // Retiro Admin
    public function retirarFormAdmin($repuesto_id)
    {
        $repuesto = Repuesto::findOrFail($repuesto_id);
        $depositos = Deposito::orderBy('nombre')->get();

        return view('Retirar', compact('repuesto', 'depositos'));
    }
    public function retirarExistenciaAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repuesto_id'   => 'required|exists:repuestos,id',
            'deposito_id'   => 'required|exists:depositos,id',
            'cantidad'      => 'required|integer|min:1',
            'solicita'      => 'required|string|max:100',
            'entrega'       => 'required|string|max:100',
            'autoriza'      => 'required|string|max:100',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'Datos inválidos.',
                'errores' => $validator->errors()
            ], 422);
        }

        $repuesto_id = $request->repuesto_id;
        $deposito_id = $request->deposito_id;

        $entradas = EntradaRepuesto::where('repuesto_id', $repuesto_id)
            ->where('deposito_id', $deposito_id)
            ->sum('cantidad_adquirida');

        $salidas = Movimiento::where('repuesto_id', $repuesto_id)
            ->where('deposito_id', $deposito_id)
            ->where('tipo_movimiento', 'salida')
            ->sum('cantidad');

        $existencia = $entradas - $salidas;

        if ($existencia < $request->cantidad) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'No hay suficiente existencia en ese depósito.'
            ]);
        }

        // Descontar del stock global del repuesto
        $repuesto = Repuesto::find($repuesto_id);
        $repuesto->existencia -= $request->cantidad;
        $repuesto->save();

        // Registrar el movimiento
        Movimiento::create([
            'entrada_id'      => null,
            'repuesto_id'     => $repuesto->id,
            'deposito_id'     => $deposito_id,
            'tipo_movimiento' => 'salida',
            'cantidad'        => $request->cantidad,
            'usuario_id'      => Auth::id(),
            'usuario_nombre'  => Auth::user()->usuario ?? '—',
            'fecha'           => now(),
            'observaciones'   => $request->observaciones,
            'solicita'        => $request->solicita,
            'entrega'         => $request->entrega,
            'autoriza'        => $request->autoriza,
        ]);

        return response()->json([
            'exito'   => true,
            'mensaje' => 'Retiro realizado correctamente.'
        ]);
    }

    // Retiro Normal
    public function retirarFormNormal($repuesto_id)
    {
        $repuesto = Repuesto::findOrFail($repuesto_id);
        $depositos = Deposito::orderBy('nombre')->get();

        return view('Retirarn', compact('repuesto', 'depositos'));
    }
    public function retirarExistenciaNormal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repuesto_id'   => 'required|exists:repuestos,id',
            'deposito_id'   => 'required|exists:depositos,id',
            'cantidad'      => 'required|integer|min:1',
            'solicita'      => 'required|string|max:100',
            'entrega'       => 'required|string|max:100',
            'autoriza'      => 'required|string|max:100',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'Datos inválidos.',
                'errores' => $validator->errors()
            ], 422);
        }

        $repuesto_id = $request->repuesto_id;
        $deposito_id = $request->deposito_id;

        $entradas = EntradaRepuesto::where('repuesto_id', $repuesto_id)
            ->where('deposito_id', $deposito_id)
            ->sum('cantidad_adquirida');

        $salidas = Movimiento::where('repuesto_id', $repuesto_id)
            ->where('deposito_id', $deposito_id)
            ->where('tipo_movimiento', 'salida')
            ->sum('cantidad');

        $existencia = $entradas - $salidas;

        if ($existencia < $request->cantidad) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'No hay suficiente existencia en ese depósito.'
            ]);
        }

        // Descontar del stock global del repuesto
        $repuesto = Repuesto::find($repuesto_id);
        $repuesto->existencia -= $request->cantidad;
        $repuesto->save();

        // Registrar el movimiento
        Movimiento::create([
            'entrada_id'      => null,
            'repuesto_id'     => $repuesto->id,
            'deposito_id'     => $deposito_id,
            'tipo_movimiento' => 'salida',
            'cantidad'        => $request->cantidad,
            'usuario_id'      => Auth::id(),
            'usuario_nombre'  => Auth::user()->usuario ?? '—',
            'fecha'           => now(),
            'observaciones'   => $request->observaciones,
            'solicita'        => $request->solicita,
            'entrega'         => $request->entrega,
            'autoriza'        => $request->autoriza,
        ]);

        return response()->json([
            'exito'   => true,
            'mensaje' => 'Retiro realizado correctamente.'
        ]);
    }

    // Buscar repuesto por nombre (para autocomplete)
    public function buscarAjax(Request $request)
    {
        $term = $request->get('term');

        $resultados = Repuesto::where('nombre', 'LIKE', "%{$term}%")
            ->orWhere('codigo', 'LIKE', "%{$term}%")
            ->select('id', 'codigo', 'nombre')
            ->take(10)
            ->get();

        return response()->json($resultados);
    }

    // Consultar información de un repuesto 
    public function infoRepuesto(Request $request)
    {
        $nombre = $request->get('nombre');
        $codigo = $request->get('codigo');

        $repuesto = null;

        if ($codigo) {
            $repuesto = Repuesto::where('codigo', $codigo)->first();
        } elseif ($nombre) {
            $repuesto = Repuesto::where('nombre', $nombre)->first();
        }

        if (!$repuesto) {
            return response()->json([
                'error' => 'Repuesto no encontrado'
            ], 404);
        }

        return response()->json([
            'id'          => $repuesto->id,
            'codigo'      => $repuesto->codigo,
            'nombre'      => $repuesto->nombre,
            'descripcion' => $repuesto->descripcion,
            'marca'       => $repuesto->nombre_fabricante,
            'existencia'  => $repuesto->existencia,
            'estado'      => $repuesto->estado,
        ]);
    }

    // Mostrar inventario
    public function inventario()
    {
        $repuestos = Repuesto::all();
        return view('inventario', compact('repuestos'));
    }

    // Mostrar detalle del repuesto
    public function show($id)
    {
        $repuesto = Repuesto::with('depositos')->findOrFail($id);
        return response()->json($repuesto); 
    }

    // Activar - Desactivar Repuesto
    public function toggleEstado($id)
    {
        $repuesto = Repuesto::findOrFail($id);
        $repuesto->estado = !$repuesto->estado;
        $repuesto->save();

        return response()->json([
            'success' => true,
            'estado'  => $repuesto->estado
        ]);
    }

    public function verDepositos($id)
    {
        $repuesto = Repuesto::findOrFail($id);

        $depositos = Deposito::select('depositos.id', 'depositos.nombre')
            ->join('entrada_repuestos', 'depositos.id', '=', 'entrada_repuestos.deposito_id')
            ->where('entrada_repuestos.repuesto_id', $id)
            ->groupBy('depositos.id', 'depositos.nombre')
            ->get()
            ->map(function ($d) use ($id) {
                $entradas = \App\Models\EntradaRepuesto::where('repuesto_id', $id)
                    ->where('deposito_id', $d->id)
                    ->sum('cantidad_adquirida');

                $salidas = \App\Models\Movimiento::where('repuesto_id', $id)
                    ->where('deposito_id', $d->id)
                    ->where('tipo_movimiento', 'salida')
                    ->sum('cantidad');

                return [
                    'nombre' => $d->nombre,
                    'existencia' => $entradas - $salidas,
                ];
            });

        return response()->json([
            'repuesto' => $repuesto->nombre,
            'depositos' => $depositos
        ]);
    }

    public function obtenerDepositosPorRepuesto($id)
    {
        try {
            // Buscar el repuesto
            $repuesto = Repuesto::findOrFail($id);

            // Obtener los depósitos donde existe registro de ese repuesto
            $depositos = Deposito::select('depositos.id', 'depositos.nombre')
                ->join('entrada_repuestos', 'depositos.id', '=', 'entrada_repuestos.deposito_id')
                ->where('entrada_repuestos.repuesto_id', $id)
                ->groupBy('depositos.id', 'depositos.nombre')
                ->get()
                ->map(function ($d) use ($id) {
                    // Calcular entradas totales
                    $entradas = \App\Models\EntradaRepuesto::where('repuesto_id', $id)
                        ->where('deposito_id', $d->id)
                        ->sum('cantidad_adquirida');

                    // Calcular salidas totales
                    $salidas = \App\Models\Movimiento::where('repuesto_id', $id)
                        ->where('deposito_id', $d->id)
                        ->where('tipo_movimiento', 'salida')
                        ->sum('cantidad');

                    // Calcular existencia final
                    $existencia = $entradas - $salidas;

                    return [
                        'id' => $d->id,
                        'nombre' => $d->nombre,
                        'existencia' => $existencia,
                    ];
                });

            return response()->json([
                'exito' => true,
                'repuesto' => $repuesto->nombre,
                'depositos' => $depositos
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener depósitos: ' . $e->getMessage());

            return response()->json([
                'exito' => false,
                'mensaje' => 'Error al obtener los depósitos del repuesto.'
            ], 500);
        }
    }


}
