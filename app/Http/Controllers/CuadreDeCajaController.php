<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{CuadreDeCaja, MovimientosCuadre};

class CuadreDeCajaController extends Controller
{
    public function index(Request $request){

        $cuadresDeCaja = CuadreDeCaja::orderBy('id', 'desc')->get();

        return view('cuadreDeCaja', [
            'cuadresDeCaja' => $cuadresDeCaja
        ]);
    }

    public function create(){
        return view('cuadreDeCaja.create');
    }

    public function store(Request $request){

        // Validar los datos del formulario
        $request->validate([
            'fecha' => 'required|date',
            'numero_orden' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
            'responsable' => 'required|array',
            'responsable.*' => 'required|string',
            'tipo_pago' => 'required|array',
            'tipo_pago.*' => 'required|string',
            'tipo_movimiento' => 'required|array',
            'tipo_movimiento.*' => 'required|string',
            'valor' => 'required|array',
            'cliente' => 'nullable|array',
            'cliente.*' => 'nullable|string',
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
            $movimiento = new MovimientosCuadre();
            $movimiento->cuadre_id = $cuadre->id;  // Relacionar el movimiento con el cuadre de caja
            $movimiento->responsable = $responsable;
            $movimiento->tipo_pago = $request->tipo_pago[$key];
            $movimiento->tipo_movimiento = $request->tipo_movimiento[$key];
            $movimiento->cliente = $request->cliente[$key] ?? null;
            $movimiento->presupuesto = $request->presupuesto[$key] ?? null;
            $movimiento->descripcion = $request->descripcion[$key] ?? null;
            $movimiento->valor = $request->valor[$key];
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
    
    public function edit($id){
        $cuadre = CuadreDeCaja::findOrFail($id);

        return view('cuadreDeCaja.edit', compact('cuadre'));
    }

    public function update($id){
        // Validar los datos del formulario
        $request->validate([
            'fecha' => 'required|date',
            'numero_orden' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
            'responsable' => 'required|array',
            'responsable.*' => 'required|string',
            'tipo_pago' => 'required|array',
            'tipo_pago.*' => 'required|string',
            'tipo_movimiento' => 'required|array',
            'tipo_movimiento.*' => 'required|string',
            'valor' => 'required|array',
            'cliente' => 'nullable|array',
            'cliente.*' => 'nullable|string',
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
            $movimiento = new MovimientosCuadre();
            $movimiento->cuadre_id = $cuadre->id;  // Relacionar el movimiento con el cuadre de caja
            $movimiento->responsable = $responsable;
            $movimiento->tipo_pago = $request->tipo_pago[$key];
            $movimiento->tipo_movimiento = $request->tipo_movimiento[$key];
            $movimiento->cliente = $request->cliente[$key] ?? null;
            $movimiento->presupuesto = $request->presupuesto[$key] ?? null;
            $movimiento->descripcion = $request->descripcion[$key] ?? null;
            $movimiento->valor = $request->valor[$key];
            $movimiento->save();  // Guardar cada movimiento
        }

        return redirect()->route('cuadre-de-caja.index')->with('message', 'Cuadre de Caja modificado correctamente.');
    }
}
