<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tipomoneda', function (Blueprint $table) {
            
        });

        $sql = "INSERT INTO `tipomoneda` (`codtipomoneda`, `nombre`, `codmoneda`, `inactivo`) VALUES
        (10, 'ANULADO', 1, 0),
        (11, 'ANULADO', 2, 0),
        (12, 'ANULADO', 3, 0);";

        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
