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
        Schema::create('entradaequipos', function (Blueprint $table) {
            $table->id('codentrada'); // Clave primaria
            $table->timestamps();
            $table->date('fecha');
            $table->string('codclie', 15);
            $table->integer('codestatus');
            $table->date('fechaentrega')->nullable();
            $table->text('actividad');
            $table->integer('codconsultor');
            $table->text('observacion');
            $table->integer('codusuario');
            $table->boolean('verificado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradaequipos');
    }
};
