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
        Schema::table('vps_payments', function (Blueprint $table) {
            $table->date('fecha')->nullable()->after('month'); // nueva columna

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vps_payments', function (Blueprint $table) {
            $table->dropColumn('fecha');
        });
    }
};
