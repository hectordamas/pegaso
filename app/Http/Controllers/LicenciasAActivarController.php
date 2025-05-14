<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\VerifyPermissions;
use App\Models\{LicenciasAActivar, Saclie, ComprobanteLicencia};

class LicenciasAActivarController extends Controller
{
    use VerifyPermissions;

    public function index(Request $request){
        $from = $request->input('from');
		$until = $request->input('until');

        $licenciasAActivar = LicenciasAActivar::byDateRange($from, $until)
        ->byActivada($request->input('activada'))
        ->byPagada($request->input('pagada'))
        ->get();

		$saclie = Saclie::orderby('descrip', 'asc')->get();

        return view('licenciasAActivar.index', [
            'saclie' => $saclie,
            'licenciasAActivar' => $licenciasAActivar
        ]);
    }

    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'codclie'     => 'required|exists:saclie,codclie',
            'descripcion' => 'required|string|max:255',
            'licencias'   => 'required|string|max:100',
            'fechadepago' => 'required|date',
            'monto'       => 'required',
            'activada'    => 'nullable|boolean',
            'pagada'      => 'nullable|boolean',
        ]);
    
        // Asignación manual y guardado
        $licencia = new LicenciasAActivar();
        $licencia->codclie     = $request->codclie;
        $licencia->serial     = $request->serial;
        $licencia->descripcion = $request->descripcion;
        $licencia->licencias   = $request->licencias;
        $licencia->fechadepago = $request->fechadepago;
        $licencia->monto       = $request->monto;
        $licencia->notas       = $request->notas;
        $licencia->activada    = $request->has('activada') ? true : false;
        $licencia->pagada      = $request->has('pagada') ? true : false;

        if ($request->hasFile('comprobantes')) {
            foreach ($request->file('comprobantes') as $archivo) {
                $ruta = 'uploads/licenciasComprobantes';
                $nombreArchivo = time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $rutaDestino = public_path($ruta);
                $archivo->move($rutaDestino, $nombreArchivo);
    
                // Crear el comprobante relacionado
                $comprobante = new ComprobanteLicencia();
                $comprobante->licencia_id = $licencia->id; 
                $comprobante->ruta = $ruta . '/' . $nombreArchivo; 
                $comprobante->save();
            }
        }
        
        $licencia->save();
    
        return redirect()->back()->with('message', 'Licencia registrada exitosamente.');
    }

    public function upload(Request $request){
        $licencia = LicenciasAActivar::find($request->licenciaId);

        if ($request->hasFile('comprobantes')) {
            foreach ($request->file('comprobantes') as $archivo) {
                $ruta = 'uploads/licenciasComprobantes';
                $nombreArchivo = time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $rutaDestino = public_path($ruta);
                $archivo->move($rutaDestino, $nombreArchivo);
    
                // Crear el comprobante relacionado
                $comprobante = new ComprobanteLicencia();
                $comprobante->licencia_id = $licencia->id; 
                $comprobante->ruta = $ruta . '/' . $nombreArchivo; 
                $comprobante->save();
            }
        }
        
        $licencia->save();

        return redirect()->back()->with('message', 'Comprobantes cargados con éxito!.');

    }

    public function edit($id)
    {
        $licencia = LicenciasAActivar::with('saclie')->findOrFail($id);
		$saclie = Saclie::orderby('descrip', 'asc')->get();

        return view('licenciasAActivar.edit', compact('licencia', 'saclie'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'licencias'   => 'required|string|max:100',
            'notas'       => 'nullable|string',
        ]);

        $licencia = LicenciasAActivar::findOrFail($id);

        $licencia->descripcion = $request->descripcion;
        $licencia->licencias   = $request->licencias;
        $licencia->fechadepago = $request->fechadepago;
        $licencia->monto       = $request->monto;
        $licencia->notas       = $request->notas;
        $licencia->activada    = $request->has('activada');
        $licencia->pagada      = $request->has('pagada');
        $licencia->serial     = $request->serial;
        $licencia->save();

        return redirect()->route('licencias.index')->with('message', 'Licencia actualizada exitosamente.');
    }

    public function updateStatus(Request $request, $id)
    {
        $licencia = LicenciasAActivar::find($id);
    
        if (!$licencia) {
            return response()->json(['success' => false, 'message' => 'Licencia no encontrada.']);
        }
    
        $field = $request->input('field');
        $value = $request->input('value');
    
        if (!in_array($field, ['activada', 'pagada'])) {
            return response()->json(['success' => false, 'message' => 'Campo inválido.']);
        }
    
        $licencia->$field = $value;
        $licencia->save();
    
        return response()->json(['success' => true, 'message' => 'Estado actualizado correctamente.']);
    }


    public function comprobantes($id)
    {
        $licencia = LicenciasAActivar::with('comprobantes')->findOrFail($id);
        return view('licenciasAActivar.partials.comprobantes', compact('licencia'));
    }

    public function destroy($id)
    {
        $licencia = LicenciasAActivar::findOrFail($id);
    
        // Elimina comprobantes si los hay
        foreach ($licencia->comprobantes as $comp) {
            if (file_exists(public_path($comp->ruta))) {
                unlink(public_path($comp->ruta));
            }
            $comp->delete();
        }
    
        // Elimina adjunto si existe
        if ($licencia->adjunto && file_exists(public_path($licencia->adjunto))) {
            unlink(public_path($licencia->adjunto));
        }
    
        $licencia->delete();
    
        return redirect()->back()->with('message', 'Licencia eliminada correctamente.');
    }
    
}
