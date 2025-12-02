<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Safact, Savend, EstatusPre, Saclie, saitemfac, SafactEstatusHistorial, HistoryItem};
use Carbon\Carbon;
use App\Traits\VerifyPermissions;
use Pdf;

class ProyectosController extends Controller
{
    use VerifyPermissions;

    public function index(Request $request)
    {
        $clientes = Saclie::all();
        $savend = Savend::where('activo', true)->get();

        $estatusPre = EstatusPre::where('inactivo', false)
            ->whereIn('id', [3, 4, 5, 6, 7, 8, 9, 10])
            ->get();

        $saclie = Saclie::orderby('descrip', 'asc')->where('activo', true)->get();
        $client = $request->client;
        $registra = $this->hasPermissions('registra');

        return view('proyectos', [
            'estatusPre' => $estatusPre,
            'clientes' => $clientes,
            'savend' => $savend,
            'saclie' => $saclie,
            'client' => $client,
            'registra' => $registra
        ]);
    }

    public function data(Request $request)
    {
        $registra = $request->registra;

        $query = Safact::select('id', 'fechae', 'descrip', 'numerod', 'texento', 'tgravable', 'mtotax', 'factor', 'mtototal', 'codestatus', 'codclie', 'codvend', 'aplica_comision_soporte', 'aplica_comision_proyecto')
            ->whereIn('codestatus', [3, 4, 5, 6, 7, 8, 9, 10])
            ->where('tipofac', 'F')
            ->bySaclie($request->input('codclie'))
            ->bySavend($request->input('codvend'))
            ->byStatus($request->input('codestatus'))
            ->with(['saclie', 'estatusPre', 'savend'])
            ->get();

        // Obtener la cantidad total de registros antes de la paginación
        $totalRecords = (clone $query)->count();

        // Contadores por estatus
        $pendientes = (clone $query)->where('codestatus', 1)->count();
        $aprobados = (clone $query)->where('codestatus', 2)->count();
        $proyectos = (clone $query)->whereIn('codestatus', [3])->count();
        $completados = (clone $query)->where('codestatus', 4)->count();
        $rechazados = (clone $query)->where('codestatus', 5)->count();
        $descartados = (clone $query)->where('codestatus', 6)->count();
        $enproceso = (clone $query)->where('codestatus', 7)->count();
        $ejecutados = (clone $query)->where('codestatus', 8)->count();
        $pausados = (clone $query)->where('codestatus', 9)->count();
        $qa = (clone $query)->where('codestatus', 10)->count();

        $data = [];


        foreach ($query as $p) {
            //Abonado
            $total = ($p->tgravable / $p->factor);
            $abono = $p->cxc?->abono ?? 0;
            $porcentaje = $total > 0 ? ($abono * 100) / $total : 0;

            $row = [];
            $row[] = \Carbon\Carbon::parse($p->fechae)->format('Y-m-d H:i:s'); // Columna oculta para ordenar

            $row[] = '<p>' . $p->id . '</p>';
            $row[] = '<p style="max-width: 70px;">' . \Carbon\Carbon::parse($p->fechae)->format('d/m/Y h:i a') . '</p>';
            $row[] = '<p style="max-width: 70px;" class="text-success fw-bold">PRE - ' . $p->numerod . '</p>';
            $row[] = '<p>' . ($p->descrip ?? 'N/A') . '</p>';
            $row[] = '<p>' . number_format($p->texento, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->tgravable, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->mtotax, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->factor, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->mtototal, 2, ',', '.') . '</p>';
            $row[] = '<p>' . ($p->factor ? number_format($p->mtototal / $p->factor, 2, ',', '.') : number_format(0, 2, ',', '.')) . '</p>';
            $row[] = '<p>' . ($p->savend->descrip ?? 'N/A') . '</p>';
            $row[] = '<p>' . number_format($porcentaje, 2, ',', '.') . '%</p>';
            $row[] = '<span class="badge" style="background:' . ($p->estatusPre->color ?? "#e9e9e9") . ';">' . ($p->estatusPre->nombre ?? "N/A") . '</span>';
            $row[] = view('proyectos.actions', compact('p', 'registra'))->render();
            $data[] = $row;
        }

        return response()->json([
            "sEcho" => 1,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            'aaData' => $data,
            'pendientes' => $pendientes,
            'aprobados' => $aprobados,
            'proyectos' => $proyectos,
            'completados' => $completados,
            'rechazados' => $rechazados,
            'descartados' => $descartados,
            'enproceso' => $enproceso,
            'ejecutados' => $ejecutados,
            'pausados' => $pausados,
            'qa' => $qa
        ]);
    }

