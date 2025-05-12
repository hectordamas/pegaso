<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\VerifyPermissions;
use App\Models\{LicenciasAActivar, Saclie};

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
        $licencia->descripcion = $request->descripcion;
        $licencia->licencias   = $request->licencias;
        $licencia->fechadepago = $request->fechadepago;
        $licencia->monto       = $request->monto;
        $licencia->notas       = $request->notas;
        $licencia->activada    = $request->has('activada') ? true : false;
        $licencia->pagada      = $request->has('pagada') ? true : false;

        // Guardar archivo sin usar Storage
        if ($request->hasFile('file')) {
            $ruta = 'uploads/licenciasComprobantes'; 
            $archivo = $request->file('file');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName(); // Nombre único
            $rutaDestino = public_path($ruta); // Carpeta dentro de "public"
        
            $archivo->move($rutaDestino, $nombreArchivo); // Mover archivo
        
            $licencia->adjunto = $ruta . '/' . $nombreArchivo; // Guardar ruta en BD
        }
        
        $licencia->save();
    
        return redirect()->back()->with('message', 'Licencia registrada exitosamente.');
    }

    public function upload(Request $request){
        $licencia = LicenciasAActivar::find($request->licenciaId);

        // Guardar archivo sin usar Storage
        if ($request->hasFile('file')) {
            $ruta = 'uploads/licenciasComprobantes'; 
            $archivo = $request->file('file');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName(); // Nombre único
            $rutaDestino = public_path($ruta); // Carpeta dentro de "public"
        
            $archivo->move($rutaDestino, $nombreArchivo); // Mover archivo
        
            $licencia->adjunto = $ruta . '/' . $nombreArchivo; // Guardar ruta en BD
        }
        
        $licencia->save();

        return redirect()->back()->with('message', 'Archivo subido con éxito!.');

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

    
}
