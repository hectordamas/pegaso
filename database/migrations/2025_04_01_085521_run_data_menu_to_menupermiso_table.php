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
        Schema::table('menupermiso', function (Blueprint $table) {
            DB::unprepared("
                INSERT INTO `menupermiso` (`codusuario`, `codmenu`, `registra`, `vertodo`, `inactivo`) VALUES
                (40, 1, 1, 1, 0),
                (40, 2, 1, 1, 0),
                (40, 3, 1, 1, 0),
                (40, 4, 1, 1, 0),
                (40, 5, 1, 1, 0),
                (40, 6, 1, 1, 0),
                (40, 7, 1, 1, 0),
                (40, 8, 1, 1, 0),
                (40, 9, 1, 1, 0),
                (40, 10, 1, 1, 0),
                (40, 11, 1, 1, 0),
                (40, 12, 1, 1, 0),
                (40, 13, 1, 1, 0),
                (40, 14, 1, 1, 0),
                (40, 15, 1, 1, 0),
                (40, 134, 1, 1, 0),
                (40, 135, 1, 1, 0),
                (40, 136, 1, 1, 0),
                (40, 137, 1, 1, 0);
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menupermiso', function (Blueprint $table) {
            //
        });
    }
};
