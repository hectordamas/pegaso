<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Savend, saitemfac, Safact, DetalleCxC};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ComisionesController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->mes ?: date('m');
        $year = date('Y');

        $startOfMonth = Carbon::createFromDate($year, $mes, 1)->startOfDay();
        $endOfMonth   = Carbon::createFromDate($year, $mes, 1)->endOfMonth()->endOfDay();

        $mesCerrado = DB::table('cierres_mensuales')->where('mes', $mes)->where('year', $year)->exists();

        $vendedores = Savend::with(['safact' => function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereHas('cxc.detallecxc', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereNotNull('fechaDePago')->whereBetween('fechaDePago', [
                    $startOfMonth->toDateString(),
                    $endOfMonth->toDateString()
                ]);
            })
                ->whereIn('codestatus', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])
                ->with('cxc.detallecxc'); // precarga los pagos
        }])
            ->where('activo', true)
            ->get();

        $data = [];

        foreach ($vendedores as $v) {
            $comisionProducto = 0;
            $comisionServicio = 0;
            $total = 0;

            foreach ($v->safact as $safact) {
                $comisionProducto += $safact->totalComisionProducto($mes, $year);
                $comisionServicio += $safact->totalComisionServicio($mes, $year);
                $total += $safact->totalComision($mes, $year);
            }

            $comisionGerencia = $v->getComisionGerencial($mes, $year);

            $data[] = (object)[
                'id' => $v->id,
                'nombre' => $v->descrip,
                'comisionProducto' => $comisionProducto,
                'comisionServicio' => $comisionServicio,
                'comisionGerencia' => $comisionGerencia,
                'totalConGerencia' => $total + $comisionGerencia,
                'comision_producto' => $v->comision_producto,
                'comision_servicio' => $v->comision_servicio,
                'comision_gerencia' => $v->comision_gerencia,
                'es_gerente' => (bool) $v->es_gerente,
            ];
        }

        $data = collect($data)->sortByDesc('totalConGerencia')->values()->all();

        return view('comisiones', [
            'data' => $data,
            'mes' => $mes,
            'mesCerrado' => $mesCerrado
        ]);
    }

    public function vendedor($id, Request $request)
    {
        $savend = Savend::find($id);

        return view('comisiones.vendedor', [
            'savend' => $savend,
        ]);
    }

    public function set($id, Request $request)
    {

        $vendedor = Savend::find($id);

        // Actualizar los valores en la BD (opcional)
        $vendedor->es_gerente = $request->es_gerente ? 1 : 0;
        $vendedor->activo = $request->activo ? 1 : 0;
        $vendedor->comision_servicio = $request->comision_servicio;
        $vendedor->comision_producto = $request->comision_producto;
        $vendedor->comision_gerencia = $request->comision_gerencia;
        $vendedor->email = $request->email;
        $vendedor->save();

        return redirect("comisiones/vendedor/{$id}")->with('message', 'Configuración guardada con éxito');
    }

    public function detalles_vendedor($id, $mes)
    {
        $year = $year ?? date('Y');
        $mes  = $mes ?? date('m');

        $savend = Savend::findOrFail($id);

        return view('comisiones.detalle', [
            'savend'  => $savend,
            'mes' => $mes,
            'id' => $id
        ]);
    }

    public function comisionesDetallesTable(Request $request)
    {
        ini_set('max_execution_time', 300);

        $year = $request->year ?: date('Y');
        $mes  = $request->mes ?: date('m');
        $id = $request->savendId;

        $startOfMonth = Carbon::createFromDate($year, $mes, 1)->startOfDay();
        $endOfMonth   = Carbon::createFromDate($year, $mes, 1)->endOfMonth()->endOfDay();

        $savend = Savend::findOrFail($id);

        $safacts = Safact::with([
            'cxc.detallecxc', // aquí cargas todos los pagos
            'savend'
        ])
            ->whereHas('cxc.detallecxc', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereNotNull('fechaDePago')->whereBetween('fechaDePago', [
                    $startOfMonth->toDateString(),
                    $endOfMonth->toDateString()
                ]);
            })
            ->whereHas('savend', function ($q) {
                $q->where('activo', true);
            })
            ->whereIn('codestatus', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])
            ->where('codvend', $savend->codvend)
            ->get();

        $query = $safacts;

        $totalRecords = $query->count();
        $data = [];

        $totalBaseImponible = 0;
        $totalPendienteProductos = 0;
        $totalPendienteServicios = 0;
        $totalAbonadoProductos = 0;
        $totalAbonadoServicios = 0;
        $totalAbonosMes = 0;
        $totalComisionProductos = 0;
        $totalComisionServicios = 0;
        $totalComision = 0;
        $totalPendiente = 0;
        $clientesNuevos = 0;
        $clientesRecurrentes = 0;
        $cobroPendiente = 0;

        foreach ($query as $safact) {

            $totalBaseImponible += $safact->getBaseImponibleRestante($mes, $year);
            $totalPendienteProductos += $safact->pendienteProductosMes($mes, $year);
            $totalPendienteServicios += $safact->pendienteServiciosMes($mes, $year);
            $totalAbonadoProductos += $safact->abonadoProductosMes($mes, $year);
            $totalAbonadoServicios += $safact->abonadoServiciosMes($mes, $year);
            $totalAbonosMes += $safact->getAbonosMesActual($mes, $year);
            $totalComisionProductos += $safact->totalComisionProducto($mes, $year);
            $totalComisionServicios += $safact->totalComisionServicio($mes, $year);
            $totalComision += $safact->totalComision($mes, $year);
            $totalPendiente += $safact->pendiente($mes, $year);
            $clientesNuevos += $safact->esClienteNuevo() ? 1 : 0;
            $clientesRecurrentes += !$safact->esClienteNuevo() ? 1 : 0;
            $cobroPendiente += $safact->pendiente($mes, $year) > 1 ? 1 : 0;

            $row = [];

            $row[] = $safact->id;
            $row[] = $safact->numerod;
            $row[] = \Carbon\Carbon::parse($safact->fechae)->format('d-m-Y');
            $row[] = optional(optional($safact->cxc)->ultimoPago)->fechaDePago
                ? \Carbon\Carbon::parse($safact->cxc->ultimoPago->fechaDePago)->format('d-m-Y')
                : '-';
            $row[] = number_format($safact->factor, 2);
            $row[] = optional(optional($safact->cxc)->ultimoPago)->bank?->nombre ?: '-';
            $row[] = $safact->descrip;
            $row[] = $safact->esClienteNuevo() ? 'Nuevo' : 'Viejo';
            $row[] = $safact->cxc->observacion;

            $row[] = number_format($safact->getBaseImponibleRestante($mes, $year), 2, '.', ',');
            $row[] = number_format($safact->pendienteProductosMes($mes, $year), 2, '.', ',');
            $row[] = number_format($safact->pendienteServiciosMes($mes, $year), 2, '.', ',');
            $row[] = number_format($safact->abonadoProductosMes($mes, $year), 2, '.', ',');
            $row[] = number_format($safact->abonadoServiciosMes($mes, $year), 2, '.', ',');
            $row[] = number_format($safact->getAbonosMesActual($mes, $year), 2, '.', ',');
            $row[] = round($safact->abonadoPorcentaje($mes, $year)) . '%';

            $row[] = number_format($safact->totalComisionProducto($mes, $year), 2, '.', ',');
            $row[] = number_format($safact->totalComisionServicio($mes, $year), 2, '.', ',');
            $row[] = number_format($safact->totalComision($mes, $year), 2, '.', ',');

            $row[] = number_format($safact->pendiente($mes, $year), 2, '.', ',');
            $row[] = round($safact->pendientePorcentaje($mes, $year)) . '%';

            $row[] = '<a href="javascript:void(0)" onclick="cargarComprobantes(' . $safact->id . ')" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filesOffCanvas">
                        <i class="fas fa-file-invoice"></i>
                      </a>';
            $row[] = '<a href="javascript:void(0)" onclick="presupuestoDetalles(' . $safact->id . ')" class="btn btn-warning">
                        <i class="fas fa-list"></i>
                      </a>';

            $row[] = '<input type="checkbox" class="check-admin" data-id="' . $safact->id . '" ' . ($safact->check_admin ? 'checked' : '') . ' ' . ($safact->check_manager ? 'disabled' : '') . '>';
            $row[] = '<input type="checkbox" class="check-manager" data-id="' . $safact->id . '" ' . ($safact->check_manager ? 'checked' : '') . ' ' . ($safact->check_manager ? 'disabled' : '') . '>';

            $data[] = $row;
        }

        $totalesComisiones = view('comisiones.partials.totales', compact(
            'totalBaseImponible',
            'totalPendienteProductos',
            'totalPendienteServicios',
            'totalAbonadoProductos',
            'totalAbonadoServicios',
            'totalAbonosMes',
            'totalComisionProductos',
            'totalComisionServicios',
            'totalComision',
            'totalPendiente',
            'clientesNuevos',
            'clientesRecurrentes',
            'cobroPendiente',
            'savend',
            'mes',
            'year'
        ))->render();

        return response()->json([
            "sEcho" => 1,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            'aaData' => $data,
            'totalesComisiones' => $totalesComisiones
        ]);
    }

    public function comprobantes($safactId)
    {
        $safact = Safact::find($safactId);
        $detallecxc = $safact->cxc->detallecxc;

        $html = view('comisiones.partials.detallecxc', compact('detallecxc'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function updateCheck(Request $request, $id)
    {
        $safact = Safact::findOrFail($id);

        if ($request->has('check_admin')) {
            $safact->check_admin = $request->check_admin;
        }
        if ($request->has('check_manager')) {
            $safact->check_manager = $request->check_manager;
        }

        if ($request->has('aplica_comision_soporte')) {
            $safact->aplica_comision_soporte = $request->aplica_comision_soporte;
        }
        if ($request->has('aplica_comision_proyecto')) {
            $safact->aplica_comision_proyecto = $request->aplica_comision_proyecto;
        }

        // Si ambos están verificados → guardar comisiones
        if ($safact->check_admin && $safact->check_manager) {

            $safact->porcentajeComisionProducto = $safact->comision_producto;
            $safact->porcentajeComisionServicio = $safact->comision_servicio;
            $safact->porcentajeComisionGerencia = $safact->comision_gerencia;
        }

        $safact->save();

        return response()->json(['success' => true]);
    }

    public function cerrarMes(Request $request)
    {
        $mesActual = $request->mes;

        // Obtenemos los vendedores activos
        $vendedores = Savend::where('activo', true)->get();

        // Simulamos cálculo de comisiones (aquí puedes meter tu lógica real)
        $comisiones = $vendedores->mapWithKeys(function ($v) {
            return [
                $v->codvend => [
                    'id' => $v->id,
                    'codvend' => $v->codvend,
                    'descrip' => $v->descrip,
                    'comision_producto' => $v->comision_producto,
                    'comision_servicio' => $v->comision_servicio,
                    'comision_gerencia' => $v->comision_gerencia,
                ]
            ];
        });

        if (!DB::table('cierres_mensuales')->where('mes', $mesActual)->exists()) {
            DB::table('cierres_mensuales')->insert([
                'mes' => $mesActual,
                'year' => date('Y'),
                'comisiones' => $comisiones,
            ]);
        }

        return redirect()->back()->with('success', 'Mes cerrado correctamente');
    }
}
