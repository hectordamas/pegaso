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
        Schema::create('chatentrada', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('fechayhora');
            $table->unsignedBigInteger('codentrada');
            $table->unsignedBigInteger('codusuario');
            $table->text('mensaje');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatentrada');
    }
};
