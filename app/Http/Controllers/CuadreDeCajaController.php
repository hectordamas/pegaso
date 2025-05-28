<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{CuadreDeCaja, MovimientosCuadre, Saclie};
use Barryvdh\DomPDF\Facade\Pdf;

class CuadreDeCajaController extends Controller
{
public function index()
{
    $cuadresDeCaja = CuadreDeCaja::with('movimientos')->get();

    $cuadresDeCaja->transform(function ($cuadre) {
        $movimientos = $cuadre->movimientos;

        $ds_sistemas = $movimientos->where('responsable', 'DS Sistemas 3000');
        $daniel_sousa = $movimientos->where('responsable', 'Daniel Sousa');

        $cuadre->total_ds_sistemas_3000 = $ds_sistemas->where('tipo_movimiento', 'Ingreso')->sum('valor')
                                        - $ds_sistemas->where('tipo_movimiento', 'Egreso')->sum('valor');

        $cuadre->total_daniel_sousa = $daniel_sousa->where('tipo_movimiento', 'Ingreso')->sum('valor')
                                    - $daniel_sousa->where('tipo_movimiento', 'Egreso')->sum('valor');

        return $cuadre;
    });

    return view('cuadreDeCaja', compact('cuadresDeCaja'));
}

    public function create(){
        $saclie = Saclie::orderby('descrip', 'asc')->get();

        return view('cuadreDeCaja.create', [
            'saclie' => $saclie
        ]);
    }

    public function store(Request $request){

        // Validar los datos del formulario
        $request->validate([
            'fecha' => 'required|date',
            'numero_orden' => 'nullable',
            'observaciones' => 'nullable|string',
            'responsable' => 'required|array',
            'responsable.*' => 'required|string',
            'tipo_pago' => 'required|array',
            'tipo_pago.*' => 'required|string',
            'tipo_movimiento' => 'required|array',
            'tipo_movimiento.*' => 'required|string',
            'valor' => 'required|array',
            'codclie' => 'nullable|array',
            'codclie.*' => 'nullable|string',
            'presupuesto' => 'nullable|array',
            'presupuesto.*' => 'nullable|string',
            'descripcion' => 'nullable|array',
            'descripcion.*' => 'nullable|string',
        ]);

        // Crear el Cuadre de Caja
        $cuadre = new CuadreDeCaja();
        $cuadre->fecha = $request->fecha;
        $cuadre->numero_orden = $request->numero_orden;
        $cuadre->observaciones = $request->observaciones;
        $cuadre->save();  // Guardar el cuadre de caja

        // Crear los movimientos manualmente y asignar valores
        foreach ($request->responsable as $key => $responsable) {
            $saclie = Saclie::where('codclie', $request->codclie[$key])->first();

            $movimiento = new MovimientosCuadre();
            $movimiento->cuadre_id = $cuadre->id;  // Relacionar el movimiento con el cuadre de caja
            $movimiento->responsable = $responsable;
            $movimiento->tipo_pago = $request->tipo_pago[$key];
            $movimiento->tipo_movimiento = $request->tipo_movimiento[$key];
            $movimiento->codclie = $request->codclie[$key] ?? null;
            $movimiento->cliente = $saclie->descrip ?? null;
            $movimiento->presupuesto = $request->presupuesto[$key] ?? null;
            $movimiento->descripcion = $request->descripcion[$key] ?? null;
            $movimiento->valor = str_replace(',','.', str_replace('.','', $request->valor[$key]));
            $movimiento->save();  // Guardar cada movimiento
        }

        return redirect()
        ->route('cuadre-de-caja.index')
        ->with('message', 'Cuadre de Caja registrado correctamente.');
        
    }

    public function show($id)
    {
        $cuadre = CuadreDeCaja::with('movimientos')->findOrFail($id);
    
        // Agrupamos por responsable + tipo_pago
        $agrupados = $cuadre->movimientos->groupBy(function ($item) {
            return $item->responsable . '|' . $item->tipo_pago;
        });
    
        $detalle = [];
    
        foreach ($agrupados as $clave => $movimientos) {
            list($responsable, $tipo_pago) = explode('|', $clave);
    
            $ingresos = $movimientos->where('tipo_movimiento', 'Ingreso');
            $egresos = $movimientos->where('tipo_movimiento', 'Egreso');
    
            $total_ingreso = $ingresos->sum('valor');
            $total_egreso = $egresos->sum('valor');
            $saldo = $total_ingreso - $total_egreso;
    
            $detalle[] = [
                'responsable' => $responsable,
                'tipo_pago' => $tipo_pago,
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'total_ingreso' => $total_ingreso,
                'total_egreso' => $total_egreso,
                'saldo' => $saldo,
            ];
        }
    
        $saldo_general = collect($detalle)->sum('saldo');
    
        return view('cuadreDeCaja.show', compact('cuadre', 'detalle', 'saldo_general'));
    }

