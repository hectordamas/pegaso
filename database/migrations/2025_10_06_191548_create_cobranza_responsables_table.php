<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CobranzaResponsable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cobranza_responsables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->float('comision', 100, 2)->nullable();
        });

        $responsable = new CobranzaResponsable();
        $responsable->name = 'Zinais Bastidas';
        $responsable->comision = 1;
        $responsable->save();

        $responsable = new CobranzaResponsable();
        $responsable->name = 'Arbelys Acosta';
        $responsable->comision = 0.5;
        $responsable->save();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobranza_responsables');
    }
};
