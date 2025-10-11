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
        Schema::table('menu', function (Blueprint $table) {
            
            $menus = [
                ['position' => 22, 'codmenu' => 143, 'nombre' => 'Comisiones Ventas', 'id' => 'mnucomisionesventas', 'ruta' => 'comisiones-ventas', 'logo' => 'people_outline', 'logo_boostrap' => '', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
                ['position' => 23, 'codmenu' => 144, 'nombre' => 'Comisiones Cobranzas', 'id' => 'mnucomisionescobranzas', 'ruta' => 'comisiones-cobranzas', 'logo' => 'today', 'logo_boostrap' => '', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
                ['position' => 25, 'codmenu' => 145, 'nombre' => 'Comisiones Soporte', 'id' => 'mnucomisionessoporte', 'ruta' => 'comisiones-soporte', 'logo' => 'trending_up', 'logo_boostrap' => '', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],
                ['position' => 26, 'codmenu' => 146, 'nombre' => 'Comisiones Proyecto', 'id' => 'mnucomisionesproyecto', 'ruta' => 'comisiones-proyecto', 'logo' => 'trending_up', 'logo_boostrap' => '', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],

                ['position' => 27, 'codmenu' => 147, 'nombre' => 'Soporte Técnico', 'id' => 'mnusoporte', 'ruta' => 'soporte-tecnico', 'logo' => 'trending_up', 'logo_boostrap' => 'far fa-life-ring', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],

            ];

            DB::table('menus')->insert($menus);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            //
        });
    }
};
