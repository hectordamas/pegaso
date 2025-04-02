<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{CxC, Safact, AtencionCliente, Calendario, EntradaEquipos};
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        //Cuentas por Cobrar
        $cxcs = CxC::where('codwallet', 1)
        ->where('codmoneda', 2)
        ->whereColumn('monto', '>', 'abono')
        ->orderByRaw('monto - abono ASC') // Ordenar por saldo restante
        ->withCount('detallecxc')
        ->get()
        ->map(function ($cxc) {
            $cxc->saldo = $cxc->monto - $cxc->abono; // Calcular saldo individual por registro
            return $cxc;
        });
        $saldoPorCobrar = $cxcs->sum('saldo');

        $saldosPorCliente = CxC::selectRaw('codclie, SUM(monto) as total_monto, SUM(abono) as total_abono')
        ->where('codwallet', 1)
        ->where('codmoneda', 2)
        ->whereColumn('monto', '>', 'abono')
        ->groupBy('codclie')
        ->orderByRaw('SUM(monto) - SUM(abono) DESC') // Ordenar por saldo más alto
        ->limit(5) // Solo los 5 con mayor saldo
        ->get()
        ->map(function ($cxc) {
            return [
                'cliente' => $cxc->saclie->descrip,
                'saldo' => $cxc->total_monto - $cxc->total_abono,
            ];
        });
        $cxcColors = ["#3498db", "#e74c3c", "#2ecc71", "#f39c12", "#9b59b6"];


        //Proyectos
        $proyectos = Safact::whereIn('codestatus', [3, 4, 7, 8, 10])
        ->selectRaw('codestatus, COUNT(*) as cantidad')
        ->groupBy('codestatus')
        ->get();

        $estatusProyectos = ['PROYECTO', 'COMPLETADO', 'EN PROCESO', 'EJECUTADO', 'CONTROL DE CALIDAD'];
        $cantidadesPorProyectos = $proyectos->pluck('cantidad');

        //Entregas
        $entregas = Safact::whereIn('codestatus', [11, 12, 13])
        ->selectRaw('codestatus, COUNT(*) as cantidad')
        ->with('estatusPre')
        ->groupBy('codestatus')
        ->get();

        //Atención al cliente
        $atencionClientes = AtencionCliente::selectRaw('estatusat.nombre as estatus, COUNT(atencioncliente.codestatus) as cantidad')
        ->join('estatusat', 'atencioncliente.codestatus', '=', 'estatusat.codestatus') // Unimos las tablas por codestatus
        ->groupBy('estatusat.nombre') // Agrupamos por el nombre del estatus
        ->get();

        $atencionClientesEstatus =  $atencionClientes->pluck('estatus');
        $atencionClientesCantidad =  $atencionClientes->pluck('cantidad');

        //Eventos
        $eventos =  Calendario::all()
        ->map(function ($item) {
            return [
                'id'    => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'consultor' => $item->consultor->nombre,
                'eventType' => $item->evenType,
                'interactionType' => $item->interactionType,
                'cliente' => $item->saclie->descrip ?? $item->lead,
                'start' => $item->entry_date, // Fecha de inicio del evento
                'entry_date' => $item->entry_date, // Fecha de inicio del evento
                'end'   => $item->departure_date, // Fecha de fin del evento
                'color' => $item->color ? $item->color : '#404E67', 
            ];
        });

        //Entrada Equipos
        $entradaEquipos = EntradaEquipos::selectRaw('estatus.nombre as estatus, COUNT(entradaequipos.codestatus) as cantidad')
        ->join('estatus', 'entradaequipos.codestatus', '=', 'estatus.codestatus') // Unimos las tablas por codestatus
        ->groupBy('estatus.nombre') // Agrupamos por el nombre del estatus
        ->get();

        $entradaEquiposEstatus =  $entradaEquipos->pluck('estatus');
        $entradaEquiposCantidad =  $entradaEquipos->pluck('cantidad');

        return view('home', [
            'saldoPorCobrar' => $saldoPorCobrar,
            'saldosPorCliente' => $saldosPorCliente,
            'cxcColors' => $cxcColors,
            'estatusProyectos' => $estatusProyectos,
            'cantidadesPorProyectos' => $cantidadesPorProyectos,
            'entregas' => $entregas,
            'atencionClientesEstatus' => $atencionClientesEstatus,
            'atencionClientesCantidad' => $atencionClientesCantidad,
            'eventos' => $eventos,
            'entradaEquiposEstatus' => $entradaEquiposEstatus,
            'entradaEquiposCantidad' =>  $entradaEquiposCantidad
        ]);
    }
}
