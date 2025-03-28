<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Safact, EstatusPre, Savend};
use Carbon\Carbon;

class PresupuestosController extends Controller
{
    public function index(){
        $estatus = EstatusPre::where('inactivo', false)
        ->get();

        $vendedores = Savend::where('activo', true)
        ->get();

        return view('presupuestos', [
            'estatus' => $estatus, 
            'vendedores' => $vendedores,
        ]);
    }

    public function data(Request $request)
    {
        $query = Safact::select('id', 'fechae', 'numerod', 'texento', 'tgravable', 'mtotax', 'factor', 'mtototal', 'codestatus', 'codclie', 'codvend')
            ->where('tipofac', 'F')
            ->byDateRange($request->input('from'), $request->input('until'))
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

        $data = [];

        foreach($query as $p){
            $dias = \Carbon\Carbon::parse($p->fechae)->diffInDays();
            // Condiciones para determinar el badge
            if ($dias >= 0 && $dias < 6) {
                $d = '<span class="badge badge-success">' . $dias . '</span>';
            } elseif ($dias >= 6 && $dias < 16) {
                $d = '<span class="badge badge-warning">' . $dias . '</span>';
            } elseif ($dias >= 16) {
                $d = '<span class="badge badge-danger">' . $dias . '</span>';
            } else {
                $d = '<span class="badge badge-secondary">' . $dias . '</span>';  // En caso de que no cumpla ninguna condición
            }

            $row = [];

            $row[] = '<p>' . $p->id . '</p>';
            $row[] = '<p>' . \Carbon\Carbon::parse($p->fechae)->format('d/m/Y h:i a') . '</p>';
            $row[] = $d;
            $row[] = '<p class="text-success fw-bold">PRE - ' . $p->numerod . '</p>';
            $row[] = '<p>' . ($p->saclie->descrip ?? 'N/A') . '</p>';
            $row[] = '<p>' . number_format($p->texento, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->tgravable, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->mtotax, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->factor, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->mtototal, 2, ',', '.') . '</p>';
            $row[] = '<p>' . ($p->factor ? number_format($p->mtototal / $p->factor, 2, ',', '.') : number_format(0, 2, ',', '.')) . '</p>';
            $row[] = '<p>' . ($p->savend->descrip ?? 'N/A') . '</p>';
            $row[] = '<span class="badge" style="background:' . ($p->estatusPre->color ?? "#e9e9e9") . ';">'. ($p->estatusPre->nombre ?? "N/A"). '</span>';
            $row[] = view('presupuestos.actions', compact('p'))->render();
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
        ]);
    }

    public function update(Request $request){
        $presupuesto = Safact::find($request->presupuestoId);
        $presupuesto->codestatus = $request->codestatus;
        if($request->razon){
            $presupuesto->razon = $request->razon;
        }
        $presupuesto->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function verDetalles($id){
        $presupuesto = Safact::find($id);
        $items = $presupuesto->saitemfac;

        $html = view('presupuestos.detalles', compact('items'))->render(); // Cargar la vista que contiene los mensajes del chat

        return response()->json([
            'items' => $html,
            'presupuesto' => $presupuesto
        ]);
    }
}