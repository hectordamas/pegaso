<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Licencia;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('licencias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('codigo')->unique(); // L0001, etc.
            $table->string('nombre');
            $table->string('duracion')->nullable(); // Ej: '1 AÑO'
        });

        Licencia::updateOrCreate(['codigo' => 'L0008'], ['nombre' => 'DESBLOQUEO LICENCIA SAINT', 'duracion' => null]);
        Licencia::updateOrCreate(['codigo' => 'L0021'], ['nombre' => 'DS BARCODE LICENCIA ANUAL', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0010'], ['nombre' => 'DSDASHBOARD - LICENCIA INTELIGENCIA NEGOC RESUMEN GERENCIA CORREO, TABLET', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0012'], ['nombre' => 'DSPEDIDOS - LIC. SOFT. PEDIDOS WEB GESTOR DE PEDIDOS WEB EN LINEA', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0025'], ['nombre' => 'LICENCIA API DS WooCommerce', 'duracion' => null]);
        Licencia::updateOrCreate(['codigo' => 'L0016'], ['nombre' => 'LICENCIA DSPRECIOS - SOFT. MULTIMONEDA SISTEMA GESTION DE PRECIOS EN BOLIVARES Y DIVISAS', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0014'], ['nombre' => 'LICENCIA KIT HERRAMIENTA FISCAL', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0023'], ['nombre' => 'LICENCIA PLUS VPS SAINT ENTERPRISE', 'duracion' => null]);
        Licencia::updateOrCreate(['codigo' => 'L0005'], ['nombre' => 'LICENCIA SAINT ENTERPRISE ADM POS/REST', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0001'], ['nombre' => 'LICENCIA SAINT ENTERPRISE ADMIN', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0002'], ['nombre' => 'LICENCIA SAINT ENTERPRISE CONTAB', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0003'], ['nombre' => 'LICENCIA SAINT ENTERPRISE NOMINA', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0004'], ['nombre' => 'LICENCIA SAINT ENTERPRISE PLUS', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0006'], ['nombre' => 'LICENCIA SAINT ENTERPRISE PLUS POS/REST', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0011'], ['nombre' => 'LICENCIA SAINT ENTERPRISE SBS ADMIN.CONT', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0018'], ['nombre' => 'LICENCIA SAINT ENTERPRISE SBS POS/REST', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0009'], ['nombre' => 'LICENCIA SAINT PRODUCCION', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0007'], ['nombre' => 'LICENCIA SAINT PROFESSIONAL MAS', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0019'], ['nombre' => 'LICENCIA SIMPLITPOS 5 ESTACIONES DE 1 A 5 ESTACIONES', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0024'], ['nombre' => 'LICENCIA SIMPLITPOS APK PARA QUIOSCO. SUNMI', 'duracion' => null]);
        Licencia::updateOrCreate(['codigo' => 'L0013'], ['nombre' => 'LICENCIA SISTEMA PARA RETENCIONES FULLDEMON', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0020'], ['nombre' => 'LICENCIA SISTEMA PARA RETENCIONES TOOLKIT + INSTALACION Y CONFIGURACION DE COMPROBANTES DE RET.', 'duracion' => '1 AÑO']);
        Licencia::updateOrCreate(['codigo' => 'L0015'], ['nombre' => 'LICENCIA SOFT RECONVERSION MONETARIA SAINT Y TOTAL APLICACIONES', 'duracion' => null]);
        Licencia::updateOrCreate(['codigo' => 'L0022'], ['nombre' => 'LICENCIA VPS ADM INTERNACIONAL SAINT', 'duracion' => null]);
        Licencia::updateOrCreate(['codigo' => 'P0001'], ['nombre' => 'PUNTOS PARA LICENCIAS', 'duracion' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licencias');
    }
};
