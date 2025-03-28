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
        Schema::create('calendario', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('fecha')->default(now());
            $table->string('codclie', 15)->nullable();
            $table->string('lead')->nullable();
            $table->unsignedBigInteger('codconsultor');
            $table->dateTime('entry_date')->default(now());
            $table->dateTime('departure_date')->default(now());
            $table->string('title', 350);
            $table->longText('description')->nullable();
            $table->string('eventType')->nullable();
            $table->string('interactionType')->nullable();
            $table->unsignedInteger('codestatus')->default(0);
            $table->unsignedInteger('codusuario')->default(0);
            $table->text('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendario');
    }
};
