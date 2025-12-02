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
    ComisionesCobranzasController,
    ComisionesSoporteController,
    ComisionesProyectosController,

    WalletController,

    UsersController,
    LicenciasAActivarController,

    CuadreDeCajaController,
    ClientesController,

    ActualizacionDeLicenciasController,
    TicketsController,

    SoporteTecnicoController
};

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('getHomeData', [HomeController::class, 'getHomeData'])->name('getHomeData');

Route::group(['middleware' => ['auth'/*, 'prevent.duplicate'*/]], function () {

    //Modulo de Atencion al Cliente
    Route::controller(AtencionClienteController::class)->group(function () {
        Route::get('atencionclientes',  'index')->middleware('menu.permission:13');
        Route::post('atencionclientes/store',  'store')->name('atencionclientes.store');
        Route::post('atencionclientes/update',  'update')->name('atencionclientes.update');
    });


    //Calendario de Eventos
    Route::controller(CalendarioController::class)->group(function () {
        Route::get('calendario',  'index')->middleware('menu.permission:15');
        Route::post('eventos/update/{id}', 'update');
        Route::post('eventos/delete/{id}', 'destroy');
        Route::post('eventos/store', 'store')->name('eventos.store');
        Route::get('eventos/reminder/test', 'reminderTest');
    });


    //Entrada de Equipos
    Route::controller(EntradaEquiposController::class)->group(function () {
        Route::get('entradaequipos',  'index')->middleware('menu.permission:2');
        Route::post('entradaequipos/update', 'update')->name('entradaequipos.update');
        Route::post('entradaequipos/store', 'store')->name('entradaequipos.store');
        Route::get('entradaequipos/print/{id}', 'print');
    });
    //Chat para entrada de Equipos
    Route::controller(ChatEntradaController::class)->group(function () {
        Route::get('chatentrada/cargar/{codentrada}',  'cargarChats');
        Route::post('chatentrada/send', 'sendMessage')->name('chat.send');
    });

    //Presupuestos
    Route::controller(PresupuestosController::class)->group(function () {
        Route::get('presupuestos',  'index')->name('presupuestos.index')->middleware('menu.permission:134');
        Route::get('presupuestosData', 'data')->name('presupuestos.data');
        Route::post('presupuestos/update', 'update')->name('presupuestos.update');
        Route::post('presupuestos/ver-detalles/{id}', 'verDetalles');
    });

    //Proyectos
    Route::controller(ProyectosController::class)->group(function () {
        Route::get('proyectos',  'index')->name('proyectos.index')->middleware('menu.permission:135');
        Route::get('proyectosData', 'data')->name('proyectos.data');
        Route::post('proyectos/update', 'update')->name('proyectos.update');
        Route::post('proyectos/ver-detalles/{id}', 'verDetalles');
        Route::get('/proyectos/{id}/informe', 'getInforme');
        Route::get('/proyectos/{id}/informe/edit', 'editarInforme')->name('proyectos.informe.edit')->middleware('menu.permission:135');
        Route::put('/proyectos/{id}/informe', 'updateInforme')->middleware('menu.permission:135');
        Route::post('/actualizar-saitemfac', 'actualizarEstado')->name('actualizar.saitemfac');
    });
    Route::get('/proyectos/{id}/informe/pdf', [ProyectosController::class, 'exportarPDF'])->name('proyectos.informe.pdf');

    Route::controller(ChatProyectoController::class)->group(function () {
        Route::get('chatproyecto/cargar/{codproyecto}',  'cargarChats');
        Route::post('chatproyecto/send', 'sendMessage')->name('chatproyecto.send');
    });


    //Comunicaciones
    Route::controller(LlamadasController::class)->group(function () {
        Route::get('comunicaciones', 'index')->name('comunicaciones')->middleware('menu.permission:1');
        Route::post('comunicaciones/store', 'store')->name('comunicaciones.store');
        Route::post('comunicaciones/update', 'update')->name('comunicaciones.update');
    });
    Route::controller(ChatLlamadaController::class)->group(function () {
        Route::get('chatllamada/cargar/{codllamada}',  'cargarChats');
        Route::post('chatllamada/send', 'sendMessage')->name('chatllamada.send');
    });


    //Visitas
    Route::controller(VisitasController::class)->group(function () {
        Route::get('visitas', 'index')->name('visitas')->middleware('menu.permission:7');
        Route::post('visitas/store', 'store')->name('visitas.store');
        Route::get('visitas/pdf/{codvisita}', 'pdf')->name('visitas.pdf');
        Route::post('visitas/subir-archivo', 'fileUpload');
    });
    Route::controller(ChatVisitaController::class)->group(function () {
        Route::get('chatvisita/cargar/{codllamada}',  'cargarChats');
        Route::post('chatvisita/send', 'sendMessage')->name('chatvisita.send');
    });

    //Cuentas por cobrar
    Route::controller(CxCController::class)->group(function () {
        Route::get('cuentas-por-cobrar', 'index')->name('cxc')->middleware('menu.permission:3');
        Route::post('cxc/store', 'store')->name('registrarCxcWallet');
        Route::get('cxc/balance', 'balance')->name('cxc.balance');
        Route::get('cxc/getDetailsByClient', 'getDetailsByClient')->name('cxc.getDetailsByClient');

        Route::post('registrarCxcAbono', 'registrarCxcAbono')->name('registrarCxcAbono');
        Route::get('getAbonosDetails', 'getAbonosDetails')->name('cxc.getAbonosDetails');
        Route::post('cxc/updateColor', 'updateColor')->name('cxc.updateColor');

        Route::post('cxc/eliminar/{codcxc}', 'destroy');
        Route::post('cxc/anular/{codcxc}', 'anular');

        //Cxc Reportes
        Route::get('cuentas-por-cobrar/reportes', 'reportes')->middleware('menu.permission:14');
    });

    //Entregas y Suministros
    Route::controller(EntregasController::class)->group(function () {
        Route::get('entregas-y-suministros', 'index')->name('entregas')->middleware('menu.permission:136');
        Route::get('entregasData', 'data')->name('entregas.data');
        Route::post('entregas/update', 'update')->name('entregas.update');
        Route::post('entregas/ver-detalles/{id}', 'verDetalles');

        Route::post('/actualizar-entregasItems', 'actualizarEstado')->name('actualizar.entregasItems');
    });

    Route::controller(ChatEntregaController::class)->group(function () {
        Route::get('chatentrega/cargar/{codllamada}',  'cargarChats');
        Route::post('chatentrega/send', 'sendMessage')->name('chatentrega.send');
    });

    //Comisiones Ventas
    Route::controller(ComisionesController::class)->group(function () {
        Route::get('comisiones-ventas', 'index')->name('comisiones.index')->middleware('menu.permission:143');
        Route::get('comisiones/set/{id}', 'set')->name('comisiones.set')->middleware('menu.permission:143');
        Route::get('comisiones/vendedor/{id}', 'vendedor')->name('comisiones.vendedor')->middleware('menu.permission:143');
        Route::get('comisiones/detalles/{id}/{mes}', 'detalles_vendedor')->name('comisiones.detalles.vendedor')->middleware('menu.permission:143');

        Route::get('comisionesDetallesTable', 'comisionesDetallesTable')->name('comisionesDetallesTable');

        Route::get('comisiones/balance', 'balance')->name('comisiones.balance')->middleware('menu.permission:143');
        Route::get('comisiones/safact/{safactId}/comprobantes', 'comprobantes')->name('comisiones.comprobantes')->middleware('menu.permission:143');

        Route::post('comisiones/safact/{id}/check',  'updateCheck')->name('comisiones.updateCheck')->middleware('menu.permission:143');
        Route::post('cerrar/mes',  'cerrarMes')->name('cerrar.mes')->middleware('menu.permission:143');
    });

    //Comisiones Cobranzas Controller
    Route::controller(ComisionesCobranzasController::class)->group(function () {
        Route::get('comisiones-cobranzas', 'index')->name('comisiones.index')->middleware('menu.permission:144');
        Route::get('listSafact', 'listSafact')->name('comisiones.listSafact')->middleware('menu.permission:144');
        Route::get('getComisionesData', 'getComisionesData')->name('comisiones.getComisionesData')->middleware('menu.permission:144');
        Route::post('guardarSafacts', 'guardarSafacts')->name('comisiones.guardarSafacts')->middleware('menu.permission:144');
        Route::get('getPagoInfo/{id}', 'getPagoInfo');
        Route::post('addComisionesCobranzasInfo', 'addComisionesCobranzasInfo')->middleware('menu.permission:144');
        Route::post('createResponsable', 'createResponsable')->middleware('menu.permission:144');
        Route::post('updateResponsable', 'updateResponsable')->middleware('menu.permission:144');
        Route::post('comisiones/detallecxc/{id}/check',  'updateCheck')->name('comisiones.updateCheck')->middleware('menu.permission:144');
    });

    //Comsiones Soporte
    Route::controller(ComisionesSoporteController::class)->group(function () {
        Route::get('comisiones-soporte', 'index')->name('comisionesSoporte.index')->middleware('menu.permission:145');
        Route::get('comisionesSoporte/getComisionesData', 'getComisionesData')->name('comisionesSoporte.getComisionesData')->middleware('menu.permission:145');
        Route::get('comisionesSoporte/getSafactInfo/{id}', 'getSafactInfo');
        Route::post('comisionesSoporte/addComisionesSoporteInfo', 'addComisionesSoporteInfo')->middleware('menu.permission:145');
        Route::post('comisionesSoporte/safact/{id}/check',  'updateCheck')->name('comisionesSoporte.updateCheck')->middleware('menu.permission:145');
        Route::post('/soporte/update-tipo-servicio/{id}', 'updateTipoServicio');
        Route::post('/soporte/update-origen/{id}', 'updateOrigen');

        Route::get('/soporte/tipo-servicio/{id}',  'show');
        Route::post('/soporte/tipo-servicio/{id}',  'update');
    });


    //Comsiones Proyecto
    Route::controller(ComisionesProyectosController::class)->group(function () {
        Route::get('comisiones-proyecto', 'index')->name('comisionesProyecto.index')->middleware('menu.permission:146');
        Route::get('comisionesProyecto/getComisionesData', 'getComisionesData')->name('comisionesProyecto.getComisionesData')->middleware('menu.permission:146');
        Route::get('comisionesProyecto/getSafactInfo/{id}', 'getSafactInfo');
        Route::post('comisionesProyecto/addComisionesSoporteInfo', 'addComisionesProyectoInfo')->middleware('menu.permission:146');
        Route::post('comisionesProyecto/safact/{id}/check',  'updateCheck')->name('comisionesProyecto.updateCheck')->middleware('menu.permission:146');
        Route::post('comisiones/update-tipo-servicio/{id}', 'updateTipoServicio');
        Route::post('comisiones/update-origen/{id}', 'updateOrigen');

        Route::get('/comisiones/general/{id}/{type}', 'show');
        Route::post('/comisiones/general/{id}/{type}', 'update');
    });

    //Soporte Técnico
    Route::controller(SoporteTecnicoController::class)->group(function () {
        Route::get('soporte-tecnico', 'index')->name('soporte.index')->middleware('menu.permission:147');
        Route::get('soporteData', 'data')->name('soporte.data')->middleware('menu.permission:147');
        Route::post('soporte/update', 'update')->name('soporte.update')->middleware('menu.permission:147');
        Route::post('soporte/ver-detalles/{id}', 'verDetalles')->middleware('menu.permission:147');

        Route::post('/actualizar-soporteItems', 'actualizarEstado')->name('actualizar.soporteItems')->middleware('menu.permission:147');
    });

    //Wallet
    Route::controller(WalletController::class)->group(function () {
        Route::get('wallet', 'index')->middleware('menu.permission:5');
        Route::post('wallet/store', 'store');
        Route::post('wallet/destroy', 'destroy');
        Route::get('getWalletData', 'getWalletData')->name('getWalletData');
        Route::get('getTipoMonedas', 'getTipoMonedas')->name('getTipoMonedas');
    });

    //Usuarios
    Route::controller(UsersController::class)->group(function () {
        Route::get('editar-perfil/{id}', 'editarPerfil')->name('editar-perfil');
        Route::post('update-profile/{id}', 'updateProfile')->name('update-profile');
        Route::post('update-password/{id}', 'updatePassword')->name('update-password');
        Route::post('subir-foto', 'subirFoto');

        Route::get('users', 'index')->middleware('menu.permission:9');
        Route::get('users/create', 'create')->middleware('menu.permission:9');
        Route::post('users/store', 'store')->middleware('menu.permission:9');
        Route::get('users/{id}/edit', 'edit')->middleware('menu.permission:9');

        Route::post('setMenu', 'setMenu');
        Route::post('setUserConfig', 'setUserConfig');
        Route::post('setRole', 'setRole');
    });

    //Licencias a Activar
    Route::controller(LicenciasAActivarController::class)->group(function () {
        Route::get('licencias-a-activar', 'index')->middleware('menu.permission:138');
        Route::post('licencias/store', 'store')->middleware('menu.permission:138');
        Route::post('licencias/upload', 'upload')->middleware('menu.permission:138');
        Route::post('licencias-a-activar/update-status/{id}', 'updateStatus')->middleware('menu.permission:138');
        Route::get('/licencias/{id}/comprobantes', 'comprobantes')->middleware('menu.permission:138');
        Route::delete('/licencias-a-activar/{id}', 'destroy')->name('licencias.destroy')->middleware('menu.permission:138');
        Route::get('/licencias-a-activar/{id}/edit', 'edit')->name('licencias.edit');
        Route::put('/licencias-a-activar/{id}', 'update')->name('licencias.update');
        Route::post('/save-note/{id}', 'saveNote');
    });


    //Cuadre de caja
    Route::controller(CuadreDeCajaController::class)->group(function () {
        Route::get('cuadre-de-caja', 'index')->name('cuadre-de-caja.index')->middleware('menu.permission:139');
        Route::get('cuadre-de-caja/edit/{id}', 'edit')->middleware('menu.permission:139')->name('cuadre-de-caja.edit');
        Route::get('cuadre-de-caja/create', 'create')->middleware('menu.permission:139')->name('cuadre-de-caja.create');
        Route::get('cuadre-de-caja/show/{id}', 'show')->middleware('menu.permission:139')->name('cuadre-de-caja.show');
        Route::get('cuadre-de-caja/pdf/{id}', 'pdf')->name('cuadre-de-caja.pdf')->middleware('menu.permission:139');

        Route::post('cuadre-de-caja/store', 'store')->name('cuadre-de-caja.store')->middleware('menu.permission:139');
        Route::post('cuadre-de-caja/update/{id}', 'update')->name('cuadre-de-caja.update')->middleware('menu.permission:139');
        Route::post('cuadre-de-caja/{id}/revisado', 'actualizarRevisado')->middleware('menu.permission:139');
    });

    //Clientes
    Route::controller(ClientesController::class)->group(function () {
        Route::get('clientes', 'index')->name('clientes.index')->middleware('menu.permission:140');
        Route::get('clientes/{id}', 'show')->name('clientes.show')->middleware('menu.permission:140');
        Route::get('calendario/clientes/{codclie}', 'calendario')->middleware('menu.permission:140');
    });

    //Actualizacion de licencias
    Route::controller(ActualizacionDeLicenciasController::class)->group(function () {
        Route::get('actualizacion-licencias', 'index')->name('actualizacion-licencias.index')->middleware('menu.permission:141');
        Route::post('actualizacion-licencias/store', 'store')->name('actualizacion-licencias.store')->middleware('menu.permission:141');
        Route::get('actualizacion-licencias/{id}/edit', 'edit')->name('actualizacion-licencias.edit')->middleware('menu.permission:141');
        Route::post('actualizacion-licencias/{id}/update', 'update')->name('actualizacion-licencias.update')->middleware('menu.permission:141');
    });

    //Tickets 
    Route::controller(TicketsController::class)->group(function () {
        Route::get('tickets', 'index')->name('tickets.index')->middleware('menu.permission:142');
        Route::post('tickets/store', 'store')->name('tickets.store')->middleware('menu.permission:142');
        Route::get('tickets/{id}/edit', 'edit')->name('tickets.edit')->middleware('menu.permission:142');
        Route::post('tickets/{id}/update', 'update')->name('tickets.update')->middleware('menu.permission:142');
    });
});


/* --------------------------------------------- Actualizadores ------------------------------------------------------------------------ */
Route::match(['get', 'post'], 'SavendWs', [SavendController::class, 'SavendWs']);

Route::match(['get', 'post'], 'SaclieWs', [SaclieController::class, 'SaclieWs']);
Route::match(['get', 'post'], 'SaclieDsWs', [SaclieController::class, 'SaclieDsWs']);

Route::match(['get', 'post'], 'SafactWs', [SafactController::class, 'SafactWs']);
Route::match(['get', 'post'], 'SaitemfacWs', [SafactController::class, 'SaitemfacWs']);
