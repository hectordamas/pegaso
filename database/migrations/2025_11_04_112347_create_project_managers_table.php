<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ProjectManager;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_managers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('proyecto_comision', 100, 2)->nullable();
            $table->float('servicio_comision', 100, 2)->nullable();
        });

        $projectM = new ProjectManager();
        $projectM->proyecto_comision = 3;
        $projectM->servicio_comision = 3;
        $projectM->save();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_managers');
    }
};
