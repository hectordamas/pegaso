<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Safact, ProjectManager};
use Carbon\Carbon;

class ComisionesProyectosController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->mes ?: date('m');

        return view('comisionesProyecto.index', [
            'mes' => $mes
        ]);
    }

    public function getComisionesData(Request $request)
    {
        $year = $request->year ?: date('Y');
        $mes  = $request->mes ?: date('m');
        $projectM = ProjectManager::orderBy('id', 'desc')->first();
        $startOfMonth = Carbon::createFromDate($year, $mes, 1)->startOfDay();
        $endOfMonth   = Carbon::createFromDate($year, $mes, 1)->endOfMonth()->endOfDay();


        $safacts = Safact::with(['cxc.detallecxc'])
            ->whereHas('cxc.detallecxc', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereNotNull('fechaDePago')->whereBetween('fechaDePago', [
                    $startOfMonth->toDateString(),
                    $endOfMonth->toDateString()
                ]);
            })
            ->where('aplica_comision_proyecto', true)
            ->whereIn('codestatus', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14])
            ->get()
            ->filter(function ($s) use ($mes, $year) {
                return $s->pendienteServiciosMes($mes, $year) > 0;
            });

        $query = $safacts;

        $totalRecords = $query->count();
        $data = [];

        $totalComisionProyecto = 0;
        $totalComisionServicio = 0;
        $totalComision = 0;

        foreach ($query as $safact) {
            // Si el tipo no existe en el resumen, créalo dinámicamente
            $pendiente = $safact->pendienteServiciosMes($mes, $year) ?: 0;
            $abonado = $safact->abonadoServiciosMes($mes, $year) ?: 0;

            $comisionProyecto = $safact->codestatus != 14 ? $abonado * ($projectM->proyecto_comision / 100) : 0;
            $comisionServicio = $safact->codestatus == 14 ? $abonado * ($projectM->servicio_comision / 100) : 0;

            $comision = $comisionProyecto + $comisionServicio;

            $totalComision += $comision;
            $totalComisionProyecto += $comisionProyecto;
            $totalComisionServicio += $comisionServicio;

            $row = [];

            $row[] = $safact->id;
            $row[] = $safact->numerod;
            $row[] = \Carbon\Carbon::parse($safact->fechae)->format('d-m-Y');

            $row[] = optional(optional($safact->cxc)->ultimoPago)->fechaDePago ? \Carbon\Carbon::parse($safact->cxc->ultimoPago->fechaDePago)->format('d-m-Y') : '-';
            $row[] = $safact->descrip;
            $row[] = '$' . number_format($safact->base_imponible(), 2, '.', ',');

            $row[] = $safact->codestatus != 14 ? '$' . number_format($pendiente, 2, '.', ',') : '$0.00';
            $row[] = $safact->codestatus == 14 ? '$' . number_format($pendiente, 2, '.', ',') : '$0.00';

            $row[] = '$' . number_format($safact->abonadoServiciosMes($mes, $year), 2, '.', ',');
            $row[] =  number_format($safact->porcentajeCobradoSoporte($mes, $year), 2, '.', ',') . '%';

            $row[] = $safact->codestatus != 14 ? '$' . number_format($comisionProyecto, 2, '.', ',') : '$0.00';
            $row[] = $safact->codestatus == 14 ? '$' . number_format($comisionServicio, 2, '.', ',') : '$0.00';
            $row[] = '$' . number_format($comision, 2, '.', ',');

            $row[] = number_format($safact->porcentajePorCobrarSoporte($mes, $year), 2, '.', ',') . '%';
            $row[] = '$' . number_format($safact->montoPorCobrarSoporte($mes, $year), 2, '.', ',');

            $row[] = '<input type="checkbox" class="check-admin" data-id="' . $safact->id . '" ' . ($safact->soporte_check_admin ? 'checked' : '') . ' ' . ($safact->soporte_check_admin ? 'disabled' : '') . '>';
            $row[] = '<input type="checkbox" class="check-manager" data-id="' . $safact->id . '" ' . ($safact->soporte_check_manager ? 'checked' : '') . ' ' . ($safact->soporte_check_manager ? 'disabled' : '') . '>';
            $row[] = '<a href="javascript:void(0)" onclick="cargarComprobantes(' . $safact->id . ')" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filesOffCanvas">
                        <i class="fas fa-file-invoice"></i>
                      </a>';
            $row[] = '<a href="javascript:void(0)" onclick="presupuestoDetalles(' . $safact->id . ')" class="btn btn-warning">
                        <i class="fas fa-list"></i>
                      </a>';

            $data[] = $row;
        }

        $tablaDeComisiones = view('comisionesProyecto.partials.tablaDeComisiones', [
            'totalComision' => $totalComision,
            'totalComisionProyecto' => $totalComisionProyecto,
            'totalComisionServicio' => $totalComisionServicio,
            'projectM' => $projectM
        ])->render();

        return response()->json([
            'tablaDeComisiones' => $tablaDeComisiones,
            "sEcho" => 1,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            'aaData' => $data,
        ]);
    }


    public function updateCheck(Request $request, $id)
    {
        $safact = Safact::findOrFail($id);

        if ($request->has('check_admin')) {
            $safact->proyecto_check_admin = $request->check_admin;
        }
        if ($request->has('check_manager')) {
            $safact->proyecto_check_manager = $request->check_manager;
        }

        $safact->save();

        return response()->json(['success' => true]);
    }
}
