<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Safact, ChatEntrega};
use Auth;

class ChatEntregaController extends Controller
{
    public function cargarChats($codentrega)
    {
        $item = Safact::find($codentrega);

        if (!$item) {
            return response()->json(['html' => 'Proyecto no encontrado.']);
        }

        // Obtener los mensajes del chat de esta entrada
        $chats = $item->chatentrega; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatentrega', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat

        // Devolver el HTML generado
        return response()->json(['html' => $html]);
    }

    public function sendMessage(Request $request) {
        // Validar que el mensaje no esté vacío
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        // Crear el mensaje en la base de datos
        $chat = new ChatEntrega();
        $chat->codusuario = Auth::user()->codusuario;
        $chat->codentrega = $request->codentrega;
        $chat->mensaje = $request->mensaje;
        $chat->fechayhora = now();
        $chat->save();

        $item = Safact::find($request->codentrega);
        $chats = $item->chatentrega; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatentrega', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat


        return response()->json(['html' => $html]);
    }

}
