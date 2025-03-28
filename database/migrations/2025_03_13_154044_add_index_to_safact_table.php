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
            $table->index('serial');
            $table->index('tipofac');
            $table->index('fechae');
            $table->index('numerod');
            $table->index('texento');
            $table->index('tgravable');
            $table->index('mtotax');
            $table->index('factor');
            $table->index('mtototal');
            $table->index('codestatus');
            $table->index('codvend');
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
