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
        Schema::create('wallet_det', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('codwallet');
            $table->unsignedBigInteger('codmoneda');
            $table->double('saldo');
            $table->boolean('inactivo')->default(0);
        });

        $sql = "INSERT INTO `wallet_det` (`id`, `codwallet`, `codmoneda`, `saldo`, `inactivo`) VALUES
            (1, 1, 1, 67331775.31999995, 0),
            (2, 1, 2, 1288055.3300000005, 0),
            (3, 1, 3, 650, 0),
            (4, 1, 4, 0, 1),
            (5, 2, 1, -62, 0),
            (6, 2, 2, -18593, 0),
            (7, 2, 3, 0, 0),
            (8, 2, 4, 0, 1),
            (9, 5, 1, 332, 0),
            (10, 5, 2, -8112, 0),
            (11, 5, 3, -320, 0),
            (12, 5, 4, 0, 1),
            (13, 6, 1, 85673, 0),
            (14, 6, 2, 2940, 0),
            (15, 6, 3, 0, 0),
            (16, 6, 4, 0, 1);";

        DB::unprepared($sql);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_det');
    }
};
