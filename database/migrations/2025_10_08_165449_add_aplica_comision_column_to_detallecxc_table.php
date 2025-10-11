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
        Schema::table('detallecxc', function (Blueprint $table) {
            $table->boolean('aplica_comision')->default(0);
            $table->bigInteger('origen_id')->nullable();
            $table->timestamp('fechaDeCobro')->nullable();
            $table->string('estado')->nullable();
            $table->string('factura')->nullable();
            $table->timestamp('fechaDeFactura')->nullable(); // así es correcto
            $table->boolean('check_cobranza_admin')->default(false);
            $table->boolean('check_cobranza_manager')->default(false);
            $table->longText('comentarios')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detallecxc', function (Blueprint $table) {
            //
        });
    }
};
