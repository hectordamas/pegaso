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
        Schema::create('consultor', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('codconsultor')->unsigned()->unique();

            $table->string('nombre');       
            $table->boolean('inactivo')->default(false);
            $table->string('color');    

            $table->bigInteger('codusuario')->unsigned(); // Código del usuario que registra
            $table->foreign('codusuario')->references('codusuario')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultor');
    }
};
