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

        Schema::create('control_licencia_otras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('cliente', 200);
            $table->string('estatus', 50);
            $table->string('tipo', 100);
            $table->double('montovef')->default(0);
            $table->double('montousd')->default(0);
            $table->boolean('pagada')->default(0);
            $table->boolean('eliminada')->default(0);
        });

        DB::unprepared("INSERT INTO `control_licencia_otras` (`id`, `fecha`, `cliente`, `estatus`, `tipo`, `montovef`, `montousd`, `pagada`, `eliminada`) VALUES
            (1, '2022-05-16 18:16:41', 'J-41084997-5 | REPUESTOS MULTICARS MJ, C.A.', 'Activa', 'PRECIOS DS', 0, 50, 1, 0),
            (2, '2022-05-16 18:23:58', 'J-40903948-0 | SUBLIMARTEX , C.A.', 'Activa', 'PRECIOSDS', 0, 40, 1, 0),
            (3, '2022-05-16 18:26:23', 'J-29555978-0 | SECURITY SOLUTION 2008, C.A.', 'Activa', 'PRECIOSDS', 0, 40, 1, 0),
            (4, '2022-05-16 18:27:09', 'J-30510084-5 | TECNO COLOR INDUSTRIAL 2010, C.A', 'Activa', 'PRECIOSDS ', 0, 40, 0, 1),
            (5, '2022-05-16 18:27:41', 'J-31161188-6 | REFRIALZAM, C.A', 'Activa', 'PRECIOSDS', 0, 50, 0, 1),
            (6, '2022-05-16 18:29:27', 'J-40611952-0 | SUMINISTROS ESPAVEN 2006, C.A.', 'Activa', 'PRECIOSDS', 0, 40, 1, 0),
            (7, '2022-05-16 18:29:44', 'J-40578481-4 | MULTISERVICIOS SHADDAI, C.A.', 'Activa', 'PRECIOSDS', 0, 40, 0, 1),
            (8, '2022-05-16 18:30:20', 'J-31435637-2 | INVERSIONES KEIROS 1982, C.A.', 'Activa', 'PRECIOSDS', 0, 40, 1, 0),
            (9, '2022-05-16 18:31:43', 'J-40883754-4 | ALIMENTOS SERVI, C.A.', 'Activa', 'PRECIOSDS', 0, 40, 1, 0),
            (10, '2022-05-16 18:32:20', 'J-31050242-0 | COMERCIAL PRIDACA, C.A.', 'Activa', 'PRECIOSDS', 0, 40, 1, 0),
            (11, '2022-05-16 18:34:56', 'J-31464478-5 | COMERCIAL HILOTELA,C.A', 'Activa', 'PRECIOSDS', 0, 50, 1, 0),
            (12, '2022-05-16 20:02:58', 'J-30510084-5 | TECNO COLOR INDUSTRIAL 2010, C.A', 'Activa', 'PRECIO DS', 0, 50, 1, 0),
            (13, '2022-05-18 22:52:08', 'J-50082158-1 | INVERSIONES PARTY KIDS STORE 2205, C.A.', 'Activa', 'PRECIOS DS', 0, 50, 0, 1),
            (14, '2022-05-18 22:52:38', 'J-50082158-1 | INVERSIONES PARTY KIDS STORE 2205, C.A.', 'Activa', 'LICENCIA SPOOLER TOTALAPLICACIONES', 0, 80, 0, 1),
            (15, '2022-05-20 20:52:35', 'J-29805780-7 | INVERSIONES MANSION DANTES, C.A.', 'Activa', 'PRESUPUESTO 00015423 PRECIOS DS', 0, 60, 1, 0),
            (16, '2022-05-20 20:53:16', 'J-29805780-7 | INVERSIONES MANSION DANTES, C.A.', 'Activa', 'PRESUPUESTO 00015423 SPOOLER', 0, 80, 1, 0),
            (17, '2022-05-23 14:13:39', 'J-00309824-8 | DAMA’S TELAS EXCLUSIVAS, C.A', 'Activa', 'PRECIOS DS 2022', 0, 60, 1, 0),
            (18, '2022-05-25 15:26:11', 'J-50103898-8 | INVERSIONES NAF BEAUTY, C.A', 'Activa', '	PRECIOS DS - LA CANDELARIA', 0, 60, 1, 0),
            (19, '2022-05-26 18:27:47', 'J-29973426-8 | MULTISERVICIOS FJ 2005 DEL ESTE', 'Activa', 'PRECIO DS', 0, 50, 1, 0),
            (20, '2022-05-30 18:48:03', 'J-29836503-0 | SUPERIMPRESION 9080, C.A.', 'Activa', 'PRECIOS DS', 0, 50, 1, 0),
            (21, '2022-06-01 12:23:41', 'J-40674509-0 | INVERSIONES NEMICA, C.A', 'Activa', 'PRECIOS DS', 0, 50, 1, 0),
            (22, '2022-06-01 17:21:50', 'J-31740596-0 | PORCELANAS LA PASTORA 10, C.A', 'Activa', 'LIC REPLICA DS - CASA BELLA', 0, 150, 0, 1),
            (23, '2022-06-09 16:51:29', 'J-40754380-6 | SOLUCIONES ACJ 1938, C.A.', 'Activa', 'PRECIO DS', 0, 60, 0, 1),
            (24, '2022-06-17 22:45:58', 'J-40611952-0 | SUMINISTROS ESPAVEN 2006, C.A.', 'Activa', 'LIC. FULLEDITION', 0, 60, 0, 1),
            (25, '2022-06-17 22:46:27', 'J-40611952-0 | SUMINISTROS ESPAVEN 2006, C.A.', 'Activa', 'PRECIOS DS', 0, 60, 1, 0),
            (26, '2022-06-17 22:47:03', 'J-40611952-0 | SUMINISTROS ESPAVEN 2006, C.A.', 'Activa', '2 LIC. FULLEDITION', 0, 120, 1, 0),
            (27, '2022-06-22 14:48:00', 'J-29816812-9 | SOLUCIONES TECNOLOGICAS RCM, C.A.', 'Activa', '	PRECIOS DS', 0, 50, 1, 0),
            (28, '2022-06-28 21:10:25', 'J-00304977-8 | FERRETERIA HOYO DE LA PUERTA, C. A.', 'Activa', '	PRECIOS DS', 0, 60, 1, 0),
            (29, '2022-06-29 13:07:12', 'J-00089921-5 | FERRETERIA COMERCIAL ACADEMICA 2014, C.A.', 'Activa', '	PRECIOS DS', 0, 60, 1, 0),
            (30, '2022-07-05 13:46:48', 'J-41296810-6 | REFRI NORT CARIBEAN C.A', 'Activa', 'RENOVACION PRECIOS DS 05-07-2022', 0, 60, 1, 0),
            (31, '2022-07-06 01:14:02', 'J-40881363-7 | INVERSIONES V.J. 3000, CA', 'Activa', 'RENOVACION PRECIOS DS 1 AÑO', 0, 40, 1, 0),
            (32, '2022-07-07 21:36:32', 'J-40872483-9 | TAMANACO SUPPLIES, C.A', 'Activa', 'PRECIOS DS PRESUPUESTO 0015899 RENOVACION 07-07-2022', 0, 60, 1, 0);");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_licencia_otras');
    }
};
