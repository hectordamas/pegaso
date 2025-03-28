<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('savend', function (Blueprint $table) {
            $table->integer('comision_producto')->nullable();
            $table->integer('comision_servicio')->nullable();
            $table->integer('comision_gerencia')->nullable();
            $table->boolean('es_gerente')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savend', function (Blueprint $table) {
            $table->dropColumn(['comision_producto', 'comision_servicio', 'comision_gerencial', 'es_gerente']);
        });
    }
};
