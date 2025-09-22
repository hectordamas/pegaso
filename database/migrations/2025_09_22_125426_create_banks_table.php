<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Bank;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre');
            $table->string('coleccion');
        });

                // Insertar métodos de pago iniciales
        $metodos = [
            'Mercantil',
            'Banplus',
            'Venezuela',
            'BNC',
            'Banesco',
            'Efectivo',
            'Zelle',
            'Banesco Panamá',
        ];

        foreach ($metodos as $metodo) {
            Bank::create([
                'nombre' => $metodo,
                'coleccion' => 'default', // o lo que quieras usar
            ]);
        }


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
