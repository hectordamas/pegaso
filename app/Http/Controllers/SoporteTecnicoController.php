<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{Safact, EstatusPre, Savend, CxC, DetalleCxC, Bank, Saclie, saitemfac, SafactEstatusHistorial};

class SoporteTecnicoController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Saclie::all();
        $savend = Savend::where('activo', true)->get();

        $estatusPre = EstatusPre::where('inactivo', false)
            ->whereIn('id', [14])
            ->get();

        $saclie = Saclie::orderby('descrip', 'asc')->where('activo', true)->get();
        $client = $request->client;

        return view('soporte', [
            'estatusPre' => $estatusPre,
            'clientes' => $clientes,
            'savend' => $savend,
            'saclie' => $saclie,
            'client' => $client
        ]);
    }

    public function data(Request $request)
    {
        $query = Safact::select('id', 'descrip', 'fechae', 'numerod', 'texento', 'tgravable', 'mtotax', 'factor', 'mtototal', 'codestatus', 'codclie', 'codvend')
            ->whereIn('codestatus', [14])
            ->where('tipofac', 'F')
            ->bySaclie($request->input('codclie'))
            ->byStatus($request->input('codestatus'))
            ->with(['saclie', 'estatusPre', 'savend'])
            ->get();

        // Obtener la cantidad total de registros antes de la paginación
        $totalRecords = (clone $query)->count();

        // Contadores por estatus
        $comprados = (clone $query)->where('codestatus', 11)->count();
        $enproceso = (clone $query)->where('codestatus', 12)->count();
        $entregados = (clone $query)->where('codestatus', 13)->count();

        $data = [];

        foreach ($query as $p) {
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
            $row[] = view('entregas.actions', compact('p'))->render();
            $data[] = $row;
        }

        return response()->json([
            "sEcho" => 1,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            'aaData' => $data,
            'comprados' => $comprados,
            'enproceso' => $enproceso,
            'entregados' => $entregados
        ]);
    }

    public function update(Request $request)
    {
        // Buscar el Entrega
        $entrega = Safact::find($request->entregaId);

        if ($request->codestatus == 13) {
            $noCompletado = $entrega->saitemfac()->whereIn('estado', ['Despachado', 'Enviado'])->count();

            if ($noCompletado > 0) {
                return response()->json([
                    'error' => 'Esta entrega aún tiene artículos en proceso, por lo que el estatus no puede ser actualizado'
                ]);
            }
        }

        // Verificar si el estado realmente cambió
        if ($entrega->codestatus !== $request->codestatus) {
            $historialAnterior = SafactEstatusHistorial::where('safact_id', $entrega->id)
                ->whereNull('fecha_fin')
                ->first();

            if ($historialAnterior) {
                $historialAnterior->fecha_fin = Carbon::now();
                $historialAnterior->save();
            }

            // Guardar el nuevo estado en el historial
            $nuevoHistorial = new SafactEstatusHistorial();
            $nuevoHistorial->safact_id = $entrega->id;
            $nuevoHistorial->estatusPre_id = $request->codestatus;
            $nuevoHistorial->fecha_inicio = Carbon::now();
            $nuevoHistorial->fecha_fin = null; // Se deja abierto hasta el próximo cambio
            $nuevoHistorial->save();


            // Actualizar el estado en la tabla `safact`
            $entrega->codestatus = $request->codestatus;
            $entrega->save();
        }

        if ($request->codestatus == 13) {
            foreach ($entrega->saitemfac as $item) {
                $item->estado = 'Entregado';
                $item->save();
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function verDetalles($id)
    {
        $entrega = Safact::find($id);
        $items = $entrega->saitemfac;

        $html = view('entregas.detalles', compact('items', 'entrega'))->render(); // Cargar la vista que contiene los mensajes del chat

        return response()->json([
            'items' => $html,
            'entrega' => $entrega
        ]);
    }

    public function actualizarEstado(Request $request)
    { //Actualiza el estado de safactitem

        $request->validate([
            'id' => 'required|integer|exists:saitemfac,id',
            'status' => 'required'
        ]);

        $item = saitemfac::find($request->id);

        if ($item) {
            $item->estado = $request->status; // Actualizar el estado
            $item->save();

            return response()->json(['success' => true, $item->valor]);
        }

        return response()->json(['success' => false], 400);
    }
}
