<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('movimientos', function (Blueprint $table) {
            // eliminar la foreign key actual
            $table->dropForeign(['repuesto_id']);
        });

        Schema::table('movimientos', function (Blueprint $table) {
            // volver a crear la columna y la foreign key
            $table->unsignedBigInteger('repuesto_id')->nullable()->change();
            $table->foreign('repuesto_id')
                ->references('id')->on('repuestos')
                ->nullOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            //
        });
    }
};
