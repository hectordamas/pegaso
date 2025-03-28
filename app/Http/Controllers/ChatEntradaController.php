<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EntradaEquipos, ChatEntrada};
use Carbon\Carbon;
use Auth;
use Mail;
use Log;

class ChatEntradaController extends Controller
{
    public function cargarChats($codentrada)
    {
        // Obtener la entrada por su codentrada
        $item = EntradaEquipos::where('codentrada', $codentrada)->first();

        // Verificar si la entrada existe
        if (!$item) {
            return response()->json(['html' => 'Entrada no encontrada.']);
        }

        // Obtener los mensajes del chat de esta entrada
        $chats = $item->chatentradas; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatentradas', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat

        // Devolver el HTML generado
        return response()->json(['html' => $html]);
    }

    public function sendMessage(Request $request) {
        // Validar que el mensaje no esté vacío
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        // Crear el mensaje en la base de datos
        $chat = new ChatEntrada();
        $chat->codusuario = Auth::user()->codusuario;
        $chat->codentrada = $request->codentrada;
        $chat->mensaje = $request->mensaje;
        $chat->fechayhora = now();
        $chat->save();

        $item = EntradaEquipos::where('codentrada', $request->codentrada)->first();
        $chats = $item->chatentradas; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatentradas', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat

        /*$email = $this->mail(
            'Registro de Chat en "Entrada de Equipos" #'. $request->codentrada . ' - Fecha: ' . date("d/m/Y H:i:s a"),
            'info@saintnet.net',
            $chats,
            $item->saclie,
            "mails.entradaequipos"
        );	*/

        // Devolver el HTML generado
        return response()->json(['html' => $html]);
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
}
