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
        Schema::table('safact', function (Blueprint $table) {
            $table->boolean('proyecto_check_admin')->default(false);
            $table->boolean('proyecto_check_manager')->default(false);
            $table->float('proyecto_comision', 100, 2)->nullable();
            $table->float('servicio_comision', 100, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safact', function (Blueprint $table) {
            $table->dropColumn([
                'proyecto_check_admin',
                'proyecto_check_manager',
                'proyecto_comision',
                'servicio_comision'

            ]);
        });
    }
};
