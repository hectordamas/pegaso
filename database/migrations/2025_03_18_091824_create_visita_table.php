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
        Schema::create('visita', function (Blueprint $table) {
            $table->id('codvisita'); // Clave primaria con autoincremento
            $table->timestamps();
            $table->date('fecha');
            $table->string('codclie', 15);
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
        Schema::dropIfExists('visita');
    }
};
