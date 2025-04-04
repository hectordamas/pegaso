<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Updaters\{SavendController, SaclieController, SafactController};
use App\Http\Controllers\{
    HomeController, 
    AtencionClienteController,
    CalendarioController,

    EntradaEquiposController,
    ChatEntradaController,

    PresupuestosController,
    ProyectosController,
    ChatProyectoController,

    LlamadasController,
    ChatLlamadaController,

    VisitasController,
    ChatVisitaController,

    CxCController,

    EntregasController,
    ChatEntregaController,

    ComisionesController,

    WalletController,

    UsersController,
    LicenciasAActivarController
};

Route::get('/', function () {
    return redirect('/home');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    //Modulo de Atencion al Cliente
    Route::controller(AtencionClienteController::class)->group(function() {
        Route::get('atencionclientes',  'index')->middleware('menu.permission:13');
        Route::post('atencionclientes/store',  'store')->name('atencionclientes.store');
        Route::post('atencionclientes/update',  'update')->name('atencionclientes.update');
    });


    //Calendario de Eventos
    Route::controller(CalendarioController::class)->group(function(){
        Route::get('calendario',  'index')->middleware('menu.permission:15');
        Route::post('eventos/update/{id}', 'update');
        Route::post('eventos/delete/{id}', 'destroy');
        Route::post('eventos/store', 'store')->name('eventos.store');
        Route::get('eventos/reminder/test', 'reminderTest');
    });
    

    //Entrada de Equipos
    Route::controller(EntradaEquiposController::class)->group(function(){
        Route::get('entradaequipos',  'index')->middleware('menu.permission:2');
        Route::post('entradaequipos/update', 'update')->name('entradaequipos.update');
        Route::post('entradaequipos/store', 'store')->name('entradaequipos.store');
        Route::get('entradaequipos/print/{id}', 'print');
    });
    //Chat para entrada de Equipos
    Route::controller(ChatEntradaController::class)->group(function() {
        Route::get('chatentrada/cargar/{codentrada}',  'cargarChats');
        Route::post('chatentrada/send', 'sendMessage')->name('chat.send');
    });

    //Presupuestos
    Route::controller(PresupuestosController::class)->group(function() {
        Route::get('presupuestos',  'index')->name('presupuestos.index')->middleware('menu.permission:134');
        Route::get('presupuestosData', 'data')->name('presupuestos.data');
        Route::post('presupuestos/update', 'update')->name('presupuestos.update');
        Route::post('presupuestos/ver-detalles/{id}', 'verDetalles');
    });

    //Proyectos
    Route::controller(ProyectosController::class)->group(function() {
        Route::get('proyectos',  'index')->name('proyectos.index')->middleware('menu.permission:135');
        Route::get('proyectosData', 'data')->name('proyectos.data');
        Route::post('proyectos/update', 'update')->name('proyectos.update');
        Route::post('proyectos/ver-detalles/{id}', 'verDetalles');

        Route::post('/actualizar-saitemfac', 'actualizarEstado')->name('actualizar.saitemfac');
    });

    Route::controller(ChatProyectoController::class)->group(function() {
        Route::get('chatproyecto/cargar/{codproyecto}',  'cargarChats');
        Route::post('chatproyecto/send', 'sendMessage')->name('chatproyecto.send');
    });


    //Comunicaciones
    Route::controller(LlamadasController::class)->group(function(){
        Route::get('comunicaciones', 'index')->name('comunicaciones')->middleware('menu.permission:1');
        Route::post('comunicaciones/store', 'store')->name('comunicaciones.store');
        Route::post('comunicaciones/update', 'update')->name('comunicaciones.update');
    });
    Route::controller(ChatLlamadaController::class)->group(function() {
        Route::get('chatllamada/cargar/{codllamada}',  'cargarChats');
        Route::post('chatllamada/send', 'sendMessage')->name('chatllamada.send');
    });


    //Visitas
    Route::controller(VisitasController::class)->group(function(){
        Route::get('visitas', 'index')->name('visitas')->middleware('menu.permission:7');
        Route::post('visitas/store', 'store')->name('visitas.store');
        Route::get('visitas/pdf/{codvisita}', 'pdf')->name('visitas.pdf');
        Route::post('visitas/subir-archivo', 'fileUpload');
    });
    Route::controller(ChatVisitaController::class)->group(function() {
        Route::get('chatvisita/cargar/{codllamada}',  'cargarChats');
        Route::post('chatvisita/send', 'sendMessage')->name('chatvisita.send');
    });

    //Cuentas por cobrar
    Route::controller(CxCController::class)->group(function(){
        Route::get('cuentas-por-cobrar', 'index')->name('cxc')->middleware('menu.permission:3');
        Route::post('cxc/store', 'store')->name('registrarCxcWallet');
        Route::get('cxc/balance', 'balance')->name('cxc.balance');
        Route::get('cxc/getDetailsByClient', 'getDetailsByClient')->name('cxc.getDetailsByClient');

        Route::post('registrarCxcAbono', 'registrarCxcAbono')->name('registrarCxcAbono');
        Route::get('getAbonosDetails', 'getAbonosDetails')->name('cxc.getAbonosDetails');
        Route::post('cxc/updateColor', 'updateColor')->name('cxc.updateColor');

        Route::post('cxc/eliminar/{codcxc}', 'destroy');
        
        //Cxc Reportes
        Route::get('cuentas-por-cobrar/reportes', 'reportes')->middleware('menu.permission:14');
    });

    //Entregas y Suministros
    Route::controller(EntregasController::class)->group(function(){
        Route::get('entregas-y-suministros', 'index')->name('entregas')->middleware('menu.permission:136');
        Route::get('entregasData', 'data')->name('entregas.data');
        Route::post('entregas/update', 'update')->name('entregas.update');
        Route::post('entregas/ver-detalles/{id}', 'verDetalles');

        Route::post('/actualizar-entregasItems', 'actualizarEstado')->name('actualizar.entregasItems');

    });
    
    Route::controller(ChatEntregaController::class)->group(function() {
        Route::get('chatentrega/cargar/{codllamada}',  'cargarChats');
        Route::post('chatentrega/send', 'sendMessage')->name('chatentrega.send');
    });

    //Comisiones
    Route::controller(ComisionesController::class)->group(function(){
        Route::get('comisiones', 'index')->name('comisiones.index')->middleware('menu.permission:137');
        Route::get('comisiones/balance', 'balance')->name('comisiones.balance');
    });

    //Wallet
    Route::controller(WalletController::class)->group(function(){
        Route::get('wallet', 'index')->middleware('menu.permission:5');
        Route::post('wallet/store', 'store');
        Route::post('wallet/destroy', 'destroy');
        Route::get('getWalletData', 'getWalletData')->name('getWalletData');
        Route::get('getTipoMonedas', 'getTipoMonedas')->name('getTipoMonedas');
    });

    //Usuarios
    Route::controller(UsersController::class)->group(function() {
        Route::get('editar-perfil/{id}', 'editarPerfil')->name('editar-perfil');
        Route::post('update-profile/{id}', 'updateProfile')->name('update-profile');
        Route::post('update-password/{id}', 'updatePassword')->name('update-password');
        Route::post('subir-foto', 'subirFoto');

        Route::get('users', 'index')->middleware('menu.permission:9');
        Route::get('users/create', 'create');
        Route::post('users/store', 'store');
        Route::get('users/{id}/edit', 'edit');

        Route::post('setMenu', 'setMenu');
        Route::post('setUserConfig', 'setUserConfig');
        Route::post('setRole', 'setRole');
    });

    //Licencias a Activar
    Route::controller(LicenciasAActivarController::class)->group(function(){
        Route::get('licencias-a-activar', 'index')->middleware('menu.permission:138');
        Route::post('licencias/store', 'store')->middleware('menu.permission:138');
    });
});


/* --------------------------------------------- Actualizadores ------------------------------------------------------------------------ */
Route::match(['get', 'post'], 'SavendWs', [SavendController::class, 'SavendWs']);

Route::match(['get', 'post'], 'SaclieWs', [SaclieController::class, 'SaclieWs']);
Route::match(['get', 'post'], 'SaclieDsWs', [SaclieController::class, 'SaclieDsWs']);

Route::match(['get', 'post'], 'SafactWs', [SafactController::class, 'SafactWs']);
Route::match(['get', 'post'], 'SaitemfacWs', [SafactController::class, 'SaitemfacWs']);
