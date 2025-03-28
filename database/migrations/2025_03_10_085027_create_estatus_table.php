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
        Schema::create('estatus', function (Blueprint $table) {
            $table->id('codestatus'); // Clave primaria
            $table->timestamps();
            $table->string('nombre', 100);
            $table->string('color', 10);
            $table->boolean('inactivo')->default(0);
        });

        DB::table('estatus')->insert([
            ['codestatus' => 1, 'nombre' => 'EN SERVICIO TECNICO', 'color' => '#BDB417', 'inactivo' => 0],
            ['codestatus' => 2, 'nombre' => 'ENTREGADO', 'color' => '#2AAE4C', 'inactivo' => 0],
            ['codestatus' => 3, 'nombre' => 'SE AVISO AL CLIENTE', 'color' => '#B5261A', 'inactivo' => 0],
            ['codestatus' => 4, 'nombre' => 'POR FACTURAR', 'color' => '#3039FA', 'inactivo' => 0],
            ['codestatus' => 5, 'nombre' => 'DAÑADO', 'color' => '#590CCF', 'inactivo' => 0],
            ['codestatus' => 6, 'nombre' => 'THE FACTORY HKA', 'color' => '#FF69B4', 'inactivo' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatus');
    }
};
