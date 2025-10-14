<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movimiento;
use Carbon\Carbon;

class MovimientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Movimiento::create([
            'entrada_id' => 1,
            'repuesto_id' => 1,
            'usuario_id' => 1,
            'usuario_nombre' => 'Admin',
            'tipo_movimiento' => 'entrada',
            'cantidad' => 100,
            'solicita' => 'Compras',
            'entrega' => 'Almacén',
            'autoriza' => 'Gerente',
            'fecha' => Carbon::now()->subDays(30),
            'observaciones' => 'Compra inicial'
        ]);

        Movimiento::create([
            'entrada_id' => 2,
            'repuesto_id' => 2,
            'usuario_id' => 2,
            'usuario_nombre' => 'Operador',
            'tipo_movimiento' => 'entrada',
            'cantidad' => 50,
            'solicita' => 'Compras',
            'entrega' => 'Almacén',
            'autoriza' => 'Gerente',
            'fecha' => Carbon::now()->subDays(15),
            'observaciones' => 'Segunda compra'
        ]);
    }
}
