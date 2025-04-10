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
        Schema::create('movimientos_cuadre', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('responsable'); // Ej: Daniel, DS Sistemas
            $table->string('tipo_pago');
            $table->string('tipo_movimiento');
            $table->string('cliente')->nullable();
            $table->string('presupuesto')->nullable();
            $table->string('descripcion')->nullable();
            $table->decimal('valor', 10, 2);

            $table->foreignId('cuadre_id')->constrained('cuadre_de_caja')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_cuadre');
    }
};
