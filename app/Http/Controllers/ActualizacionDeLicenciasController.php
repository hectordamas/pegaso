<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ActualizacionDeLicencias, Saclie};

class ActualizacionDeLicenciasController extends Controller
{
    public function index(Request $request){
        $saclie = Saclie::orderby('descrip', 'asc')->get();

        $actualizacionDeLicencias = ActualizacionDeLicencias::bySaclie($request->codclie)
        ->byStatus($request->status)
        ->byIncidencias($request->incidencias)
        ->get();

        $widgets = collect([
            [
                'title' => 'Sin Actualizar', 
                'subtitle' => 'Clientes Por Actualizar', 
                'count' => $actualizacionDeLicencias->where('status', 'No Actualizado')->count(), 
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'yellow' 
            ],
            [
                'title' => 'Actualizados', 
                'subtitle' => 'Clientes Actualizados', 
                'count' => $actualizacionDeLicencias->where('status', 'Actualizado')->count(), 
                'icon' => 'fas fa-sync',
                'color' => 'green' 
            ],
            [
                'title' => 'Con Incidencias', 
                'subtitle' => 'Actualizaciones Con Incidencias', 
                'count' => $actualizacionDeLicencias->where('incidencias', 'Con Incidencias')->count(), 
                'icon' => 'far fa-times-circle',
                'color' => 'pink' 
            ],
            [
                'title' => 'Sin Incidencias', 
                'subtitle' => 'Actualizaciones Sin Incidencias', 
                'count' => $actualizacionDeLicencias->where('incidencias', 'Sin Incidencias')->count(), 
                'icon' => 'fas fa-thumbs-up',
                'color' => 'blue' 
            ],
        ])->map(fn($i) => (object) $i);

        return view('actualizacionDeLicencias.index', [
            'saclie' => $saclie,
            'actualizacionDeLicencias' => $actualizacionDeLicencias,
            'widgets' => $widgets
        ]);
    }

    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'codclie' => 'required|exists:saclie,codclie',
            'status' => 'required|in:Actualizado,No Actualizado',
            'incidencias' => 'required|in:Sin Incidencias,Con Incidencias',
            'observacion' => 'nullable|string|max:1000',
        ]);
    
        // Guardar el registro
        $licencia = new ActualizacionDeLicencias(); // Usa el nombre correcto del modelo
        $licencia->codclie = $request->codclie;
        $licencia->status = $request->status;
        $licencia->incidencias = $request->incidencias;
        $licencia->observacion = $request->observacion;
        $licencia->save();
    
        // Redirigir con mensaje de éxito
        return redirect()->route('actualizacion-licencias.index')->with('message', 'Actualización registrada correctamente.');
    }

    public function edit($id){
        $licencia = ActualizacionDeLicencias::find($id);
        $saclie = Saclie::orderby('descrip', 'asc')->get();

        return view('actualizacionDeLicencias.edit', [
            'saclie' => $saclie,
            'licencia' => $licencia,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'codclie' => 'required|string|max:20',
            'status' => 'required|in:Actualizado,No Actualizado',
            'incidencias' => 'nullable|in:Sin Incidencias,Con Incidencias',
            'observacion' => 'nullable|string|max:1000',
        ]);

        $licencia = ActualizacionDeLicencias::findOrFail($id);

        $licencia->codclie = $request->codclie;
        $licencia->status = $request->status;
        $licencia->incidencias = $request->incidencias;
        $licencia->observacion = $request->observacion;

        $licencia->save();

        return redirect()->route('actualizacion-licencias.index')
                         ->with('message', 'Licencia actualizada correctamente.');
    }

    
}
