<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    protected $fillable = ['nombre', 'ubicacion', 'descripcion'];

    public function repuestos()
    {
        return $this->belongsToMany(Repuesto::class ,'entrada_repuestos')
                    ->withPivot('cantidad_adquirida', 'fecha_compra')
                    ->withTimestamps();
    }
    
}
