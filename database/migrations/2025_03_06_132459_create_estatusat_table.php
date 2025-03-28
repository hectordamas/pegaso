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
        Schema::create('estatusat', function (Blueprint $table) {
            $table->id("codestatus");
            $table->timestamps();
            $table->string('nombre');
            $table->string('color');
            $table->boolean('inactivo')->default(false);
        });

        DB::table('estatusat')->insert([
            ['codestatus' => 1, 'nombre' => 'PENDIENTE', 'color' => '#FFA500', 'inactivo' => 0],
            ['codestatus' => 2, 'nombre' => 'EN ESPERA', 'color' => '#E9967A', 'inactivo' => 0],
            ['codestatus' => 3, 'nombre' => 'SOLUCIONADO', 'color' => '#32CD32', 'inactivo' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatusat');
    }
};
