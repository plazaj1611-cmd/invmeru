<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion', 
        'existencia',
        'nombre_fabricante',
        'estado_repuesto',
        'deposito_id',
        'estado'
    ];

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function entradas()
    {
        return $this->hasMany(EntradaRepuesto::class);
    }

    public function depositos()
    {
        return $this->belongsToMany(Deposito::class,'entrada_repuestos')
                    ->withPivot('cantidad_adquirida', 'fecha_ingreso')
                    ->withTimestamps();
    }

}