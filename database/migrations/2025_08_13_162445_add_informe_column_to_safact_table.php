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
        Schema::table('safact', function (Blueprint $table) {
            $table->longText('informe')->nullable();
        });

        // Asignar HTML por defecto a los registros que ya existen
        DB::table('safact')->update([
            'informe' => '<h3 style="text-align:center">Escriba aquí su informe de gestión</h3> <p style="text-align:justify">Puede usar este espacio para redactar el informe correspondiente a este proyecto. Incluya los detalles, avances, fechas y observaciones necesarias.</p>'
        ]);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safact', function (Blueprint $table) {
            $table->dropColumn('informe');
        });
    }
};
