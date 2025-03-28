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
        Schema::create('menus', function (Blueprint $table) {
            $table->id('codmenu'); // Clave primaria
            $table->timestamps();
            $table->string('nombre', 100);
            $table->string('id', 100);
            $table->string('ruta', 100);
            $table->string('logo', 100);
            $table->text('logo_boostrap');
            $table->boolean('collapse')->default(0);
            $table->integer('master');
            $table->boolean('registra')->default(0);
            $table->boolean('vertodo')->default(0);
            $table->boolean('inactivo')->default(0);

            $table->bigInteger('position')->nullable();
        });

        DB::table('menus')->insert([
            ['codmenu' => 1, 'nombre' => 'Registro de Llamadas', 'id' => 'mnullamadas', 'ruta' => 'getInfoLlamadas', 'logo' => 'contact_phone', 'logo_boostrap' => 'fas fa-tty', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['codmenu' => 2, 'nombre' => 'Entrada de Equipos', 'id' => 'mnuentradaequipos', 'ruta' => 'getInfoEntradaEquipos', 'logo' => 'keyboard_hide', 'logo_boostrap' => 'fas fa-laptop', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['codmenu' => 3, 'nombre' => 'CXC en Divisas', 'id' => 'mnucxc', 'ruta' => 'getInfoCxC', 'logo' => 'trending_up', 'logo_boostrap' => 'fas fa-comments-dollar', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],
            ['codmenu' => 4, 'nombre' => 'CXP en Divisas', 'id' => 'mnucxp', 'ruta' => 'getInfoCxP', 'logo' => 'trending_down', 'logo_boostrap' => 'fas fa-user-friends', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 1],
            ['codmenu' => 5, 'nombre' => 'Wallet', 'id' => 'mnuwallet', 'ruta' => 'getInfoWallet', 'logo' => 'account_balance_wallet', 'logo_boostrap' => 'fas fa-wallet', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],
            ['codmenu' => 6, 'nombre' => 'Licencias', 'id' => 'mnulicencia', 'ruta' => 'getInfoControlLic', 'logo' => 'list_alt', 'logo_boostrap' => 'fas fa-window-restore', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 1],
            ['codmenu' => 7, 'nombre' => 'Registro de Visitas', 'id' => 'mnuregistrovisita', 'ruta' => 'getInfoRegistroVisitas', 'logo' => 'assignment_ind', 'logo_boostrap' => 'fas fa-address-card', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['codmenu' => 8, 'nombre' => 'Configuración', 'id' => 'mnuconf', 'ruta' => 'formsConfig', 'logo' => 'settings', 'logo_boostrap' => 'fas fa-cogs', 'collapse' => 1, 'master' => 0, 'registra' => 0, 'vertodo' => 0, 'inactivo' => 0],
            ['codmenu' => 9, 'nombre' => 'Usuarios', 'id' => 'configuser', 'ruta' => 'getInfoUsuarios', 'logo' => 'person', 'logo_boostrap' => 'fas fa-chalkboard-teacher', 'collapse' => 0, 'master' => 8, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],
            ['codmenu' => 134, 'nombre' => 'Presupuestos', 'id' => 'mnupresupuestos', 'ruta' => 'getInfoPresupuestos', 'logo' => 'today', 'logo_boostrap' => 'fas fa-calculator', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['codmenu' => 135, 'nombre' => 'Proyectos', 'id' => 'mnuproyectos', 'ruta' => 'getInfoProyectos', 'logo' => 'today', 'logo_boostrap' => 'fas fa-project-diagram', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
