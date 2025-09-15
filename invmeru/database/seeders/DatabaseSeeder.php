<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Repuesto;
use App\Models\Movimiento;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        $this->call(RepuestosSeeder::class);
        $this->call(MovimientosSeeder::class);
    } 
}
