<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Safact, EstatusPre, Savend, CxC, DetalleCxC, Bank};
use App\Mail\ProyectoAprobadoMail;
use Carbon\Carbon;
use Auth;
use Mail;

class PresupuestosController extends Controller
{
    public function index(Request $request){
        $estatus = EstatusPre::where('inactivo', false)
        ->whereIn('id', [2, 3, 5, 6, 11])
        ->get();

        $vendedores = Savend::where('activo', true)
        ->get();

        $client = $request->client;

        $banks = Bank::all();

        return view('presupuestos', [
            'estatus' => $estatus, 
            'vendedores' => $vendedores,
            'client' => $client,
            'banks' => $banks
        ]);
    }

    public function data(Request $request)
    {
        $query = Safact::select('id', 'fechae', 'descrip', 'numerod', 'texento', 'tgravable', 'mtotax', 'factor', 'mtototal', 'codestatus', 'codclie', 'codvend')
            ->where('tipofac', 'F')
            ->whereNotIn('codestatus', [3, 7, 8, 9])
            ->byDateRange($request->input('from'), $request->input('until'))
            ->bySavend($request->input('codvend'))
            ->byStatus($request->input('codestatus'))
            ->bySaclie($request->input('client'))
            ->with(['saclie', 'estatusPre', 'savend'])
            ->get();
    
        // Obtener la cantidad total de registros antes de la paginación
        $totalRecords = (clone $query)->count();
    
        // Contadores por estatus
        $pendientes = (clone $query)->where('codestatus', 1)->count();
        $aprobados = (clone $query)->where('codestatus', 2)->count();
        //$proyectos = (clone $query)->whereIn('codestatus', [3, 7, 8, 9])->count();
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
            $row[] = \Carbon\Carbon::parse($p->fechae)->format('Y-m-d H:i:s'); // Columna oculta para ordenar

            $row[] = '<p>' . $p->id . '</p>';
            $row[] = '<p>' . \Carbon\Carbon::parse($p->fechae)->format('d/m/Y h:i a') . '</p>'; // Columna visible

            $row[] = $d;
            $row[] = '<p class="text-success fw-bold">PRE - ' . $p->numerod . '</p>';
            $row[] = '<p>' . ($p->descrip ?? 'N/A') . '</p>';
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
            //'proyectos' => $proyectos,
            'completados' => $completados,
            'rechazados' => $rechazados,
            'descartados' => $descartados,
        ]);
    }

    public function update(Request $request){
        $presupuesto = Safact::find($request->presupuestoId);
        $codestatus = $request->codestatus;
        $presupuesto->codestatus = $request->codestatus;


        if($codestatus == 5 || $codestatus == 6){
            if($request->razon){
                $presupuesto->razon = $request->razon;
            }       
        }
 
        
        if($codestatus == 3 || $codestatus == 11){
            if($request->abono){
                $presupuesto->abono = str_replace(',','.', str_replace('.','', $request->abono));

                $cxc = new CxC();
                $cxc->codwallet	= 1;			
                $cxc->fecha	= date('Y-m-d');
                $cxc->fecha_emision	= $presupuesto->fechae;						
                $cxc->codmoneda	= 2;			
                $cxc->codtipomoneda	= 4;		
                $cxc->codclie = $presupuesto->saclie->codclie;			
                $cxc->cliente = $presupuesto->saclie->rif . ' | '. $presupuesto->saclie->descrip;			
                $cxc->monto	= $presupuesto->tgravable / $presupuesto->factor;			
                $cxc->codusuario = Auth::user()->codusuario;	
                $cxc->observacion = ($codestatus == 3 ? 'Proyecto: ' . $presupuesto->numerod : 'Entrega: '. $presupuesto->numerod) . ' ' . $request->observacion;
                $cxc->departamento = 'Ventas';
                $cxc->safact_id = $presupuesto->id;	
                $cxc->save();

                $abono = new DetalleCxC();
                $abono->codcxc = $cxc->codcxc;	
                $abono->codtipomoneda = 4;
                $abono->fecha = date('Y-m-d');			
                $abono->monto = str_replace(',','.', str_replace('.','', $request->abono));			
                $abono->descripcion = ($codestatus == 3 ? 'Abono Proyecto: ' . $presupuesto->numerod : 'Abono Entrega: '. $presupuesto->numerod) . ' ' . $request->observacion;	
                $abono->file = $request->input('file');	
                $abono->codusuario = Auth::user()->codusuario;	
                $abono->departamento = 'Ventas';	
                $abono->fechaDePago = $request->fechaDePago;
                $abono->bank_id = $request->bank_id;
                $abono->save();
                
                $cxc = Cxc::where('codcxc','=', $cxc->codcxc)->first();
                
                if($cxc){
                    $cxc->abono = $cxc->abono + str_replace(',','.', str_replace('.','', $request->abono));
                    $cxc->save();
                }
            }
        }

        $presupuesto->save();

        if ($presupuesto->codestatus == 3) {
            $email = $presupuesto->savend->email ?? null;
        
            if ($email) {
                Mail::to($email)->send(new ProyectoAprobadoMail($presupuesto));
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function verDetalles($id){
        $presupuesto = Safact::find($id);
        $items = $presupuesto->saitemfac;

        $html = view('presupuestos.detalles', compact('items', 'presupuesto'))->render(); // Cargar la vista que contiene los mensajes del chat

        return response()->json([
            'items' => $html,
            'presupuesto' => $presupuesto
        ]);
    }
}