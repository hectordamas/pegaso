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
        Schema::table('actualizacion_de_licencias', function (Blueprint $table) {
            $table->string('qrseniat')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actualizacion_de_licencias', function (Blueprint $table) {
            $table->string('qrseniat')->nullable();
        });
    }
};
