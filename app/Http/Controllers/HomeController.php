<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{CxC, Safact, AtencionCliente, Calendario, EntradaEquipos, DetalleCxC, Savend, Visita, Consultor};
use Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    private function obtenerDatosDashboard($type)
    {
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
    
        // Cuentas por cobrar
        $cxcs = CxC::with(['saclie:id,codclie,descrip'])
            ->where('codwallet', 1)
            ->whereBetween('fecha', [$desde, $hasta])
            ->where('codmoneda', 2)
            ->whereColumn('monto', '>', 'abono')
            ->orderByRaw('monto - abono ASC')
            ->withCount('detallecxc')
            ->get()
            ->map(function ($cxc) {
                $cxc->saldo = $cxc->monto - $cxc->abono;
                return $cxc;
            });
    
        $saldoPorCobrar = $cxcs->sum('saldo');
    
        $saldosPorCliente = CxC::with(['saclie:id,codclie,descrip'])
            ->selectRaw('codclie, SUM(monto) as total_monto, SUM(abono) as total_abono')
            ->whereBetween('fecha', [$desde, $hasta])
            ->where('codwallet', 1)
            ->where('codmoneda', 2)
            ->whereColumn('monto', '>', 'abono')
            ->groupBy('codclie')
            ->orderByRaw('SUM(monto) - SUM(abono) DESC')
            ->limit(5)
            ->get()
            ->map(function ($cxc) {
                return [
                    'cliente' => $cxc->saclie->descrip ?? 'Sin nombre',
                    'saldo' => $cxc->total_monto - $cxc->total_abono,
                ];
            });
    
        $cxcColors = ["#3498db", "#e74c3c", "#2ecc71", "#f39c12", "#9b59b6"];
    
        // Proyectos
        $proyectos = Safact::whereIn('codestatus', [3, 4, 7, 8, 10])
            ->whereBetween('fechae', [$desde, $hasta])
            ->selectRaw('codestatus, COUNT(*) as cantidad')
            ->groupBy('codestatus')
            ->get();

        $estatusProyectos = ['PROYECTO', 'COMPLETADO', 'EN PROCESO', 'EJECUTADO', 'CONTROL DE CALIDAD'];
        $cantidadesPorProyectos = $proyectos->pluck('cantidad');
    
        // Entregas
        $entregasComprado = Safact::where('codestatus', 11)->whereBetween('fechae', [$desde, $hasta])->count();
        $entregasEnProceso = Safact::where('codestatus', 12)->whereBetween('fechae', [$desde, $hasta])->count();
        $entregasEntregado = Safact::where('codestatus', 13)->whereBetween('fechae', [$desde, $hasta])->count();
    
        // Atención Clientes
        $atencionClientes = AtencionCliente::join('estatusat', 'atencioncliente.codestatus', '=', 'estatusat.codestatus')
            ->whereBetween('fecha', [$desde, $hasta])
            ->selectRaw('estatusat.nombre as estatus, COUNT(atencioncliente.codestatus) as cantidad')
            ->groupBy('estatusat.nombre')
            ->get();
        $atencionClientesEstatus =  $atencionClientes->pluck('estatus');
        $atencionClientesCantidad =  $atencionClientes->pluck('cantidad');
        $clientesAtendidos = $atencionClientes->sum('cantidad');
    
        // Eventos
        $eventos = Calendario::with(['consultor:id,nombre', 'saclie:id,codclie,descrip'])->get()->map(function ($item) {
            return [
                'id'    => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'consultor' => $item->consultor->nombre ?? 'Sin nombre',
                'eventType' => $item->evenType,
                'interactionType' => $item->interactionType,
                'cliente' => $item->saclie->descrip ?? $item->lead,
                'start' => $item->entry_date,
                'entry_date' => $item->entry_date,
                'end'   => $item->departure_date,
                'color' => $item->color ?: '#404E67',
            ];
        });
    
        // Entrada Equipos
        $entradaEquipos = EntradaEquipos::join('estatus', 'entradaequipos.codestatus', '=', 'estatus.codestatus')
            ->whereBetween('fecha', [$desde, $hasta])
            ->selectRaw('estatus.nombre as estatus, COUNT(entradaequipos.codestatus) as cantidad')
            ->groupBy('estatus.nombre')
            ->get();
        $entradaEquiposEstatus = $entradaEquipos->pluck('estatus');
        $entradaEquiposCantidad = $entradaEquipos->pluck('cantidad');
    
        // Ventas por Vendedor
        $cobrosPorVendedor = \DB::table('detallecxc')
            ->join('cxc', 'detallecxc.codcxc', '=', 'cxc.codcxc')
            ->join('safact', 'cxc.safact_id', '=', 'safact.id')
            ->whereBetween('detallecxc.fecha', [$desde, $hasta])
            ->whereNotNull('safact.codvend')
            ->selectRaw('safact.codvend, SUM(detallecxc.monto) as total_abonos')
            ->groupBy('safact.codvend')
            ->pluck('total_abonos', 'codvend');
    
        $ventasPorVendedor = Safact::whereBetween('fechae', [$desde, $hasta])
            ->whereIn('codestatus', [3, 4, 7, 8, 10, 11, 12, 13])
            ->whereNotNull('codvend')
            ->selectRaw('codvend, SUM(mtototal/factor) as total')
            ->groupBy('codvend')
            ->with('savend:id,codvend,descrip')
            ->get()
            ->map(function ($venta) use ($cobrosPorVendedor) {
                return [
                    'vendedor' => $venta->savend->descrip ?? 'Sin nombre',
                    'total' => round($venta->total, 2),
                    'cobros' => round($cobrosPorVendedor[$venta->codvend] ?? 0, 2),
                ];
            });
    
        $ventasVendedorLabels = $ventasPorVendedor->pluck('vendedor');
        $ventasVendedorTotales = $ventasPorVendedor->pluck('total');
        $cobrosVendedorTotales = $ventasPorVendedor->pluck('cobros');
    
        // Consultores: solicitudes, eventos, visitas
        $fuentes = [
            'solicitudes' => AtencionCliente::whereBetween('fecha', [$desde, $hasta])
                ->whereNotNull('codconsultor')
                ->where('codestatus', 3),
        
            'eventos' => Calendario::whereBetween('fecha', [$desde, $hasta])
                ->whereNotNull('codconsultor'),
        
            'visitas' => Visita::whereBetween('fecha', [$desde, $hasta])
                ->whereNotNull('codconsultor'),
        ];

        // Agrupar conteos por codconsultor
        $conteos = collect($fuentes)->map(fn($query) =>
            $query->selectRaw('codconsultor, COUNT(*) as cantidad')
                  ->groupBy('codconsultor')
                  ->get()
                  ->pluck('cantidad', 'codconsultor')
        );

        // Codigos únicos y nombres
        $cods = $conteos->flatten()->keys()->unique();
        $nombres = Consultor::whereIn('codconsultor', $cods)->where('inactivo', false)->pluck('nombre', 'codconsultor');

        // Construcción final para el gráfico
        $consultoresData = [];

        foreach ($cods as $cod) {
            $sol = $conteos['solicitudes'][$cod] ?? 0;
            $eve = $conteos['eventos'][$cod] ?? 0;
            $vis = $conteos['visitas'][$cod] ?? 0;

            // Excluir si todo está en cero
            if (($sol + $eve + $vis) == 0) continue;

            $consultoresData[] = [
                'label' => $nombres[$cod] ?? 'Sin nombre',
                'solicitudes' => $sol,
                'eventos' => $eve,
                'visitas' => $vis,
                'total' => $sol + $eve + $vis
            ];
        }

        // Ordenar por total descendente
        $consultoresData = collect($consultoresData)->sortByDesc('total')->values();

        // Extraer para el gráfico
        $consultoresLabels = $consultoresData->pluck('label');
        $consultoresSolicitudes = $consultoresData->pluck('solicitudes');
        $consultoresEventos = $consultoresData->pluck('eventos');
        $consultoresVisitas = $consultoresData->pluck('visitas');
    
        return compact(
            'saldoPorCobrar',
            'saldosPorCliente',
            'cxcColors',
            'estatusProyectos',
            'cantidadesPorProyectos',
            'entregasComprado',
            'entregasEntregado',
            'entregasEnProceso',
            'clientesAtendidos',
            'atencionClientes',
            'atencionClientesEstatus',
            'atencionClientesCantidad',
            'eventos',
            'entradaEquiposEstatus',
            'entradaEquiposCantidad',
            'ventasVendedorLabels',
            'ventasVendedorTotales',
            'cobrosVendedorTotales',
            'consultoresLabels',
            'consultoresSolicitudes',
            'consultoresEventos',
            'consultoresVisitas',
        );
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $menus = DB::table('menupermiso')
            ->select('menus.*')
            ->join('menus', 'menupermiso.codmenu', '=', 'menus.codmenu')
            ->where('menupermiso.codusuario', $userId)
            ->where('menus.inactivo', false)
            ->orderBy('menus.position', 'asc')
            ->get();

        return view('home', [
            'menus' => $menus,
        ]);
    }

    public function getHomeData(Request $request)
    {
        $type = $request->type ?? 'anio';
        $data = $this->obtenerDatosDashboard($type);

        return response()->json(array_merge($data, [
            'type' => $type,
        ]));
    }
}
