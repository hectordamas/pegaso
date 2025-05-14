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
        Schema::create('comprobante_licencia', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('licencia_id')->unsigned()->nullable();
            $table->string('ruta'); // ruta del archivo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobante_licencia');
    }
};
