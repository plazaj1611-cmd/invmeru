<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'cantidad',
        'descripcion', 
        'nombre_fabricante',
        'estado_repuesto',
        'estado'
    ];

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}