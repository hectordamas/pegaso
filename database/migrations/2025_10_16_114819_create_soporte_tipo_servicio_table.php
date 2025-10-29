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
        Schema::create('soporte_tipo_servicio', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name')->nullable();
            $table->json('origen')->nullable(); // soporta múltiples orígenes
            $table->float('porcentaje', 8, 2)->nullable();
            $table->float('comision_fija', 8, 2)->nullable(); // monto en $ si aplica
        });

        // Insertar los registros con sus orígenes y valores
        DB::table('soporte_tipo_servicio')->insert([
            [
                'name' => 'SOPORTE',
                'origen' => json_encode(['Soporte', 'Ventas']),
                'porcentaje' => 7.00,
                'comision_fija' => null,
            ],
            [
                'name' => 'IMPRESORA FISCAL',
                'origen' => json_encode(['Soporte']),
                'porcentaje' => 2.00,
                'comision_fija' => null,
            ],
            [
                'name' => 'CLIENTES NUEVOS',
                'origen' => json_encode(['Ventas']),
                'porcentaje' => 4.00,
                'comision_fija' => null,
            ],
            [
                'name' => 'PLAN BRONCE',
                'origen' => json_encode(['Plan']),
                'porcentaje' => null,
                'comision_fija' => 10.00,
            ],
            [
                'name' => 'PLAN PLATA',
                'origen' => json_encode(['Plan']),
                'porcentaje' => null,
                'comision_fija' => 20.00,
            ],
            [
                'name' => 'PLAN ORO',
                'origen' => json_encode(['Plan']),
                'porcentaje' => null,
                'comision_fija' => 50.00,
            ],
            [
                'name' => 'PLAN VIP',
                'origen' => json_encode(['Plan']),
                'porcentaje' => null,
                'comision_fija' => 100.00,
            ],
            [
                'name' => 'PLAN ESPECIAL',
                'origen' => json_encode(['Plan']),
                'porcentaje' => 30.00,
                'comision_fija' => null,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soporte_tipo_servicio');
    }
};
