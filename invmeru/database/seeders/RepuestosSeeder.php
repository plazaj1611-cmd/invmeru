<?php

namespace Database\Seeders;

use App\Models\Repuesto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepuestosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Repuesto::create([
            'codigo' => 'REP-001',
            'nombre' => 'Filtro de aceite',
            'descripcion' => 'Filtro para motor diesel',
            'existencia' => '10',
            'nombre_fabricante' => 'Bosch',
            'estado_repuesto' => 'nuevo',
            'estado' => 1
        ]);

        Repuesto::create([
            'codigo' => 'REP-002',
            'nombre' => 'Bujía',
            'descripcion' => 'Bujía estándar',
            'existencia' => '10',
            'nombre_fabricante' => 'NGK',
            'estado_repuesto' => 'nuevo',
            'estado' => 1
        ]);
    }
}
