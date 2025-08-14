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
        Schema::create('historyitems', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->longText('informe')->nullable();
            
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('safact_id')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historyitems');
    }
};
