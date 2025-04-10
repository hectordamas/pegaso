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
        Schema::create('cuadre_de_caja', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->date('fecha');
            $table->integer('numero_orden')->nullable();
            $table->decimal('total_general_daniel', 10, 2)->default(0);
            $table->decimal('total_general_ds', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuadre_de_caja');
    }
};
