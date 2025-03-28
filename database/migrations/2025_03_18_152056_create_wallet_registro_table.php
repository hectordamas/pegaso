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
        Schema::create('wallet_registro', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('codwallet');
            $table->date('fecha');
            $table->date('fechapag');
            $table->unsignedBigInteger('codmoneda');
            $table->unsignedBigInteger('codtipomoneda');
            $table->unsignedBigInteger('codoperacion');
            $table->unsignedBigInteger('codusuario');
            $table->text('descripcion');
            $table->double('monto');
            $table->integer('signo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_registro');
    }
};
