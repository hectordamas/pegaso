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
        Schema::create('saclie', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('codclie')->unique();
            $table->string('descrip');
            $table->string('email');
            $table->string('telef');
            $table->string('rif');
            $table->datetime('fechaupdate');
            
            $table->index('descrip');
            $table->index('rif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saclie');
    }
};
