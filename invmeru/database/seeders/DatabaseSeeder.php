<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Repuesto;
use App\Models\Movimiento;
use App\Models\EntradaRepuesto;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            RepuestosSeeder::class,
            EntradaRepuestosSeeder::class,
            MovimientosSeeder::class,
        ]);
    }

}
