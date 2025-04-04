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
        Schema::create('licenciasaactivar', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('fechadepago')->default(now());
            $table->longText('licencias')->nullable();
            $table->longText('descripcion')->nullable();
            $table->longText('notas')->nullable();
            $table->float('monto', 100, 2); 
            $table->boolean('pagada')->default(false);
            $table->boolean('activada')->default(false);
            $table->string('codclie', 15); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenciasaactivar');
    }
};
