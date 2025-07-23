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
        Schema::create('licencia_licenciasaactivar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licenciasaactivar_id')->constrained('licenciasaactivar')->onDelete('cascade');
            $table->foreignId('licencia_id')->constrained('licencias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licencia_licenciasaactivar');
    }
};
