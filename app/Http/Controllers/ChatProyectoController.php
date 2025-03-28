<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Safact, ChatProyecto};
use Auth;

class ChatProyectoController extends Controller
{
    public function cargarChats($codproyecto)
    {
        $item = Safact::find($codproyecto);

        if (!$item) {
            return response()->json(['html' => 'Proyecto no encontrado.']);
        }

        // Obtener los mensajes del chat de esta entrada
        $chats = $item->chatproyecto; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatproyecto', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat

        // Devolver el HTML generado
        return response()->json(['html' => $html]);
    }

    public function sendMessage(Request $request) {
        // Validar que el mensaje no esté vacío
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        // Crear el mensaje en la base de datos
        $chat = new ChatProyecto();
        $chat->codusuario = Auth::user()->codusuario;
        $chat->codproyecto = $request->codproyecto;
        $chat->mensaje = $request->mensaje;
        $chat->fechayhora = now();
        $chat->save();

        $item = Safact::find($request->codproyecto);
        $chats = $item->chatproyecto; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatproyecto', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat


        return response()->json(['html' => $html]);
    }

}
