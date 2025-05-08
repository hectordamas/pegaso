<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Savend, saitemfac};

class ComisionesController extends Controller
{
    public function index(){
        return view('comisiones');
    }

    public function balance(Request $request)
    {
        $vendedores = Savend::with(['safact.cxc', 'safact.saitemfac'])->where('activo', true)->get();
        $comisiones = [];
        $mes = $request->mes;
        $totalVentasDepartamento = 0;
        
        foreach ($vendedores as $vendedor) {
            $comisionProducto = 0;
            $comisionServicio = 0;

            $totalAbonado = 0;

            foreach ($vendedor->safact as $safact) {
                $items = $safact->saitemfac;
                $factor = $safact->factor ?: 1;
    
                $productoTotal = $items->where('EsServ', false)->sum(fn($item) => $item->TotalItem / $factor);
                $servicioTotal = $items->where('EsServ', true)->sum(fn($item) => $item->TotalItem / $factor);
    
                $cxc = $safact->cxc;
                $abonosMes = 0;
                
                if ($cxc) {
                    $abonosMes = $cxc->detallecxc()
                        ->whereMonth('fecha', $mes)
                        ->whereYear('fecha', date('Y'))
                        ->sum('monto');
                }
    
                // Distribución de abonos: lógica pura y clara
                $montoParaProducto = min($abonosMes, $productoTotal);
                $montoParaServicio = max(0, $abonosMes - $productoTotal);
    
                // Aplicar comisiones sobre montos efectivamente abonados
                $comisionProducto += $montoParaProducto * ($vendedor->comision_producto / 100);
                $comisionServicio += $montoParaServicio * ($vendedor->comision_servicio / 100);
                $totalAbonado += $montoParaProducto + $montoParaServicio;

            }
    
            $totalVentasDepartamento += $totalAbonado;
    
            $comisiones[] = [
                'id' => $vendedor->id,
                'vendedor' => $vendedor->descrip,
                'es_gerente' => $vendedor->es_gerente,
                'comision_producto' => $comisionProducto,
                'comision_servicio' => $comisionServicio,
                'comision_gerencial' => 0,
                'percent_comision_gerencial' => $vendedor->comision_gerencia,
            ];
        }
    
        foreach ($comisiones as &$comision) {
            if ($comision['es_gerente']) {
                $comision['comision_gerencial'] = $totalVentasDepartamento * ($comision['percent_comision_gerencial'] / 100);
            }
        }
    
        $html = view('comisiones.table', ['comisiones' => collect($comisiones)->map(fn($i) => (object) $i)])->render();
    
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
}
