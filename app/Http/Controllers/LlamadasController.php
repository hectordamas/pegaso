<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Llamadas, TipoLlamada, Motivo, Consultor, ChatLlamada, Menciones, Saclie, User};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\VerifyPermissions;
use Auth;
use Mail;
use Log;

class LlamadasController extends Controller
{
    use VerifyPermissions;

    public function index(Request $request)
    {
        // Verificar si el usuario tiene permiso "vertodo"
        $puedeVerTodo = $this->hasPermissions('vertodo');

        $llamadasQuery = Llamadas::byDateRange($request->from, $request->until)
            ->byTipoLlamada($request->codtipollamada)
            ->byMotivo($request->codmotivo)
            ->orderBy('codllamada', 'desc');

        if (!$puedeVerTodo) {
            // Si no tiene "vertodo", solo ve sus propias llamadas
            $llamadasQuery->where('codconsultor', Auth::id());
        }

        $llamadas = $llamadasQuery->get();

        $tipoLlamadas = TipoLlamada::where('inactivo', false)->get();
        $motivos = Motivo::where('inactivo', false)->get();
        $consultores = Consultor::where('inactivo', false)->get();

        $cliente = Saclie::where('codclie', $request->requestCliente)->first();
        $consultor = Consultor::where('codconsultor', $request->requestConsultor)->first();
        $users = User::where('inactivo', false)->get();

        return view('comunicaciones', [
            'llamadas' => $llamadas,
            'tipoLlamadas' => $tipoLlamadas,
            'motivos' => $motivos,
            'consultores' => $consultores,

            'requestAccion' => $request->requestAccion,
            'requestFecha' => $request->requestFecha,
            'requestCliente' => $request->requestCliente,
            'requestConsultor' => $request->requestConsultor,
            'requestDescripcion' => $request->requestDescripcion,

            'ccliente' => $cliente,
            'cconsultor' => $consultor,
            'registra' => $this->hasPermissions('registra')
        ]);
    }

    public function store(Request $request){
        // Validación de los datos
        $request->validate([
            'contacto' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);
    
        // Crear la nueva llamada
        $llamada = new Llamadas();
        $llamada->codtipollamada = $request->codtipollamada;
        $llamada->contacto = $request->contacto;
        $llamada->telefono = $request->telefono;
        $llamada->email = $request->email;
        $llamada->codmotivo = $request->codmotivo;
        $llamada->codconsultor = $request->codconsultor;
        $llamada->codusuario = Auth::user()->codusuario;
        $llamada->observacion = $request->observacion;
        $llamada->tipoDeComunicacion = $request->tipoDeComunicacion;
        $llamada->fecha = now(); // Guarda la fecha actual

        // Guardar archivo sin usar Storage
        if ($request->hasFile('file')) {
            $ruta = 'uploads/llamadas'; 
            $archivo = $request->file('file');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName(); // Nombre único
            $rutaDestino = public_path($ruta); // Carpeta dentro de "public"

            $archivo->move($rutaDestino, $nombreArchivo); // Mover archivo

            $llamada->adjunto = $ruta . '/' . $nombreArchivo; // Guardar ruta en BD
        }

        $llamada->save();

        if($request->observacion){
			$chat = new ChatLlamada();
			$chat->fechayhora = date('Y-m-d H:i:s'); 
			$chat->codllamada = $llamada->codllamada; 
			$chat->codusuario = Auth::user()->codusuario; 
			$chat->mensaje = $request->observacion;
			$chat->save();
        }

        // Guardar las menciones (si existen)
        if ($request->has('menciones')) {
            $menciones = array_map(fn($codconsultor) => [
                'codllamada' => $llamada->codllamada,
                'codconsultor' => $codconsultor
            ], $request->menciones);

            // Insertar menciones en la base de datos
            DB::table('menciones')->insert($menciones);

            // Obtener los emails de los consultores mencionados
            $emails = Consultor::whereIn('codconsultor', $request->menciones)
            ->with('user')
            ->get()
            ->pluck('user.email')
            ->filter()
            ->unique()
            ->toArray(); // Obtiene solo los emails únicos en un array

            if ($emails) {
                $asunto = 'Has sido mencionado en ' . $llamada->tipoDeComunicacion;
                $datos = [
                    'contacto' => $llamada->contacto,
                    'telefono' => $llamada->telefono,
                    'observacion' => $llamada->observacion,
                    'tipoDeComunicacion' => $llamada->tipoDeComunicacion
                ];

                $formato = 'mails.menciones';

                // Suponiendo que $llamada contiene la llamada actual
                $consultorOriginal = $llamada->consultor;  // Relación con el modelo Consultor
                $emailConsultorOriginal = $consultorOriginal->user->email;  // Relación con el modelo User que contiene el email
    
                $this->enviaremail($asunto, $emails, $datos, $formato, $emailConsultorOriginal);
            }
        }

        return redirect()->back()->with('message', 'Comunicación registrada correctamente.');
    }

    protected function enviaremail($asunto, $emaildestino, $datos, $formato, $emailConsultorOriginal)
    {
        try {    
            Mail::send($formato, ['datos' => $datos], function ($message) use ($datos, $asunto, $emaildestino, $emailConsultorOriginal) {
                $message->from('no-responder@saintnetweb.info', env('APP_NAME'));
                $message->subject($asunto);
                $message->to($emaildestino); // Laravel acepta un array de emails aquí
                $message->cc($emailConsultorOriginal);
            });
        } catch (\Exception $e) {
            Log::error("Error al enviar correo: " . $e->getMessage()); // Registrar error
            
            return response()->json(['error' => "Error: " . $e->getMessage()]); // Fallo        
        }
    }
}
