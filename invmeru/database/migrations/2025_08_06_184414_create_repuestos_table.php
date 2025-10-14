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
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('existencia')->default(0);
            $table->string('nombre_fabricante')->nullable();
            $table->enum('estado_repuesto', ['nuevo', 'usado', 'reacondicionado'])->default('nuevo');
            $table->boolean('estado')->default(1); 
            $table->foreignId('deposito_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repuestos');
    }
};
