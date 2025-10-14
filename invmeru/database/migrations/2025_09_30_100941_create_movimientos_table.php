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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrada_id')->nullable()->constrained('entrada_repuestos')->onDelete('set null');
            $table->foreignId('repuesto_id')->constrained('repuestos')->onDelete('cascade');
            $table->foreignId('deposito_id')->constrained('depositos')->onDelete('cascade'); // ← esta es la línea nueva
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo_movimiento', ['entrada', 'salida', 'ajuste']);
            $table->integer('cantidad');
            $table->string('solicita')->nullable();
            $table->string('entrega')->nullable();
            $table->string('autoriza')->nullable();
            $table->dateTime('fecha');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};