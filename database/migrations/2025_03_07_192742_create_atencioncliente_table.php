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
        Schema::create('atencioncliente', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('fecha')->index();
            $table->string('email')->nullable();
            $table->text('solicitud'); // Descripción de la solicitud
            $table->text('actividad')->nullable(); // Actividad realizada
            $table->string('conexion')->nullable(); // Tipo de conexión (Ej: Presencial, Remota)
            $table->string('direccionconex')->nullable(); // Dirección si es presencial

            $table->unsignedBigInteger('codestatus'); // Estado de la solicitud
            $table->foreign('codestatus')->references('codestatus')->on('estatusat')->onDelete('cascade');


            $table->unsignedBigInteger('codconsultor'); // Código del consultor asignado
            $table->foreign('codconsultor')->references('codconsultor')->on('consultor')->onDelete('cascade');

            $table->unsignedBigInteger('codusuario'); // Código del usuario que registra
            $table->foreign('codusuario')->references('codusuario')->on('users')->onDelete('cascade');

            $table->string('codclie')->nullable();
           // $table->foreign('codclie')->references('codclie')->on('saclie')->onDelete('cascade');
           //Por alguna razon no deja convertir en foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atencioncliente');
    }
};
