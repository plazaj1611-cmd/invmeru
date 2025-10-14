<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EntradaRepuesto;
use Carbon\Carbon;

class EntradaRepuestosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EntradaRepuesto::create([
            'repuesto_id' => 1,
            'origen_compra' => 'Proveedor A',
            'precio_unitario' => 5.00,
            'cantidad_adquirida' => 100,
            'cantidad_disponible' => 100,
            'fecha_compra' => Carbon::now()->subDays(30),
            'usuario_id' => 1
        ]);

        EntradaRepuesto::create([
            'repuesto_id' => 2,
            'origen_compra' => 'Proveedor B',
            'precio_unitario' => 4.50,
            'cantidad_adquirida' => 50,
            'cantidad_disponible' => 50,
            'fecha_compra' => Carbon::now()->subDays(15),
            'usuario_id' => 2
        ]);
    }
}