    public function update(Request $request)
    {
        // Buscar el proyecto
        $proyecto = Safact::findOrFail($request->proyectoId);


        if ($request->codestatus == 4 || $request->codestatus == 8) {
            $noCompletado = $proyecto->saitemfac()->where('valor', false)->count();

            if ($noCompletado > 0) {
                return response()->json([
                    'error' => 'Este proyecto aún tiene tareas sin completar, por lo que su estatus no pueder actualizado!'
                ]);
            }
        }

        // Verificar si el estado realmente cambió
        if ($proyecto->codestatus != $request->codestatus) {
            $historialAnterior = SafactEstatusHistorial::where('safact_id', $proyecto->id)
                ->whereNull('fecha_fin')
                ->first();

            if ($historialAnterior) {
                $historialAnterior->fecha_fin = Carbon::now();
                $historialAnterior->save();
            }

            // Guardar el nuevo estado en el historial
            $nuevoHistorial = new SafactEstatusHistorial();
            $nuevoHistorial->safact_id = $proyecto->id;
            $nuevoHistorial->estatusPre_id = $request->codestatus;
            $nuevoHistorial->fecha_inicio = Carbon::now();
            $nuevoHistorial->fecha_fin = null; // Se deja abierto hasta el próximo cambio
            $nuevoHistorial->save();

            // Actualizar el estado en la tabla `safact`
            $proyecto->codestatus = $request->codestatus;
            $proyecto->save();
        }

        if ($proyecto->codestatus == 4) {
            foreach ($proyecto->saitemfac as $item) {
                $item->valor = true;
                $item->save();
            }
        }
        return response()->json([
            'success' => true
        ]);
    }

    public function editarInforme($id)
    {
        $proyecto = Safact::findOrFail($id);
        return view('proyectos.informe', compact('proyecto'));
    }

    public function getInforme($id)
    {
        $proyecto = Safact::findOrFail($id);
        return response()->json(['informe' => $proyecto->informe]);
    }

    public function exportarPDF($id)
    {
        $proyecto = Safact::with(['savend', 'estatusPre', 'historyitems'])->findOrFail($id);

        $pdf = Pdf::loadView('proyectos.pdf', compact('proyecto'));
        return $pdf->stream('informe_' . $proyecto->descrip . '_' . $proyecto->numerod . '.pdf');
    }

    public function updateInforme(Request $request, $id)
    {
        $request->validate([
            'informe' => 'required|string',
        ]);

        // Crear nuevo registro de historial
        HistoryItem::create([
            'safact_id' => $id,
            'user_id' => auth()->id(),
            'informe' => $request->informe,
        ]);

        return redirect()->back()->with('message', 'Historial guardado correctamente.');
    }

    public function verDetalles($id)
    {
        $proyecto = Safact::find($id);
        $items = $proyecto->saitemfac;

        $html = view('proyectos.detalles', compact('items', 'proyecto'))->render(); // Cargar la vista que contiene los mensajes del chat

        return response()->json([
            'items' => $html,
            'proyecto' => $proyecto
        ]);
    }

    public function actualizarEstado(Request $request)
    { //Actualiza el estado de safactitem

        $request->validate([
            'id' => 'required|integer|exists:saitemfac,id',
            'valor' => 'required|boolean'
        ]);

        $item = saitemfac::find($request->id);

        if ($item) {
            $item->valor = $request->valor; // Actualizar el estado
            $item->save();

            return response()->json(['success' => true, $item->valor]);
        }

        return response()->json(['success' => false], 400);
    }
}
