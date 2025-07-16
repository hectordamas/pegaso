<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Calendario;
use Carbon\Carbon;
use Mail;
use Log;
use Auth;

class SendEventReminder extends Command
{
    protected $signature = 'event:sendReminder';
    protected $description = 'Enviar recordatorio de eventos programados';

    public function handle()
    {
        $events = Calendario::where('entry_date', '>=', Carbon::now()->subMinutes(30)->toDateTimeString())
        ->where('reminder_sent', false)
        ->get();

        foreach ($events as $event) {
            $datos = [];
            $datos['fecha']     = $event->entry_date;
            $datos['fechaFinal']     = $event->departure_date;
            $datos['cliente']   = $event->saclie->descrip ?? $event->lead;
            $datos['consultor'] = $event->consultor->nombre;
            $datos['codconsultor'] = $event->consultor->codconsultor;
            $datos['codclie'] = $event->saclie->codclie ?? $event->lead;
            $datos['interactionType'] = $event->interactionType;
            $datos['description'] = $event->description;
            $datos['tipo'] = 'recordatorio';

            // Marcar evento como recordado
            $event->reminder_sent = true;
            $event->save();

            if ($event->user && $event->user->email) {
                $emailEnviado = $this->mail('⏳ Recordatorio de Evento Programado en Calendario', $event->user->email, $datos);
            }
        }

        $this->info('Recordatorios enviados correctamente.');
    }

    
    protected function mail($asunto, $userEmail, $datos)
    {
        try {    
            return Mail::send('mails.calendario', ['datos' => $datos], function ($message) use ($asunto, $userEmail) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                    ->to([$userEmail, 'info@saintnet.net'])
                    ->subject($asunto);
            });
        
        } catch (\Exception $e) {
            Log::error("Error al enviar correo: " . $e->getMessage()); // Registrar error
            return response()->json(['error' => "Error: " . $e->getMessage()]); // Fallo
        }
    }
}
