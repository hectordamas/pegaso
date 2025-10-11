<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CobranzasOrigen;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cobranzas_origens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
        });

        $origen = new CobranzasOrigen();
        $origen->name = 'HOMOLOGACIÓN';
        $origen->save();

        $origen = new CobranzasOrigen();
        $origen->name = 'LICENCIA';
        $origen->save();

        $origen = new CobranzasOrigen();
        $origen->name = 'SOPORTE';
        $origen->save();

        $origen = new CobranzasOrigen();
        $origen->name = 'VENTAS';
        $origen->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobranzas_origens');
    }
};
