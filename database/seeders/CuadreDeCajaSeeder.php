<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CuadreDeCajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar un registro de Cuadre de Caja
        $cuadreId = DB::table('cuadre_de_caja')->insertGetId([
            'fecha' => '2025-04-02',
            'numero_orden' => 668,
            'total_general_daniel' => 540.00,
            'total_general_ds' => 630.00,
            'observaciones' => 'Cuadre ingresado desde seeder para pruebas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertar movimientos asociados a ese cuadre
        DB::table('movimientos_cuadre')->insert([
            [
                'cuadre_id' => $cuadreId,
                'responsable' => 'Daniel',
                'tipo_pago' => 'efectivo',
                'tipo_movimiento' => 'ingreso',
                'cliente' => 'GRUPOSEIN',
                'presupuesto' => '27886',
                'descripcion' => 'ADM',
                'valor' => 270.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cuadre_id' => $cuadreId,
                'responsable' => 'Daniel',
                'tipo_pago' => 'efectivo',
                'tipo_movimiento' => 'ingreso',
                'cliente' => 'DR PRINTER C.A',
                'presupuesto' => '27557',
                'descripcion' => 'ADM',
                'valor' => 270.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cuadre_id' => $cuadreId,
                'responsable' => 'DS Sistemas',
                'tipo_pago' => 'efectivo',
                'tipo_movimiento' => 'ingreso',
                'cliente' => 'RONNY',
                'presupuesto' => null,
                'descripcion' => 'DS PEDIDOS',
                'valor' => 120.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cuadre_id' => $cuadreId,
                'responsable' => 'DS Sistemas',
                'tipo_pago' => 'efectivo',
                'tipo_movimiento' => 'ingreso',
                'cliente' => 'GRUPOSEIN',
                'presupuesto' => '27886',
                'descripcion' => 'INSTALACIÓN',
                'valor' => 280.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cuadre_id' => $cuadreId,
                'responsable' => 'DS Sistemas',
                'tipo_pago' => 'efectivo',
                'tipo_movimiento' => 'ingreso',
                'cliente' => 'DR PRINTER C.A',
                'presupuesto' => '27557',
                'descripcion' => 'INSTALACIÓN',
                'valor' => 230.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
