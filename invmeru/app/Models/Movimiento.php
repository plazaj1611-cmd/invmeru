<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Movimiento extends Model
{
    protected $fillable = [
        'entrada_id',
        'repuesto_id',
        'usuario_id',
        'usuario_nombre',
        'tipo_movimiento',
        'cantidad',
        'solicita',
        'entrega',
        'autoriza',
        'fecha',
        'observaciones',
        'deposito_id',
    ];

    public static function registrar($repuesto, $cantidad, $tipo, $solicita = null, $entrega = null, $autoriza = null, $entrada = null, $observaciones = null, $depositoId = null)
    {
        // Verificar usuario autenticado
        $usuarioId = Auth::id() ?? null;
        $usuarioNombre = Auth::user()?->name ?? 'Sistema';

        // Asegurar que el repuesto esté definido
        if (!$repuesto || !$repuesto->id) {
            throw new \Exception("El repuesto no está definido o no tiene ID.");
        }

        return self::create([
            'entrada_id'      => $entrada?->id,
            'repuesto_id'     => $repuesto->id,
            'deposito_id'     => $depositoId,
            'tipo_movimiento' => $tipo,
            'cantidad'        => $cantidad,
            'solicita'        => $solicita,
            'entrega'         => $entrega,
            'autoriza'        => $autoriza,
            'fecha'           => now(),
            'usuario_id'      => $usuarioId,
            'usuario_nombre'  => $usuarioNombre,
            'observaciones'   => $observaciones,
        ]);
    }

    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'repuesto_id');
    }

    public function entrada()
    {
        return $this->belongsTo(EntradaRepuesto::class, 'entrada_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }
}
