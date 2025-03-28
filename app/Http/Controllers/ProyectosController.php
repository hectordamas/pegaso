<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Safact, Savend, EstatusPre, Saclie, saitemfac, SafactEstatusHistorial};
use Carbon\Carbon;

class ProyectosController extends Controller
{
    public function index(){
        $clientes = Saclie::all();
        $savend = Savend::where('activo', true)->get();

        $estatusPre = EstatusPre::where('inactivo', false)
        ->whereIn('id', [3, 4, 5, 6, 7, 8, 9, 10])
        ->get();   

		$saclie = Saclie::orderby('descrip', 'asc')->get();

        return view('proyectos', [
            'estatusPre' => $estatusPre, 
            'clientes' => $clientes,
            'savend' => $savend,
            'saclie' => $saclie
        ]);
    }

    public function data(Request $request)
    {
        $query = Safact::select('id', 'fechae', 'numerod', 'texento', 'tgravable', 'mtotax', 'factor', 'mtototal', 'codestatus', 'codclie', 'codvend')
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
        $proyectos = (clone $query)->whereIn('codestatus', [3, 7, 8, 9])->count();
        $completados = (clone $query)->where('codestatus', 4)->count();
        $rechazados = (clone $query)->where('codestatus', 5)->count();
        $descartados = (clone $query)->where('codestatus', 6)->count();
        $enproceso = (clone $query)->where('codestatus', 7)->count();
        $ejecutados = (clone $query)->where('codestatus', 8)->count();
        $pausados = (clone $query)->where('codestatus', 9)->count();
        $qa = (clone $query)->where('codestatus', 10)->count();

        $data = [];

        foreach($query as $p){
            $row = [];

            $row[] = '<p>' . $p->id . '</p>';
            $row[] = '<p style="max-width: 70px;">' . \Carbon\Carbon::parse($p->fechae)->format('d/m/Y h:i a') . '</p>';
            $row[] = '<p style="max-width: 70px;" class="text-success fw-bold">PRE - ' . $p->numerod . '</p>';
            $row[] = '<p>' . ($p->saclie->descrip ?? 'N/A') . '</p>';
            $row[] = '<p>' . number_format($p->texento, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->tgravable, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->mtotax, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->factor, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->mtototal, 2, ',', '.') . '</p>';
            $row[] = '<p>' . ($p->factor ? number_format($p->mtototal / $p->factor, 2, ',', '.') : number_format(0, 2, ',', '.')) . '</p>';
            $row[] = '<p>' . ($p->savend->descrip ?? 'N/A') . '</p>';
            $row[] = '<span class="badge" style="background:' . ($p->estatusPre->color ?? "#e9e9e9") . ';">'. ($p->estatusPre->nombre ?? "N/A"). '</span>';
            $row[] = view('proyectos.actions', compact('p'))->render();
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

    public function update(Request $request){
        // Buscar el proyecto
        $proyecto = Safact::findOrFail($request->proyectoId);

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

        if($proyecto->codestatus == 4){
            foreach($proyecto->saitemfac as $item){
                $item->valor = true;
                $item->save();
            }
        }
        return response()->json([
            'success' => true
        ]);
    }

    public function verDetalles($id){
        $proyecto = Safact::find($id);
        $items = $proyecto->saitemfac;

        $html = view('proyectos.detalles', compact('items', 'proyecto'))->render(); // Cargar la vista que contiene los mensajes del chat

        return response()->json([
            'items' => $html,
            'proyecto' => $proyecto
        ]);
    }

    public function actualizarEstado(Request $request){//Actualiza el estado de safactitem

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
