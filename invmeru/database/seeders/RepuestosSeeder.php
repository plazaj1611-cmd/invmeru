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
        $repuesto = new Repuesto();
        $repuesto->nombre = 'Lampara led';
        $repuesto->cantidad = 10;
        $repuesto->descripcion = 'Lámpara led sobreponer redondo blanca 18 W 85-277 V 4.000 K';
        $repuesto->nombre_fabricante = 'General lighting';
        $repuesto->stock_actual = 5;
        $repuesto->estado_repuesto = 'nuevo';
        $repuesto->save();

        $repuesto = new Repuesto();
        $repuesto->nombre = 'Set destornilladores';
        $repuesto->cantidad = 15;
        $repuesto->descripcion = 'Set 10 destornilladores para montaje punta pala y Phillips Stanley';
        $repuesto->nombre_fabricante = 'Stanley';
        $repuesto->stock_actual = 10;
        $repuesto->estado_repuesto = 'nuevo';
        $repuesto->save();

        $repuesto = new Repuesto();
        $repuesto->nombre = 'Placa base PC';
        $repuesto->cantidad = 20;
        $repuesto->descripcion = 'Placa base X99 D4 PLUS LGA 2011-3 kit xeon e5 2680 v4 procesador 4x16 = 64GB DDR4 ECC memoria RAM NVME M.2 NGFF x99';
        $repuesto->nombre_fabricante = 'Jingsha';
        $repuesto->stock_actual = 15;
        $repuesto->estado_repuesto = 'nuevo';
        $repuesto->save();
    }
}
