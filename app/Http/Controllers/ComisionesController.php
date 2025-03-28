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
        $mes = $request->mes;
        $datos = $request->comisiones; // Recibe datos desde AJAX
    
        if($datos){
            foreach ($datos as $dato) {
                $vendedor = Savend::find($dato['id']);

                if ($vendedor) {
                    // Actualizar los valores en la BD (opcional)
                    $vendedor->es_gerente = $dato['es_gerente'];
                    $vendedor->comision_servicio = $dato['servicio'];
                    $vendedor->comision_producto = $dato['producto'];
                    $vendedor->comision_gerencia = $dato['gerencia'];
                    $vendedor->save();
                }
            }
        }

        // Obtener vendedores con datos actualizados
        $vendedores = Savend::with(['saitemfac' => function ($query) use ($mes) {
            $query->byMonth($mes)->where('TipoFac', 'F')->with('safact'); // Cargar la relación 'safact' dentro de 'saitemfac'
        }])->get();

    
        $comisiones = [];
        $totalVentasDepartamento = 0;
    
        foreach ($vendedores as $vendedor) {
            $totalVentas = 0;
            $comisionProducto = 0;
            $comisionServicio = 0;
    
            foreach ($vendedor->saitemfac as $item) {
                $montoConvertido = $item->TotalItem / $item->safact->factor;
                $totalVentas += $montoConvertido;
    
                if ($item->EsServ) {
                    $comisionProducto += $montoConvertido * ($vendedor->comision_producto / 100);
                } else {
                    $comisionServicio += $montoConvertido * ($vendedor->comision_servicio / 100);
                }
            }
    
            $totalVentasDepartamento += $totalVentas;
    
            $comisiones[] = [
                'id' => $vendedor->id,
                'vendedor' => $vendedor->descrip,
                'es_gerente' => $vendedor->es_gerente,
                'total_ventas' => $totalVentas,

                'comision_producto' => $comisionProducto,
                'comision_servicio' => $comisionServicio,
                'comision_gerencial' => 0,

                'percent_comision_producto' => $vendedor->comision_producto,
                'percent_comision_servicio' => $vendedor->comision_servicio,
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
    
}
