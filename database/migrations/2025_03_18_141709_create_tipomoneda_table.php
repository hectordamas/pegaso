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
        Schema::create('tipomoneda', function (Blueprint $table) {
            $table->id('codtipomoneda'); // Primary Key           
            $table->timestamps();
            $table->string('nombre', 100);
            $table->unsignedBigInteger('codmoneda'); 
            $table->boolean('inactivo')->default(0);
        });

        $sql = "INSERT INTO `tipomoneda` (`codtipomoneda`, `nombre`, `codmoneda`, `inactivo`) VALUES
                (1, 'EFECTIVO', 1, 0),
                (2, 'TRANSFERENCIA', 1, 0),
                (3, 'PAGO MOVIL', 1, 0),
                (4, 'CASH', 2, 0),
                (5, 'ZELLE', 2, 0),
                (6, 'TRANSFERENCIA', 2, 0),
                (7, 'CASH', 3, 0),
                (8, 'TRANSFERENCIA', 3, 0),
                (9, 'ELECTRONICO', 4, 0);";
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipomoneda');
    }
};
