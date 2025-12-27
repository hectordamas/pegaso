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
        Schema::create('vps_payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('codclie', 100);

            $table->year('year');
            $table->tinyInteger('month');
            $table->decimal('amount', 10, 2);
            $table->longText('receipt')->nullable();
            $table->string('status')->default('pending');

            $table->unique(['month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vps_payments');
    }
};
