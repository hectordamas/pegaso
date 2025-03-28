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
        Schema::create('detallecxc', function (Blueprint $table) {
            $table->id(); // Equivalente a `id` INT PRIMARY KEY AUTO_INCREMENT
            $table->timestamps();
            $table->integer('codcxc');
            $table->integer('codtipomoneda');
            $table->date('fecha');
            $table->double('monto');
            $table->string('descripcion', 100);
            $table->text('file');
            $table->integer('codusuario')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detallecxc');
    }
};
