<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('estatuspre', function (Blueprint $table) {
            DB::table('estatuspre')->insert([
                ['id' => 11, 'nombre' => 'COMPRADO', 'color' => '#FFC107', 'inactivo' => 0], 
                ['id' => 12, 'nombre' => 'EN PROCESO', 'color' => '#17A2B8', 'inactivo' => 0], 
                // En proceso de compras para que no se mezclen con los proyectos
                ['id' => 13, 'nombre' => 'ENTREGADO', 'color' => '#28A745', 'inactivo' => 0],
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estatuspre', function (Blueprint $table) {
            //
        });
    }
};
