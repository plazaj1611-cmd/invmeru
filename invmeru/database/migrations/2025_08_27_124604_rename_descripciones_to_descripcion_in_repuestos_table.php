<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repuestos', function (Blueprint $table) {
            $table->renameColumn('descripciones', 'descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('repuestos', function (Blueprint $table) {
            $table->renameColumn('descripcion', 'descripciones');
        });
    }
};