    public function pdf(Request $request, $id){
        $cuadre = CuadreDeCaja::findOrFail($id);
        $detalle = $this->getDetalle($cuadre); // Usa un helper si lo tienes
        $saldo_general = $detalle->sum('saldo');
    
        $pdf = Pdf::loadView('pdf.cuadre_de_caja', compact('cuadre', 'detalle', 'saldo_general'))
                  ->setPaper('letter', 'portrait'); // Carta vertical
    
        return $pdf->stream("arqueo-caja-{$cuadre->numero_orden}.pdf");
    }
    
    public function edit($id){
        $cuadre = CuadreDeCaja::findOrFail($id);
        $saclie = Saclie::orderby('descrip', 'asc')->get();

        return view('cuadreDeCaja.edit', compact('cuadre', 'saclie'));
    }

    public function update($id, Request $request){
        // Validar los datos del formulario
        $request->validate([
            'fecha' => 'required|date',
            'numero_orden' => 'nullable',
            'observaciones' => 'nullable|string',
            'responsable' => 'required|array',
            'responsable.*' => 'required|string',
            'tipo_pago' => 'required|array',
            'tipo_pago.*' => 'required|string',
            'tipo_movimiento' => 'required|array',
            'tipo_movimiento.*' => 'required|string',
            'valor' => 'required|array',
            'codclie' => 'nullable|array',
            'codclie.*' => 'nullable|string',
            'presupuesto' => 'nullable|array',
            'presupuesto.*' => 'nullable|string',
            'descripcion' => 'nullable|array',
            'descripcion.*' => 'nullable|string',
        ]);

        $cuadre = CuadreDeCaja::findOrFail($id);
        $cuadre->fecha = $request->fecha;
        $cuadre->numero_orden = $request->numero_orden;
        $cuadre->observaciones = $request->observaciones;
        $cuadre->save();  // Guardar el cuadre de caja

        $cuadre->movimientos()->delete();

        // Crear los movimientos manualmente y asignar valores
        foreach ($request->responsable as $key => $responsable) {
            $saclie = Saclie::where('codclie', $request->codclie[$key])->first();

            $movimiento = new MovimientosCuadre();
            $movimiento->cuadre_id = $cuadre->id;  // Relacionar el movimiento con el cuadre de caja
            $movimiento->responsable = $responsable;
            $movimiento->tipo_pago = $request->tipo_pago[$key];
            $movimiento->tipo_movimiento = $request->tipo_movimiento[$key];
            $movimiento->codclie = $request->codclie[$key] ?? null;
            $movimiento->cliente = $saclie->descrip ?? null;
            $movimiento->presupuesto = $request->presupuesto[$key] ?? null;
            $movimiento->descripcion = $request->descripcion[$key] ?? null;
            $movimiento->valor = str_replace(',','.', str_replace('.','', $request->valor[$key]));
            $movimiento->save();  // Guardar cada movimiento
        }


        return redirect()->route('cuadre-de-caja.index')->with('message', 'Cuadre de Caja modificado correctamente.');
    }

    private function getDetalle($cuadre)
    {
        // Aquí deberías replicar cómo agrupas ingresos, egresos, saldos, etc.
        // Esto es un ejemplo genérico, ajusta a tu estructura real

        $cuadre = CuadreDeCaja::with('movimientos')->findOrFail($cuadre->id);
    
        // Agrupamos por responsable + tipo_pago
        $agrupados = $cuadre->movimientos->groupBy(function ($item) {
            return $item->responsable . '|' . $item->tipo_pago;
        });
    
        $detalle = [];
    
        foreach ($agrupados as $clave => $movimientos) {
            list($responsable, $tipo_pago) = explode('|', $clave);
    
            $ingresos = $movimientos->where('tipo_movimiento', 'Ingreso');
            $egresos = $movimientos->where('tipo_movimiento', 'Egreso');
    
            $total_ingreso = $ingresos->sum('valor');
            $total_egreso = $egresos->sum('valor');
            $saldo = $total_ingreso - $total_egreso;
    
            $detalle[] = [
                'responsable' => $responsable,
                'tipo_pago' => $tipo_pago,
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'total_ingreso' => $total_ingreso,
                'total_egreso' => $total_egreso,
                'saldo' => $saldo,
            ];
        }
    
        return collect($detalle);
    }

    public function actualizarRevisado(Request $request, $id)
    {
        $cuadre = CuadreDeCaja::findOrFail($id);
        $cuadre->revisado = $request->revisado;
        $cuadre->save();
    
        return response()->json(['success' => true]);
    }
}
