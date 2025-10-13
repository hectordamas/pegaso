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
        DB::table('estatuspre')->insert([
            ['id' => 14, 'nombre' => 'SOPORTE TÉCNICO', 'color' => '#2f00ffff', 'inactivo' => 0],
        ]);
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
