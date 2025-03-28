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
        Schema::create('estatuspre', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre', 100)->nullable();
            $table->string('color', 20)->nullable();
            $table->boolean('inactivo')->default(0);
        });

        DB::table('estatuspre')->insert([
            ['id' => 1, 'nombre' => 'PENDIENTE', 'color' => '#FFA500', 'inactivo' => 0],
            ['id' => 2, 'nombre' => 'APROBADO', 'color' => '#32CD32', 'inactivo' => 0],
            ['id' => 3, 'nombre' => 'PROYECTO', 'color' => '#4B0082', 'inactivo' => 0],
            ['id' => 4, 'nombre' => 'COMPLETADO', 'color' => '#000000', 'inactivo' => 0],
            ['id' => 5, 'nombre' => 'RECHAZADO', 'color' => '#8B0000', 'inactivo' => 0],
            ['id' => 6, 'nombre' => 'DESCARTADO', 'color' => '#E9967A', 'inactivo' => 0],
            ['id' => 7, 'nombre' => 'EN PROCESO', 'color' => '#FF8C00', 'inactivo' => 0],
            ['id' => 8, 'nombre' => 'EJECUTADO', 'color' => '#00CED1', 'inactivo' => 0],
            ['id' => 9, 'nombre' => 'PAUSADO', 'color' => '#FF69B4', 'inactivo' => 0],
            ['id' => 10, 'nombre' => 'CONTROL DE CALIDAD', 'color' => '#000000', 'inactivo' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatuspre');
    }
};
