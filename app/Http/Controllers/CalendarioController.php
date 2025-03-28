<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Calendario, Saclie, Consultor};
use Carbon\Carbon;
use Mail;
use Auth;
use Log;

class CalendarioController extends Controller
{
    public function index(Request $request)
	{
		$consultors = Consultor::where('inactivo', false)->get();
		$saclie = Saclie::orderby('descrip', 'asc')->get();

        $eventos =  Calendario::all()->map(function ($item) {
            return [
                'id'    => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'consultor' => $item->consultor->nombre,
                'eventType' => $item->evenType,
                'interactionType' => $item->interactionType,
                'cliente' => $item->saclie->descrip ?? $item->lead,
                'start' => $item->entry_date, // Fecha de inicio del evento
                'entry_date' => $item->entry_date, // Fecha de inicio del evento
                'end'   => $item->departure_date, // Fecha de fin del evento
                'borderColor' => $item->color ? $item->color : '#404E67', 
                'textColor' => '#000',
                'itemDotColor' => $item->color ? $item->color : '#01A9AC',
                //'backgroundColor' => $item->color, // Color personalizado
            ];
        });

		return view('calendario', [
            'saclie' => $saclie,
            'consultors' => $consultors, 
            'eventos' => $eventos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'codconsultor' => 'required|exists:consultor,id',
            'desde' => 'required|date',
            'title' => 'required|string|max:255',
            'eventType' => 'required|string',
            'interactionType' => 'required|string'
        ]);

         // Validar que la fecha no sea menor a la actual
         if (strtotime($request->desde) < strtotime(now())) {
            return redirect()->back()->with('error', 'La fecha programada no puede ser menor a a la actual');
        }

        $client = Saclie::where('codclie', $request->codclie)->first();
        $consultor = Consultor::where('codconsultor', $request->codconsultor)->first();

        $fechaDesde     = new Carbon(date('Y-m-d H:i',strtotime($request->desde))); 
        $departure_date = date('Y-m-d H:i', strtotime($request->desde.' + 2 hour'));

        $event = new Calendario();
        if($client){
            $event->codclie = $request->codclie;
        }else{
            $event->lead = $request->codclie;
        }
        $event->codconsultor = $request->codconsultor;
        $event->entry_date = $fechaDesde;
        $event->departure_date = $departure_date;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->eventType = $request->eventType;
        $event->interactionType = $request->interactionType;
        $event->color = $consultor->color ?? ''; 
        $event->save();

        $userEmail = Auth::user()->email;

        $datos = [];
        $datos['fecha']     = $event->entry_date;
        $datos['cliente']   = $event->saclie->descrip ?? $evento->lead;
        $datos['consultor'] = $event->consultor->nombre;
        $datos['tipo'] = 'asignacion';

        $emailEnviado = $this->mail('Registro de un Evento en Calendario', $userEmail, $datos);

        return redirect()->back()->with('message', 'Evento Programado con éxito');
    }

    public function update($id, Request $request){
        $event = Calendario::find($id);

        if (!$event) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }
    
        $event->entry_date = Carbon::parse($request->start); // Guardamos la nueva 
        $event->reminder_sent = false;
        $event->save();

        $userEmail = Auth::user()->email;

        $datos = [];
        $datos['fecha']     = $event->entry_date;
        $datos['cliente']   = $event->saclie->descrip ?? $evento->lead;
        $datos['consultor'] = $event->consultor->nombre;
        $datos['tipo'] = 'modificacion';


        $emailEnviado = $this->mail('Modificación de fecha de un Evento en Calendario', $userEmail, $datos);
    
        return response()->json(['message' => 'Evento actualizado correctamente']);
    }

    public function destroy($id){
        $evento = Calendario::find($id);

        if (!$evento) {
            return response()->json(['success' => false, 'message' => 'Evento no encontrado.'], 404);
        }
    
        $evento->delete();

        $userEmail = Auth::user()->email;

        $datos = [];
        $datos['fecha']     = $evento->entry_date;
        $datos['cliente']   = $evento->saclie->descrip ?? $evento->lead;
        $datos['consultor'] = $evento->consultor->nombre;
        $datos['tipo'] = 'anulacion';

        $emailEnviado = $this->mail('Eliminación de Evento en Calendario', $userEmail, $datos);

    
        return response()->json(['success' => true, 'message' => 'Evento eliminado correctamente.']);
    }

    public function reminderTest(){
        $events = Calendario::whereIn('id', [37, 39, 44, 45])
        ->where('reminder_sent', false)
        ->get();

        foreach ($events as $event) {
            $datos = [];
            $datos['title'] = $event->title;
            $datos['fecha'] = $event->entry_date;
            $datos['fechaFinal'] = $event->departure_date;
            $datos['cliente']   = $event->saclie->descrip ?? $evento->lead;
            $datos['consultor'] = $event->consultor->nombre;
            $datos['codconsultor'] = $event->consultor->codconsultor;
            $datos['codclie'] = $event->saclie->codclie;
            $datos['interactionType'] = $event->interactionType;
            $datos['description'] = $event->description;
            $datos['tipo'] = 'recordatorio';

            // Marcar evento como recordado
            $event->reminder_sent = true;
            $event->save();

            $emailEnviado = $this->mail('⏳ Recordatorio de Evento Programado en Calendario', $event->user->email, $datos);

        }

    }

    protected function mail($asunto, $userEmail, $datos)
    {
        try {    
            return Mail::send('mails.calendario', ['datos' => $datos], function ($message) use ($asunto, $userEmail) {
                $message->from('no-responder@saintnetweb.info', env('APP_NAME'))
                    ->to([$userEmail, 'info@saintnet.net', 'hectorgabrieldm@hotmail.com'])
                    ->subject($asunto);
            });
        
        } catch (\Exception $e) {
            Log::error("Error al enviar correo: " . $e->getMessage()); // Registrar error
            return response()->json(['error' => "Error: " . $e->getMessage()]); // Fallo
        }
    }
}
