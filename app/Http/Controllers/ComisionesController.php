<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Savend, saitemfac, Safact, DetalleCxC};
use Carbon\Carbon;

class ComisionesController extends Controller
{
    public function index(){
        return view('comisiones');
    }

    public function balance(Request $request)
    {
        $mes = 8;
        $anio = 2025;

        $vendedores = Savend::with(['safact' => function($query) {
            $query->whereHas('cxc.detallecxc', function($q) {
                $q->whereNotNull('fechaDePago');
                              /*->whereBetween('fechaDePago', [
                  $startOfMonth->toDateString(), 
                  $endOfMonth->toDateString()
              ]);*/
            })
            ->with('cxc.detallecxc'); // precarga los pagos
        }])
        ->whereIn('id', [24, 29])
        ->get();      

        $data = [];

        foreach ($vendedores as $v) {
            $comisionProducto = 0;
            $comisionServicio = 0;
            $total = 0;
        
            foreach ($v->safact as $safact) {
                $comisionProducto += $safact->totalComisionProducto($mes, $anio);
                $comisionServicio += $safact->totalComisionServicio($mes, $anio);
                $total += $safact->totalComision($mes, $anio);
            }
        
            $comisionGerencia = $v->getComisionGerencial($mes, $anio);
        
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


        $html = view('comisiones.table', ['data' => $data])->render();

        return response()->json(['html' => $html]);
    }


    public function vendedor($id, Request $request){
        $savend = Savend::find($id);

        return view('comisiones.vendedor', [
            'savend' => $savend
        ]);
    }

    public function set($id, Request $request){

        $vendedor = Savend::find($id);

        if ($vendedor) {
            // Actualizar los valores en la BD (opcional)
            $vendedor->es_gerente = $request->es_gerente ? 1 : 0;
            $vendedor->activo = $request->activo ? 1 : 0;
            $vendedor->comision_servicio = $request->comision_servicio;
            $vendedor->comision_producto = $request->comision_producto;
            $vendedor->comision_gerencia = $request->comision_gerencia;
            $vendedor->email = $request->email;
            $vendedor->save();
        }

        return redirect("comisiones/vendedor/{$id}")->with('message', 'Configuración guardada con éxito');
    }
    
    public function detalles_vendedor($id){
        $year = $year ?? date('Y');
        $mes  = $mes ?? date('m');
        $startOfMonth = Carbon::createFromDate($year, $mes, 1)->startOfDay();
        $endOfMonth   = Carbon::createFromDate($year, $mes, 1)->endOfMonth()->endOfDay();

        $savend = Savend::findOrFail($id);

        $safacts = Safact::with([
            'cxc.detallecxc', // aquí cargas todos los pagos
            'savend'
        ])
        ->whereHas('cxc.detallecxc', function($q) use ($startOfMonth, $endOfMonth) {
            $q->whereNotNull('fechaDePago');
              /*->whereBetween('fechaDePago', [
                  $startOfMonth->toDateString(), 
                  $endOfMonth->toDateString()
              ]);*/
        })
        ->where('codvend', $savend->codvend)
        ->get();

        return view('comisiones.detalle', [
            'savend'  => $savend,
            'safacts' => $safacts
        ]);
    }

    public function comprobantes($safactId){
        $safact = Safact::find($safactId);
        $detallecxc = $safact->cxc->detallecxc;

        $html = view('comisiones.partials.detallecxc', compact('detallecxc'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

}
