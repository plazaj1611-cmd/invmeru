<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaRepuesto extends Model
{
    use HasFactory;

    protected $table = 'entrada_repuestos';

    protected $fillable = [
        'repuesto_id',
        'origen_compra',
        'precio_unitario',
        'cantidad_adquirida',
        'cantidad_disponible',
        'fecha_compra',
        'deposito_id',
        'usuario_id',
    ];

        public function repuesto()
    {
        return $this->belongsTo(Repuesto::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'entrada_id');
    }

        public function deposito()
    {
        return $this->belongsTo(Deposito::class);
    }

}
