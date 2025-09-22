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
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->float('porcentajeComisionProducto', 100, 2)->nullable();
            $table->float('porcentajeComisionServicio', 100, 2)->nullable();
            $table->float('porcentajeComisionGerencia', 100, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safact', function (Blueprint $table) {
            //
        });
    }
};
