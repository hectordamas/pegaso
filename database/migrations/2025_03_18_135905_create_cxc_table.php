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
        Schema::create('cxc', function (Blueprint $table) {

            $table->id('codcxc'); // Primary Key
            $table->timestamps();

            $table->date('fecha'); 
            $table->unsignedBigInteger('codwallet'); 
            $table->unsignedBigInteger('codmoneda'); 
            $table->unsignedBigInteger('codtipomoneda'); 
            $table->string('codclie', 15); 
            $table->string('cliente', 100);
            $table->double('monto'); 
            $table->double('abono'); 
            $table->unsignedBigInteger('codusuario'); 
            $table->unsignedBigInteger('codlic')->default(0); 
            $table->unsignedBigInteger('codlicotras')->default(0); 
            $table->string('observacion', 200); 
            $table->string('color', 20)->default('#00FF00');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cxc');
    }
};
