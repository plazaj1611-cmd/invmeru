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
        Schema::table('repuestos', function (Blueprint $table) {
            $table->enum('deposito', ['Deposito A', 'Deposito B', 'Deposito C'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repuestos', function (Blueprint $table) {
            //
        });
    }
};
