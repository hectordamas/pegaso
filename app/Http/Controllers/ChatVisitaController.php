<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ChatVisita, Visita};
use Auth;

class ChatVisitaController extends Controller
{
    public function cargarChats($codvisita)
    {
        $item = Visita::find($codvisita);

        if (!$item) {
            return response()->json(['html' => 'Proyecto no encontrado.']);
        }

        // Obtener los mensajes del chat de esta entrada
        $chats = $item->chatvisita; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatvisita', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat

        // Devolver el HTML generado
        return response()->json(['html' => $html]);
    }

    public function sendMessage(Request $request) {
        // Validar que el mensaje no esté vacío
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        // Crear el mensaje en la base de datos
        $chat = new ChatVisita();
        $chat->codusuario = Auth::user()->codusuario;
        $chat->codvisita = $request->codvisita;
        $chat->mensaje = $request->mensaje;
        $chat->fechayhora = now();
        $chat->save();

        $item = Visita::find($request->codvisita);
        $chats = $item->chatvisita; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatllamada', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat

        return response()->json(['html' => $html]);
    }

}
