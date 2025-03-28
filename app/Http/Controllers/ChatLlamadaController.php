<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ChatLlamada, Llamadas};
use Auth;

class ChatLlamadaController extends Controller
{
    public function cargarChats($codllamada)
    {
        $item = Llamadas::find($codllamada);

        if (!$item) {
            return response()->json(['html' => 'Proyecto no encontrado.']);
        }

        // Obtener los mensajes del chat de esta entrada
        $chats = $item->chatllamada; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatllamada', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat

        // Devolver el HTML generado
        return response()->json(['html' => $html]);
    }

    public function sendMessage(Request $request) {
        // Validar que el mensaje no esté vacío
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        // Crear el mensaje en la base de datos
        $chat = new ChatLlamada();
        $chat->codusuario = Auth::user()->codusuario;
        $chat->codllamada = $request->codllamada;
        $chat->mensaje = $request->mensaje;
        $chat->fechayhora = now();
        $chat->save();

        $item = Llamadas::find($request->codllamada);
        $chats = $item->chatllamada; // O el método adecuado para obtener los mensajes del chat

        // Generar el HTML para los mensajes
        $html = view('chat.chatllamada', compact('chats'))->render(); // Cargar la vista que contiene los mensajes del chat

        return response()->json(['html' => $html]);
    }

}
