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
        Schema::create('safact', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('serial', 100)->nullable();
            $table->string('tipofac', 1);
            $table->string('numerod', 20);
            $table->string('otipo', 1)->nullable();
            $table->string('onumero', 20)->nullable();
            $table->integer('nrounico');
            $table->string('numerof', 20)->nullable();
            $table->string('numeror', 20)->nullable();
            $table->double('factor')->default(0);
            $table->string('descrip', 60)->nullable();
            $table->string('direc1', 60)->nullable();
            $table->string('direc2', 60)->nullable();
            $table->string('telef', 30)->nullable();
            $table->double('monto')->default(0);
            $table->double('mtotax')->default(0);
            $table->double('fletes')->default(0);
            $table->double('tgravable')->default(0);
            $table->double('texento')->default(0);
            $table->double('costoprd')->default(0);
            $table->double('costosrv')->default(0);
            $table->double('reteniva')->default(0);
            $table->dateTime('fechae')->default(now());
            $table->double('mtototal')->default(0);
            $table->double('contado')->default(0);
            $table->double('credito')->default(0);
            $table->double('descto1')->default(0);
            $table->double('descto2')->default(0);

            $table->integer('signo')->default(0);
            $table->string('codubic', 10)->nullable();
            $table->integer('codestatus')->default(1);
            
            // Índices y claves foráneas (ajustar si hay relaciones con otras tablas)
            $table->string('codesta', 100);
            $table->string('codclie', 100);
            $table->string('codvend');
            $table->string('codusua');

            //$table->index('codusua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safact');
    }
};
