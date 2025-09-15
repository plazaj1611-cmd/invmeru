<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Movimiento extends Model
{
    protected $fillable = [
        'repuesto_id',
        'nombre_repuesto',
        'cantidad',
        'tipo',
        'descripcion',
        'fecha',
        'usuario_id',
        'usuario_nombre',
    ];

    public static function registrar($repuesto, $cantidad, $tipo, $descripcion)
    {
        return self::create([
            'repuesto_id'     => $repuesto->id,
            'nombre_repuesto' => $repuesto->nombre,
            'cantidad'        => $cantidad,
            'tipo'            => $tipo,
            'descripcion'     => $descripcion,
            'fecha'           => now(),
            'usuario_id'      => Auth::id(),
            'usuario_nombre'  => Auth::user()->usuario,
        ]);
    }

    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'repuesto_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
