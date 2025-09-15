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
    Schema::create('movimientos', function (Blueprint $table) {
        $table->id();
        
        // Relación con repuestos
        $table->unsignedBigInteger('repuesto_id')->nullable();
        $table->foreign('repuesto_id')->references('id')->on('repuestos')->nullOnDelete();

        $table->string('nombre_repuesto'); 
        $table->enum('tipo', ['entrada', 'salida', 'removido']); 
        $table->integer('cantidad');

        $table->string('descripcion')->nullable(); 
        $table->timestamp('fecha')->useCurrent();  

        // Usuario que hizo el movimiento
        $table->unsignedBigInteger('usuario_id')->nullable()->change();
        $table->foreign('usuario_id')->references('id')->on('users')->nullOnDelete();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
