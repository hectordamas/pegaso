<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{CxC, Safact, AtencionCliente, Calendario, EntradaEquipos, DetalleCxC, Savend};
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request){
        $type = $request->type;
        $hoy = now();

        switch ($type) {
            case 'dia':
                $desde = $hoy->copy()->startOfDay();
                $hasta = $hoy->copy()->endOfDay();
                break;
            case 'mes':
                $desde = $hoy->copy()->startOfMonth();
                $hasta = $hoy->copy()->endOfMonth();
                break;
            case 'anio':
            default:
                $desde = $hoy->copy()->startOfYear();
                $hasta = $hoy->copy()->endOfYear();
                break;
        }

        //Cuentas por Cobrar
        $cxcs = CxC::where('codwallet', 1)
        ->whereBetween('fecha', [$desde, $hasta])
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
        ->whereBetween('fecha', [$desde, $hasta])
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
        ->whereBetween('fechae', [$desde, $hasta])
        ->selectRaw('codestatus, COUNT(*) as cantidad')
        ->groupBy('codestatus')
        ->get();

        $estatusProyectos = ['PROYECTO', 'COMPLETADO', 'EN PROCESO', 'EJECUTADO', 'CONTROL DE CALIDAD'];
        $cantidadesPorProyectos = $proyectos->pluck('cantidad');

        $entregasComprado = Safact::where('codestatus', 11)->whereBetween('fechae', [$desde, $hasta])->get()->count();
        $entregasEnProceso = Safact::where('codestatus', 12)->whereBetween('fechae', [$desde, $hasta])->get()->count();
        $entregasEntregado = Safact::where('codestatus', 13)->whereBetween('fechae', [$desde, $hasta])->get()->count();

        //Atención al cliente
        $atencionClientes = AtencionCliente::whereBetween('fecha', [$desde, $hasta])
        ->selectRaw('estatusat.nombre as estatus, COUNT(atencioncliente.codestatus) as cantidad')
        ->join('estatusat', 'atencioncliente.codestatus', '=', 'estatusat.codestatus') // Unimos las tablas por codestatus
        ->groupBy('estatusat.nombre') // Agrupamos por el nombre del estatus
        ->get();

        $atencionClientesEstatus =  $atencionClientes->pluck('estatus');
        $atencionClientesCantidad =  $atencionClientes->pluck('cantidad');

        //Eventos
        $eventos =  Calendario::all()->map(function ($item) {
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
        $entradaEquipos = EntradaEquipos::whereBetween('fecha', [$desde, $hasta])
        ->selectRaw('estatus.nombre as estatus, COUNT(entradaequipos.codestatus) as cantidad')
        ->join('estatus', 'entradaequipos.codestatus', '=', 'estatus.codestatus') // Unimos las tablas por codestatus
        ->groupBy('estatus.nombre') // Agrupamos por el nombre del estatus
        ->get();

        $entradaEquiposEstatus =  $entradaEquipos->pluck('estatus');
        $entradaEquiposCantidad =  $entradaEquipos->pluck('cantidad');

        

        $ventasPorVendedor = Safact::whereBetween('fechae', [$desde, $hasta])
        ->whereIn('codestatus', [3, 4, 7, 8, 10, 11, 12, 13]) // Estados válidos para ventas
        ->whereNotNull('codvend')
        ->with('savend') // Asegura que se cargue la relación
        ->get()
        ->groupBy('codvend')
        ->map(function ($ventas) {
            $vendedor = $ventas->first()->savend->descrip ?? 'Sin nombre';
            $total = $ventas->sum(function ($venta) {
                return $venta->factor ? $venta->mtototal / $venta->factor : 0;
            });
        
            return [
                'vendedor' => $vendedor,
                'total' => round($total, 2),
                'cobros' => 0
            ];
        });

        // Extraemos datos para la vista
        $ventasVendedorLabels = $ventasPorVendedor->pluck('vendedor');
        $ventasVendedorTotales = $ventasPorVendedor->pluck('total');
        $cobrosVendedorTotales = [];
        foreach($ventasVendedorLabels as $v){
            $vendedor = Savend::where('descrip', $v)->first();
            $cobroTotal = 0;
            foreach($vendedor->safact as $safact){
                if($safact->cxc){
                    $abonos = $safact->cxc->detallecxc()
                    ->whereBetween('fecha', [$desde, $hasta])
                    ->sum('monto');
    
                    $cobroTotal += $abonos;
                }
            }

            array_push($cobrosVendedorTotales, $cobroTotal);
        }


        $solicitudesPorConsultor = AtencionCliente::whereBetween('fecha', [$desde, $hasta])
        ->whereNotNull('codconsultor')
        ->where('codestatus', 3)
        ->selectRaw('codconsultor, COUNT(*) as cantidad')
        ->groupBy('codconsultor')
        ->orderByDesc('cantidad')        
        ->get()
        ->map(function ($item) {
            return [
                'consultor' => $item->consultor->nombre ?? 'Sin nombre',
                'cantidad' => $item->cantidad,
            ];
        });
    
        $consultoresLabels = $solicitudesPorConsultor->pluck('consultor');
        $consultoresCantidades = $solicitudesPorConsultor->pluck('cantidad');
    
        return view('home', [
            'saldoPorCobrar' => $saldoPorCobrar,
            'saldosPorCliente' => $saldosPorCliente,
            'cxcColors' => $cxcColors,
            'estatusProyectos' => $estatusProyectos,
            'cantidadesPorProyectos' => $cantidadesPorProyectos,

            'entregasComprado' => $entregasComprado,
            'entregasEntregado' => $entregasEntregado,
            'entregasEnProceso' => $entregasEnProceso,

            'atencionClientes' => $atencionClientes,
            'atencionClientesEstatus' => $atencionClientesEstatus,
            'atencionClientesCantidad' => $atencionClientesCantidad,

            'eventos' => $eventos,

            'entradaEquiposEstatus' => $entradaEquiposEstatus,
            'entradaEquiposCantidad' =>  $entradaEquiposCantidad,

            'ventasVendedorLabels' => $ventasVendedorLabels,
            'ventasVendedorTotales' => $ventasVendedorTotales,
            'cobrosVendedorTotales' => $cobrosVendedorTotales,

            'consultoresLabels' => $consultoresLabels,
            'consultoresCantidades' => $consultoresCantidades,

            'type' => $type ?? 'anio'
        ]);
    }
}
