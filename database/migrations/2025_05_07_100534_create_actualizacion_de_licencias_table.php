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
        Schema::create('actualizacion_de_licencias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('codclie', 100);
            $table->string('status')->default('No Actualizado');
            $table->string('incidencias')->default('Sin Incidencias');
            $table->longText('observacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actualizacion_de_licencias');
    }
};
