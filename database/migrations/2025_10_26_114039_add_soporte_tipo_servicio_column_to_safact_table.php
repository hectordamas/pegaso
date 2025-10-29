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
            $table->string('soporte_status')->nullable();

            $table->string('soporte_origen')->nullable();
            $table->string('soporte_tipo_servicio')->nullable();
            $table->unsignedBigInteger('soporte_tipo_servicio_id')->nullable();

            $table->boolean('soporte_check_admin')->default(false);
            $table->boolean('soporte_check_manager')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safact', function (Blueprint $table) {
            $table->dropColumn([
                'soporte_origen',
                'soporte_tipo_servicio',
                'soporte_tipo_servicio_id',
                'soporte_check_admin',
                'soporte_check_manager',
            ]);
        });
    }
};
