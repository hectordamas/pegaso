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
        Schema::create('motivo', function (Blueprint $table) {
            $table->id('codmotivo'); // Primary Key con auto-incremento
            $table->string('nombre', 100);
            $table->string('color', 100);
            $table->boolean('inactivo')->default(false);
            $table->timestamps();
        });

        // Insertar valores iniciales
        DB::table('motivo')->insert([
            ['codmotivo' => 1, 'nombre' => 'PRESUPUESTO', 'color' => '#2AAE4C', 'inactivo' => 0],
            ['codmotivo' => 2, 'nombre' => 'LLAMAR URGENTE', 'color' => '#3039FA', 'inactivo' => 0],
            ['codmotivo' => 3, 'nombre' => 'COBRANZA', 'color' => '#590CCF', 'inactivo' => 0],
            ['codmotivo' => 4, 'nombre' => 'CLIENTE MOLESTO', 'color' => '#B87E0C', 'inactivo' => 0],
            ['codmotivo' => 5, 'nombre' => 'ABANDONO CLIENTE', 'color' => '#686665', 'inactivo' => 0],
            ['codmotivo' => 6, 'nombre' => 'SOLICITUD', 'color' => '#F86A44', 'inactivo' => 0],
            ['codmotivo' => 7, 'nombre' => 'SOPORTE', 'color' => '#F844B9', 'inactivo' => 0],
            ['codmotivo' => 8, 'nombre' => 'INFORMACION', 'color' => '#DF667A', 'inactivo' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motivo');
    }
};
