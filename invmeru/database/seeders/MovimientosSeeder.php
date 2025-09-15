<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movimiento;

class MovimientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Movimientos de ejemplo
        Movimiento::create([
            'repuesto_id' => 1,
            'nombre_repuesto' => 'Repuesto 1',
            'usuario_id' => 1, // admin
            'tipo' => 'entrada',
            'cantidad' => 10,
        ]);

        Movimiento::create([
            'repuesto_id' => 2,
            'nombre_repuesto' => 'Repuesto 2',
            'usuario_id' => 2, // usuario normal
            'tipo' => 'salida',
            'cantidad' => 3,
        ]);

        Movimiento::create([
            'repuesto_id' => 1,
            'nombre_repuesto' => 'Repuesto 1',
            'usuario_id' => 2, // usuario normal
            'tipo' => 'salida',
            'cantidad' => 2,
        ]);
    }
}
