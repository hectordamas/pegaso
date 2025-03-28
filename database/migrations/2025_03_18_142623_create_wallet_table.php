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
        Schema::create('wallet', function (Blueprint $table) {
            $table->id('codwallet'); // Primary Key
            $table->string('nombre', 100);
            $table->boolean('cuenta')->default(0);
            $table->boolean('cxc')->default(0);
            $table->boolean('inactivo')->default(0);
        });

        // Insertando datos iniciales
        DB::table('wallet')->insert([
            ['codwallet' => 1, 'nombre' => 'DS & DS SISTEMAS 3000, C.A.', 'cuenta' => 1, 'cxc' => 1, 'inactivo' => 0],
            ['codwallet' => 2, 'nombre' => 'INVERSIONES JS & DS, C.A.', 'cuenta' => 1, 'cxc' => 0, 'inactivo' => 0],
            ['codwallet' => 3, 'nombre' => 'CXC DANIEL SOUSA', 'cuenta' => 0, 'cxc' => 0, 'inactivo' => 0],
            ['codwallet' => 4, 'nombre' => 'CXC JULIO SANCHEN', 'cuenta' => 0, 'cxc' => 0, 'inactivo' => 0],
            ['codwallet' => 5, 'nombre' => 'POTE', 'cuenta' => 1, 'cxc' => 0, 'inactivo' => 0],
            ['codwallet' => 6, 'nombre' => 'CAJA CHICA', 'cuenta' => 1, 'cxc' => 0, 'inactivo' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet');
    }
};
