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
        Schema::create('vps_fields', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('codclie', 100);
            $table->string('label');            // Usuario, IP, Contraseña
            $table->string('type');             // text, password
            $table->string('group')->nullable(); // windows, sql, anydesk
            $table->text('value')->nullable();
            $table->boolean('encrypted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vps_fields');
    }
};
