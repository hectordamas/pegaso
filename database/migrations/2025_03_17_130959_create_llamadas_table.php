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
        Schema::create('llamadas', function (Blueprint $table) {
            $table->id('codllamada'); // Primary Key con auto-incremento
            $table->timestamps(); // Opcional: crea created_at y updated_at
            $table->date('fecha');
            $table->unsignedBigInteger('codtipollamada');
            $table->string('contacto', 200);
            $table->string('telefono', 15);
            $table->string('email', 100);
            $table->unsignedBigInteger('codmotivo');
            $table->unsignedBigInteger('codconsultor');
            $table->text('observacion');
            $table->unsignedBigInteger('codusuario');
            $table->boolean('verificado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llamadas');
    }
};
