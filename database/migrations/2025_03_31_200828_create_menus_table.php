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

        $menus = [
            ['position' => 1, 'codmenu' => 13, 'nombre' => 'Atención Clientes', 'id' => 'mnuatencion', 'ruta' => 'atencionclientes', 'logo' => 'people_outline', 'logo_boostrap' => 'fas fa-headset', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['position' => 2, 'codmenu' => 15, 'nombre' => 'Calendario', 'id' => 'mnucalendario', 'ruta' => 'calendario', 'logo' => 'today', 'logo_boostrap' => 'fas fa-calendar', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],

            ['position' => 3, 'codmenu' => 3, 'nombre' => 'CXC en Divisas', 'id' => 'mnucxc', 'ruta' => 'cuentas-por-cobrar', 'logo' => 'trending_up', 'logo_boostrap' => 'fas fa-comments-dollar', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],
            ['position' => 4, 'codmenu' => 14, 'nombre' => 'CXC Reportes', 'id' => 'mnucxcreport', 'ruta' => 'cuentas-por-cobrar/reportes', 'logo' => 'pie_chart', 'logo_boostrap' => 'fas fa-poll', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],

            ['position' => 5, 'codmenu' => 2, 'nombre' => 'Entrada de Equipos', 'id' => 'mnuentradaequipos', 'ruta' => 'entradaequipos', 'logo' => 'keyboard_hide', 'logo_boostrap' => 'fas fa-laptop', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['position' => 6, 'codmenu' => 134, 'nombre' => 'Presupuestos', 'id' => 'mnupresupuestos', 'ruta' => 'presupuestos', 'logo' => 'today', 'logo_boostrap' => 'fas fa-calculator', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['position' => 7, 'codmenu' => 135, 'nombre' => 'Proyectos', 'id' => 'mnuproyectos', 'ruta' => 'proyectos', 'logo' => 'today', 'logo_boostrap' => 'fas fa-project-diagram', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['position' => 8, 'codmenu' => 136, 'nombre' => 'Entregas y Suministros', 'id' => 'mnuproyectos', 'ruta' => 'entregas-y-suministros', 'logo' => 'today', 'logo_boostrap' => 'fas fa-truck-moving', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],

            ['position' => 9, 'codmenu' => 1, 'nombre' => 'Comunicaciones', 'id' => 'mnullamadas', 'ruta' => 'comunicaciones', 'logo' => 'contact_phone', 'logo_boostrap' => 'fas fa-phone-volume', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['position' => 10, 'codmenu' => 7, 'nombre' => 'Registro de Visitas', 'id' => 'mnuregistrovisita', 'ruta' => 'visitas', 'logo' => 'assignment_ind', 'logo_boostrap' => 'fas fa-address-card', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],

            ['position' => 11, 'codmenu' => 137, 'nombre' => 'Comisiones', 'id' => 'mnuregistrovisita', 'ruta' => 'comisiones', 'logo' => 'assignment_ind', 'logo_boostrap' => 'fas fa-coins', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],
            ['position' => 12, 'codmenu' => 5, 'nombre' => 'Wallet', 'id' => 'mnuwallet', 'ruta' => 'wallet', 'logo' => 'account_balance_wallet', 'logo_boostrap' => 'fas fa-wallet', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],

            ['position' => 13, 'codmenu' => 8, 'nombre' => 'Configuración', 'id' => 'mnuconf', 'ruta' => 'formsConfig', 'logo' => 'settings', 'logo_boostrap' => 'fas fa-cogs', 'collapse' => 1, 'master' => 0, 'registra' => 0, 'vertodo' => 0, 'inactivo' => 1],
            ['position' => 14, 'codmenu' => 9, 'nombre' => 'Usuarios', 'id' => 'configuser', 'ruta' => 'users', 'logo' => 'person', 'logo_boostrap' => 'fas fa-users', 'collapse' => 0, 'master' => 8, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 0],
            ['position' => 15, 'codmenu' => 10, 'nombre' => 'Permiso Usuarios', 'id' => 'permiuser', 'ruta' => 'getInfoPermisoUsuario', 'logo' => 'vpn_key', 'logo_boostrap' => 'fas fa-user-shield', 'collapse' => 0, 'master' => 8, 'registra' => 0, 'vertodo' => 0, 'inactivo' => 1],
            ['position' => 16, 'codmenu' => 11, 'nombre' => 'Consultores', 'id' => 'confconsultores', 'ruta' => 'getInfoConsultores', 'logo' => 'account_circle', 'logo_boostrap' => 'fas fa-user-tie', 'collapse' => 0, 'master' => 8, 'registra' => 0, 'vertodo' => 0, 'inactivo' => 1],

            ['position' => 17, 'codmenu' => 6, 'nombre' => 'Licencias', 'id' => 'mnulicencia', 'ruta' => 'licencias', 'logo' => 'list_alt', 'logo_boostrap' => "fas fa-certificate", 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 0],

            ['position' => 1, 'codmenu' => 4, 'nombre' => 'CXP en Divisas', 'id' => 'mnucxp', 'ruta' => 'getInfoCxP', 'logo' => 'trending_down', 'logo_boostrap' => 'fas fa-user-friends', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 0, 'inactivo' => 1],
            ['position' => 1, 'codmenu' => 12, 'nombre' => 'Otras Licencias', 'id' => 'mnulicenciaotras', 'ruta' => 'getInfoControlLicOtras', 'logo' => 'list_alt', 'logo_boostrap' => 'far fa-window-restore', 'collapse' => 0, 'master' => 0, 'registra' => 1, 'vertodo' => 1, 'inactivo' => 1],
        ];            
        DB::table('menus')->insert($menus);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
