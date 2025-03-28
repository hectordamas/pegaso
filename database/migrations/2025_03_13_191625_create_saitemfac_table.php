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
        Schema::create('saitemfac', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('serial', 100)->nullable();
            $table->char('TipoFac', 1);
            $table->string('NumeroD', 20);
            $table->char('OTipo', 1)->nullable();
            $table->string('ONumero', 20)->nullable();
            $table->string('Status', 2)->nullable();
            $table->integer('NroLinea')->default(0);
            $table->integer('NroLineaC')->default(0);
            $table->string('CodItem', 15)->nullable();
            $table->string('CodUbic', 10)->nullable();
            $table->string('CodMeca', 10)->nullable();
            $table->string('CodVend', 10)->nullable();
            for ($i = 1; $i <= 10; $i++) {
                $table->string("Descrip$i", 40)->nullable();
            }
            $table->string('Refere', 15)->nullable();
            $table->integer('Signo')->default(0);
            $table->double('CantMayor', 15, 2)->default(0);
            $table->double('Cantidad', 15, 2)->default(0);
            $table->double('CantidadO', 15, 2)->default(0);
            $table->double('Tara', 15, 2)->default(0);
            $table->double('ExistAntU', 15, 2)->default(0);
            $table->double('ExistAnt', 15, 2)->default(0);
            $table->double('CantidadU', 15, 2)->default(0);
            $table->double('CantidadC', 15, 2)->default(0);
            $table->double('CantidadA', 15, 2)->default(0);
            $table->double('CantidadUA', 15, 2)->default(0);
            $table->double('TotalItem', 15, 2)->default(0);
            $table->double('Costo', 15, 2)->default(0);
            $table->double('Precio', 15, 2)->default(0);
            $table->double('Descto', 15, 2)->default(0);
            $table->integer('NroUnicoL')->default(0);
            $table->string('NroLote', 20)->nullable();
            $table->dateTime('FechaE')->nullable();
            $table->dateTime('FechaL')->nullable();
            $table->dateTime('FechaV')->nullable();
            $table->boolean('EsServ')->default(0);
            $table->boolean('EsUnid')->default(0);
            $table->boolean('EsFreeP')->default(0);
            $table->boolean('EsPesa')->default(0);
            $table->boolean('EsExento')->default(0);
            $table->boolean('UsaServ')->default(0);
            $table->boolean('DEsLote')->default(0);
            $table->boolean('DEsSeri')->default(0);
            $table->boolean('DEsComp')->default(0);
            $table->string('NumeroE', 20)->nullable();
            $table->string('CodSucu', 5)->nullable();
            $table->double('MtoTax', 15, 2)->default(0);
            $table->double('PriceO', 15, 2)->default(0);
            $table->double('MtoTaxO', 15, 2)->default(0);
            $table->integer('TipoPVP')->default(0);
            $table->double('Factor', 15, 2)->default(0);
            $table->integer('ONroLinea')->default(0);
            $table->integer('ONroLineaC')->default(0);
            $table->double('CantidadT', 15, 2)->default(0);
            $table->boolean('valor')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saitemfac');
    }
};
