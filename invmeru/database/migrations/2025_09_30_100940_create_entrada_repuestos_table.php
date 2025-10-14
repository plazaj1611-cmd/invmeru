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
        Schema::create('entrada_repuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repuesto_id')->constrained('repuestos')->onDelete('cascade');
            $table->string('origen_compra');
            $table->decimal('precio_unitario', 15, 4);
            $table->integer('cantidad_adquirida');
            $table->date('fecha_compra');
            $table->foreignId('deposito_id')->constrained('depositos')->onDelete('cascade'); 
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrada_repuestos');
    }
};