<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repuestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->integer('cantidad')->nullable();
            $table->text('descripciones')->nullable();
            $table->string('nombre_fabricante', 100);
            $table->integer('stock_actual')->default(0);
            $table->enum('estado_repuesto', ['nuevo', 'reacondicionado']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repuestos');
    }
};
