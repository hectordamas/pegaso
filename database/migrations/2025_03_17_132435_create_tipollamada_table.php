<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipollamada', function (Blueprint $table) {
            $table->id('codtipollamada'); // Primary Key con auto-incremento
            $table->timestamps(); // Opcional: crea created_at y updated_at
            $table->string('nombre', 100)->nullable();
            $table->string('color', 100);
            $table->boolean('inactivo')->default(false);
        });

        DB::table('tipollamada')->insert([
            ['codtipollamada' => 1, 'nombre' => 'ENTRANTES', 'color' => '#036B19', 'inactivo' => 0],
            ['codtipollamada' => 2, 'nombre' => 'SALIENTES', 'color' => '#5C0E06', 'inactivo' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipollamada');
    }
};
