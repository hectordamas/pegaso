<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EntradaEquipos, Consultor, Saclie, Estatus, ChatEntrada};
use Barryvdh\DomPDF\Facade\Pdf;
use Validator;
use Mail;
use Auth;
use Log;

class EntradaEquiposController extends Controller
{
    public function index(Request $request){
		// Recoge los filtros desde el request
		$codconsultor = $request->input('consultor');
		$codclie = $request->input('client');
		$codestatus = $request->input('estatus');
		$from = $request->input('from');
		$until = $request->input('until');

        $entradaequipos = EntradaEquipos::query()->byConsultor($codconsultor)
        ->bySaclie($codclie)
        ->byDateRange($from, $until);

        // Si no se filtra por estatus, aplicar el filtro por defecto
		if (!$codestatus) {
			$entradaequipos->whereNotIn('codestatus', [5]);
		} else {
			$entradaequipos->byStatus($codestatus);
		}

        $entradaequipos = $entradaequipos->orderBy('codentrada', 'desc')->get();

        $consultors = Consultor::where('inactivo', false)->get();
		$estatus = Estatus::where('inactivo', false)->get();
		$saclie = Saclie::orderby('descrip','asc')->get();

        return view('entradaequipos', [
            'entradaequipos' => $entradaequipos,
            'consultors' => $consultors,
            'saclie' => $saclie,
            'estatus' => $estatus
        ]);
    }

    public function store(Request $request){
        $regla = Validator::make($request->all(),[
            'codclie'=>'required',
            'actividad'=>'required',
            'codestatus'=>'required',
            'codconsultor'=>'required',
        ],
		$messages = [
			'required'	=> 'El campo :attribute es requerido',
			'numeric'   => 'EL campo :attribute debe contener solo números',
			'string' 	=> 'EL campo :attribute debe contener solo letras',
			'min'      	=> 'El campo :attribute debe contener al menos :min caracteres',
			'max'      	=> 'El campo :attribute debe contener un máximo de :max caracteres',
		]);
		
		if ($regla->fails()){
			foreach($regla->errors()->messages() as $error){
				$mensaje = $error;
			}            
			return redirect()->back()->withErrors($mensaje[0]."-4");
        }

		$codclie = $request->get('codclie');

		$cliente = Saclie::where('codclie', $codclie)->first();

		$codestatus = $request->get('codestatus');
		$codconsultor = $request->get('codconsultor');
		$actividad = $request->get('actividad');
        $observacion = $request->get('observacion');
		$codusuario = Auth::user()->codusuario;
		
		$entrada = new EntradaEquipos();
		$entrada->fecha = now(); 
		$entrada->codclie	= $codclie; 
		$entrada->codestatus = $codestatus; 
		$entrada->codconsultor = $codconsultor; 
		$entrada->actividad = $actividad; 
        $entrada->observacion = $observacion; 
		$entrada->codusuario = $codusuario; 
		$entrada->save();

        // Si hay una observación, registrar en el chat
        if ($request->observacion) {
            $chatentrada = new ChatEntrada();
            $chatentrada->fechayhora = now();
            $chatentrada->codentrada = $entrada->codentrada;
            $chatentrada->codusuario = $codusuario;
            $chatentrada->mensaje   = $request->observacion;
            $chatentrada->save();
        }

        return redirect()->back()->with('message', "Ingreso de equipos Registrado con éxito!");		

    }

    public function update(Request $request){
		$id	= $request->input('entradaEquiposId');
		$codestatus	= $request->input('codestatus');
						
		$entrada = EntradaEquipos::find($id);
	
		if($entrada){	
            $entrada->codestatus = $codestatus;		
            if($codestatus == 2){
				$entrada->fechaentrega = date('Y-m-d');
			}
			$entrada->save();

			return redirect()->back()->with('message', 'Registro Actualizado correctamente.');
		}	
			
        return redirect()->back()->with('error', 'Ocurrió un error durante la actualización del estatus.');
    }


    protected function mail($asunto, $emaildestino, $datos, $cliente, $view)
    {
        try {
            Mail::send($view, ['datos' => $datos, 'cliente' => $cliente], function ($message) use ($asunto, $emaildestino) {
                $message->from('no-responder@saintnetweb.info', env('APP_NAME'))
                        ->to([$emaildestino, 'hectorgabrieldm@hotmail.com'])
                        ->subject($asunto);
            });
    
        } catch (\Exception $e) {
            Log::error("Error al enviar correo: " . $e->getMessage()); // Registrar error
            return response()->json(['error' => "Error: " . $e->getMessage()]); // Fallo
        }
    }

    public function print($id)
    {
        $entrada = EntradaEquipos::where('codentrada', $id)->first();
        if (!$entrada) {
            abort(404, 'Entrada no encontrada');
        }
    
        $titulo = $entrada->codestatus == 1 ? 'ENTRADA' : 'SALIDA';
        $titulo2 = $entrada->codestatus == 1 ? 'ENTREGANDO' : 'RECIBIENDO';
    
        $data = [
            'entrada' => $entrada,
            'titulo' => $titulo,
            'titulo2' => $titulo2
        ];
    
        $pdf = Pdf::loadView('pdf.entradaequipos', $data)
            ->setPaper([0, 0, 226.77, 841.89], 'portrait') // 80mm x 297mm en puntos
            ->setOption('defaultFont', 'Courier');
    
        return $pdf->stream('entrada_equipos.pdf');
    }
}
