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
        Schema::table('visita', function (Blueprint $table) {
            $table->dateTime('entry_date')->default(now());
            $table->dateTime('departure_date')->default(now());
            $table->longText('notas')->nullable();
            $table->string('adjunto')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visita', function (Blueprint $table) {
            $table->dropColumn(['entry_date', 'departure_date', 'notas', 'adjunto']);
        });
    }
};
