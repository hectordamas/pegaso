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
            $table->boolean('check_admin')->default(false);
            $table->boolean('check_manager')->default(false);
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
