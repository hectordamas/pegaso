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
        Schema::create('savend', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('serial', 100); // Crea el campo `serial` con un máximo de 100 caracteres
            $table->string('codvend', 10)->unique(); // Crea el campo `codvend` de hasta 10 caracteres y lo hace nullable
            $table->string('descrip', 60)->nullable(); // Crea el campo `descrip` de hasta 60 caracteres y lo hace nullable
            $table->text('codubic')->nullable(); // Crea el campo `codubic` de tipo text y lo hace nullable
            $table->boolean('activo')->nullable(); // Crea el campo `activo` como booleano y lo hace nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savend');
    }
};
