<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{CxC, Safact, AtencionCliente, Calendario, EntradaEquipos, DetalleCxC, Savend};
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
        $cxcs = CxC::where('codwallet', 1)
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
    
        $saldosPorCliente = CxC::selectRaw('codclie, SUM(monto) as total_monto, SUM(abono) as total_abono')
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
                    'cliente' => $cxc->saclie->descrip,
                    'saldo' => $cxc->total_monto - $cxc->total_abono,
                ];
            });
        $cxcColors = ["#3498db", "#e74c3c", "#2ecc71", "#f39c12", "#9b59b6"];
    
        $proyectos = Safact::whereIn('codestatus', [3, 4, 7, 8, 10])
            ->whereBetween('fechae', [$desde, $hasta])
            ->selectRaw('codestatus, COUNT(*) as cantidad')
            ->groupBy('codestatus')
            ->get();
        $estatusProyectos = ['PROYECTO', 'COMPLETADO', 'EN PROCESO', 'EJECUTADO', 'CONTROL DE CALIDAD'];
        $cantidadesPorProyectos = $proyectos->pluck('cantidad');
    
        $entregasComprado = Safact::where('codestatus', 11)->whereBetween('fechae', [$desde, $hasta])->count();
        $entregasEnProceso = Safact::where('codestatus', 12)->whereBetween('fechae', [$desde, $hasta])->count();
        $entregasEntregado = Safact::where('codestatus', 13)->whereBetween('fechae', [$desde, $hasta])->count();
    
        $atencionClientes = AtencionCliente::whereBetween('fecha', [$desde, $hasta])
            ->selectRaw('estatusat.nombre as estatus, COUNT(atencioncliente.codestatus) as cantidad')
            ->join('estatusat', 'atencioncliente.codestatus', '=', 'estatusat.codestatus')
            ->groupBy('estatusat.nombre')
            ->get();
        $atencionClientesEstatus =  $atencionClientes->pluck('estatus');
        $atencionClientesCantidad =  $atencionClientes->pluck('cantidad');
    
        $eventos = Calendario::all()->map(function ($item) {
            return [
                'id'    => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'consultor' => $item->consultor->nombre,
                'eventType' => $item->evenType,
                'interactionType' => $item->interactionType,
                'cliente' => $item->saclie->descrip ?? $item->lead,
                'start' => $item->entry_date,
                'entry_date' => $item->entry_date,
                'end'   => $item->departure_date,
                'color' => $item->color ?: '#404E67',
            ];
        });
    
        $entradaEquipos = EntradaEquipos::whereBetween('fecha', [$desde, $hasta])
            ->selectRaw('estatus.nombre as estatus, COUNT(entradaequipos.codestatus) as cantidad')
            ->join('estatus', 'entradaequipos.codestatus', '=', 'estatus.codestatus')
            ->groupBy('estatus.nombre')
            ->get();
        $entradaEquiposEstatus =  $entradaEquipos->pluck('estatus');
        $entradaEquiposCantidad =  $entradaEquipos->pluck('cantidad');
    
        $ventasPorVendedor = Safact::whereBetween('fechae', [$desde, $hasta])
            ->whereIn('codestatus', [3, 4, 7, 8, 10, 11, 12, 13])
            ->whereNotNull('codvend')
            ->with('savend')
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
    
        $ventasVendedorLabels = $ventasPorVendedor->pluck('vendedor');
        $ventasVendedorTotales = $ventasPorVendedor->pluck('total');
        $cobrosVendedorTotales = [];
        foreach ($ventasVendedorLabels as $v) {
            $vendedor = Savend::where('descrip', $v)->first();
            $cobroTotal = 0;
            foreach ($vendedor->safact as $safact) {
                if ($safact->cxc) {
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
    
        $userId = Auth::id();
        $menus = DB::table('menupermiso')
            ->select('menus.*')
            ->join('menus', 'menupermiso.codmenu', '=', 'menus.codmenu')
            ->where('menupermiso.codusuario', $userId)
            ->where('menus.inactivo', false)
            ->orderBy('menus.position', 'asc')
            ->get();

        return compact(
            'saldoPorCobrar',
            'saldosPorCliente',
            'cxcColors',
            'estatusProyectos',
            'cantidadesPorProyectos',
            'entregasComprado',
            'entregasEntregado',
            'entregasEnProceso',
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
            'consultoresCantidades',
            'menus'
        );
    }

    public function index(Request $request)
    {
        $type = $request->type ?? 'anio';
        $data = $this->obtenerDatosDashboard($type);

        return view('home', array_merge($data, [
            'type' => $type,
        ]));
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
