<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Safact, SoporteTipoServicio};
use Carbon\Carbon;

class ComisionesSoporteController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->mes ?: date('m');

        return view('comisionesSoporte.index', [
            'mes' => $mes
        ]);
    }

    public function getComisionesData(Request $request)
    {
        $year = $request->year ?: date('Y');
        $mes  = $request->mes ?: date('m');
        $id = $request->savendId;

        $startOfMonth = Carbon::createFromDate($year, $mes, 1)->startOfDay();
        $endOfMonth   = Carbon::createFromDate($year, $mes, 1)->endOfMonth()->endOfDay();


        $safacts = Safact::with(['cxc.detallecxc'])
            ->whereHas('cxc.detallecxc', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereNotNull('fechaDePago')->whereBetween('fechaDePago', [
                    $startOfMonth->toDateString(),
                    $endOfMonth->toDateString()
                ]);
            })
            ->where('aplica_comision_soporte', true)
            ->whereIn('codestatus', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])
            ->get()
            ->filter(function ($s) use ($mes, $year) {
                return $s->pendienteServiciosMes($mes, $year) > 0;
            });

        $query = $safacts;

        $totalRecords = $query->count();
        $data = [];

        $tipos = SoporteTipoServicio::all();
        $tiposServicio = SoporteTipoServicio::all();

        $resumen = [];
        foreach ($tipos as $tipo) {
            $resumen[$tipo->name] = [
                'id' => $tipo->id,
                'name' => $tipo->name,
                'total_servicio' => 0,
                'total_cobrado' => 0,
                'total_comision' => 0,
            ];
        }

        foreach ($query as $safact) {
            $tipo = $safact->soporte_tipo_servicio ?? 'Desconocido';

            // Si el tipo no existe en el resumen, créalo dinámicamente
            if (!isset($resumen[$tipo])) {
                $resumen[$tipo] = [
                    'name' => $tipo,
                    'total_servicio' => 0,
                    'total_cobrado' => 0,
                    'total_comision' => 0,
                ];
            }

            $pendiente = $safact->pendienteServiciosMes($mes, $year) ?: 0;
            $abonado = $safact->abonadoServiciosMes($mes, $year) ?: 0;

            // Buscar el tipo de servicio (puede no existir)
            $tipoServicio = $tipos->firstWhere('name', $tipo);

            // Evita error si no existe
            $comisionFija = $tipoServicio->comision_fija ?? null;
            $porcentaje = $tipoServicio->porcentaje ?? null;

            // Calcular comisión de forma segura
            if (!is_null($comisionFija)) {
                $comision = $comisionFija;
            } elseif (!is_null($porcentaje)) {
                $comision = ($abonado * $porcentaje) / 100;
            } else {
                $comision = 0;
            }

            // Acumular totales
            $resumen[$tipo]['total_servicio'] += $pendiente;
            $resumen[$tipo]['total_cobrado'] += $abonado;
            $resumen[$tipo]['total_comision'] += $comision;

            $row = [];

            $row[] = $safact->id;
            $row[] = $safact->numerod;
            $row[] = \Carbon\Carbon::parse($safact->fechae)->format('d-m-Y');

            $row[] = optional(optional($safact->cxc)->ultimoPago)->fechaDePago
                ? \Carbon\Carbon::parse($safact->cxc->ultimoPago->fechaDePago)->format('d-m-Y')
                : '-';
            $row[] = $safact->descrip;

            $row[] = $safact->soporte_origen ?: '-----------';
            $row[] = $safact->soporte_tipo_servicio ?: '-----------';

            $row[] = $safact->soporte_status ?: '-----------';

            $row[] = '$' . number_format($safact->pendienteServiciosMes($mes, $year), 2, '.', ',');
            $row[] = '$' . number_format($safact->abonadoServiciosMes($mes, $year), 2, '.', ',');
            $row[] =  number_format($safact->porcentajeCobradoSoporte($mes, $year), 2, '.', ',') . '%';
            $row[] = '$' . number_format($comision, 2, '.', ',');

            $row[] = '$' . number_format($safact->montoPorCobrarSoporte($mes, $year), 2, '.', ',');
            $row[] = number_format($safact->porcentajePorCobrarSoporte($mes, $year), 2, '.', ',') . '%';

            $row[] = '<input type="checkbox" class="check-admin" data-id="' . $safact->id . '" ' . ($safact->soporte_check_admin ? 'checked' : '') . ' ' . ($safact->soporte_check_admin ? 'disabled' : '') . '>';
            $row[] = '<input type="checkbox" class="check-manager" data-id="' . $safact->id . '" ' . ($safact->soporte_check_manager ? 'checked' : '') . ' ' . ($safact->soporte_check_manager ? 'disabled' : '') . '>';
            $row[] = '<a href="javascript:void(0)" onclick="cargarComprobantes(' . $safact->id . ')" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filesOffCanvas">
                        <i class="fas fa-file-invoice"></i>
                      </a>';
            $row[] = '<a href="javascript:void(0)" onclick="presupuestoDetalles(' . $safact->id . ')" class="btn btn-warning">
                        <i class="fas fa-list"></i>
                      </a>';
            $row[] = view('comisionesSoporte.partials.buttons', compact('safact'))->render();

            $data[] = $row;
        }

        $tablaDeComisiones = view('comisionesSoporte.partials.tablaDeComisiones', [
            'soporteTipoDeServicio' => $tipos
        ])->render();

        $servicios = view('comisionesSoporte.partials.tablaServicios', compact('resumen'))->render();

        return response()->json([
            "sEcho" => 1,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            'aaData' => $data,
            'tablaDeComisiones' => $tablaDeComisiones,
            'servicios' => $servicios
        ]);
    }

    public function updateCheck(Request $request, $id)
    {
        $safact = Safact::findOrFail($id);

        if ($request->has('check_admin')) {
            $safact->soporte_check_admin = $request->check_admin;
        }
        if ($request->has('check_manager')) {
            $safact->soporte_check_manager = $request->check_manager;
        }

        $safact->save();

        return response()->json(['success' => true]);
    }

    public function getSafactInfo($id)
    {
        $safact = Safact::findOrFail($id);
        $tiposServicio = SoporteTipoServicio::all();

        $html = view('comisionesSoporte.partials.editarSafact', [
            'safact' => $safact,
            'tiposServicio' => $tiposServicio
        ])->render();

        return response()->json([
            'safact' => $safact,
            'html' => $html
        ]);
    }

    public function addComisionesSoporteInfo(Request $request)
    {
        $safact = Safact::findOrFail($request->safact_id);
        $tipo = SoporteTipoServicio::find($request->tipo_servicio);

        $safact->soporte_origen = $request->origen;
        $safact->soporte_tipo_servicio = $tipo?->name;
        $safact->soporte_tipo_servicio_id = $tipo?->id;
        $safact->soporte_status = $request->estatus; // <-- Nuevo campo

        $safact->save();

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $t = SoporteTipoServicio::findOrFail($id);

        return response()->json([
            'porcentaje' => $t->porcentaje,
            'comision_fija' => $t->comision_fija
        ]);
    }

    public function update(Request $request, $id)
    {
        $t = SoporteTipoServicio::findOrFail($id);

        if ($request->tipo === 'porcentaje') {
            $t->porcentaje = $request->monto;
            $t->comision_fija = null;
        } else {
            $t->comision_fija = $request->monto;
            $t->porcentaje = null;
        }

        $t->save();

        return response()->json(['success' => true]);
    }
}
