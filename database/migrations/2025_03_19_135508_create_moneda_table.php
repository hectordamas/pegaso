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
        Schema::create('moneda', function (Blueprint $table) {
            $table->id('codmoneda');
            $table->timestamps();
            $table->string('nombre', 100);
            $table->string('siglas', 3);
            $table->string('color', 100);
            $table->integer('decimales');
            $table->boolean('inactivo')->default(0);
        });

        // Insertar datos iniciales
        DB::table('moneda')->insert([
            ['codmoneda' => 1, 'nombre' => 'VEB BOLIVAR', 'siglas' => 'VEB', 'color' => '#17a2b8', 'decimales' => 2, 'inactivo' => 0],
            ['codmoneda' => 2, 'nombre' => 'USD DOLAR', 'siglas' => 'USD', 'color' => 'green', 'decimales' => 2, 'inactivo' => 0],
            ['codmoneda' => 3, 'nombre' => 'EUR EURO', 'siglas' => 'EUR', 'color' => 'purple', 'decimales' => 2, 'inactivo' => 0],
            ['codmoneda' => 4, 'nombre' => 'BTC BITCOINT', 'siglas' => 'BTC', 'color' => 'blue', 'decimales' => 6, 'inactivo' => 0],
        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moneda');
    }
};
