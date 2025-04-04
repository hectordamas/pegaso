<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Visita, Consultor, Saclie, ChatVisita, User, Acompanantes};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use Mail;
use Auth;
use App\Traits\VerifyPermissions;

class VisitasController extends Controller
{
    use VerifyPermissions; // Importamos el trait

    public function index(Request $request)
    {
        // Verificar si el usuario tiene permiso "vertodo"
        $puedeVerTodo = $this->hasPermissions('vertodo');

        // Obtener listas de datos auxiliares
        $consultores = Consultor::where('inactivo', false)->get();
        $users = User::where('inactivo', false)->get();
        $saclie = Saclie::orderby('descrip', 'asc')->get();

        // Construcción de la consulta de visitas
        $visitasQuery = Visita::byDateRange($request->from, $request->until)
            ->byConsultor($request->codconsultor);

        // Si el usuario no tiene permiso "vertodo", solo puede ver sus visitas
        if (!$puedeVerTodo) {
            $visitasQuery->where('codconsultor', Auth::id());
        }

        $visitas = $visitasQuery->orderBy('codvisita', 'desc')->get();

        // Obtener cliente y consultor si se han enviado en la solicitud
        $cliente = Saclie::where('codclie', $request->requestCliente)->first();
        $consultor = Consultor::where('codconsultor', $request->requestConsultor)->first();

        return view('visitas', [
            'visitas' => $visitas,
            'consultores' => $consultores,
            'saclie' => $saclie,
            'users' => $users,

            'requestAccion' => $request->requestAccion,
            'requestFecha' => $request->requestFecha,
            'requestFechaFinal' => $request->requestFechaFinal,
            'requestCliente' => $request->requestCliente,
            'requestConsultor' => $request->requestConsultor,
            'requestDescripcion' => $request->requestDescripcion,

            'ccliente' => $cliente,
            'cconsultor' => $consultor,
            'registra' => $this->hasPermissions('registra')
        ]);
    }

    public function store(Request $request){
		$regla = Validator::make($request->all(),[
            'codclie'=>'required',
            'codconsultor'=>'required',			
        ],		

		$messages = [
			'required'	=> 'El campo :attribute es requerido',
			'numeric'   => 'EL campo :attribute debe contener solo n&uacute;meros',
			'string' 	=> 'EL campo :attribute debe contener solo letras',
			'min'      	=> 'El campo :attribute debe contener al menos :min caracteres',
			'max'      	=> 'El campo :attribute debe contener un maximo de :max caracteres',
		]);
		
		if ($regla->fails()){
			foreach($regla->errors()->messages() as $error){
				$mensaje=$error;
			}            
			return redirect()->back()->withErrors($mensaje[0]."-4");
        }

		$cliente = $request->input('codclie');
		$codconsultor = $request->input('codconsultor');
		$observacion = $request->input('observacion');
		
	    $visita = new Visita();
	    $visita->fecha = date('Y-m-d'); 
	    $visita->codclie = $cliente; 
        $visita->entry_date = $request->entry_date; 
        $visita->departure_date = $request->departure_date; 
        $visita->notas = $request->notas; 
	    $visita->codconsultor = $codconsultor; 
	    $visita->observacion = $observacion; 
	    $visita->codusuario	= Auth::user()->codusuario; 
	    $visita->save();

                
        // Guardar las acompanantes (si existen)
        if ($request->has('acompanantes')) {
            $acompanantes = array_map(fn($codusuario) => [
                'codvisita' => $visita->codvisita,
                'codusuario' => $codusuario
            ], $request->acompanantes);

            // Insertar acompanantes en la base de datos
            DB::table('acompanantes')->insert($acompanantes);
        }

        if($observacion){
			$chat = new ChatVisita();
			$chat->fechayhora = date('Y-m-d H:i:s'); 
			$chat->codvisita = $visita->codvisita; 
			$chat->codusuario = Auth::user()->codusuario; 
			$chat->mensaje = $observacion;
			$chat->save();
		}

        $data = [
            'codvisita'   => $visita->codvisita,
            'fecha'       => date('d/m/Y', strtotime($visita->fecha)),
            'descrip'     => $visita->saclie->descrip ?? 'N/A',
            'consultor'   => $visita->consultor->nombre ?? 'N/A',
            'observacion' => $visita->observacion,
            'tipo'        => "digital"
        ];

        // Generar PDF en memoria
        $pdf = Pdf::loadView('pdf.visitas', $data);
        $pdfOutput = $pdf->output();

        // Obtener los emails de los consultores mencionados
        $emails = User::whereIn('codusuario', $request->acompanantes)
        ->get()
        ->pluck('email')
        ->filter()
        ->unique()
        ->toArray(); // Obtiene solo los emails únicos en un array
        

        Mail::send('mails.visita', ['visita' => $visita, 'cliente' => $cliente], function ($message) use ($emails, $visita, $pdfOutput) {
            $message->from('no-responder@saintnetweb.info', env('APP_NAME'))
                ->to($emails)
                ->to([$visita->user->email, 'hectorgabrieldm@hotmail.com'])
                ->subject('Nueva Visita Creada')
                ->attachData($pdfOutput, 'orden_servicio.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });


        return redirect('visitas')->with('message', 'Visita Creada con éxito');
    }

    
    public function pdf($codvisita)
    {
        $visita = Visita::where('codvisita', $codvisita)->first();

        if (!$visita) {
            return abort(404, 'Visita no encontrada.');
        }

        $data = [
            'codvisita'   => $visita->codvisita,
            'fecha'       => date('d/m/Y', strtotime($visita->fecha)),
            'descrip'     => $visita->saclie->descrip ?? 'N/A',
            'consultor'   => $visita->consultor->nombre ?? 'N/A',
            'observacion' => $visita->observacion,
            'tipo'        => "digital"
        ];

        $pdf = Pdf::loadView('pdf.visitas', $data);
		$namefile = 'orden_servicio_'.time().'.pdf';

        return $pdf->stream($namefile);

    }

    public function fileUpload(Request $request){
        // Guardar archivo sin usar Storage
        if ($request->hasFile('file')) {
            $visita = Visita::where('codvisita', $request->visitaId)->first();

            $ruta = 'uploads/visitas'; 
            $archivo = $request->file('file');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName(); // Nombre único
            $rutaDestino = public_path($ruta); // Carpeta dentro de "public"

            $archivo->move($rutaDestino, $nombreArchivo); // Mover archivo

            $visita->adjunto = $ruta . '/' . $nombreArchivo; // Guardar ruta en BD
            $visita->save();

            return redirect()->back()->with('message', 'Archivo subido con éxito!');
        }

        return redirect()->back()->with('error', 'No se ha cargado un archivo válido');

    }
}
