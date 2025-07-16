<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Saclie, Ticket};

class TicketsController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::with('saclie')
            ->bySaclie($request->codclie)
            ->byStatus($request->status)
            ->byDateRange($request->from, $request->until)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $saclie = Saclie::orderBy('descrip', 'asc')->where('activo', true)->get();
    
        return view('tickets.index', [
            'saclie' => $saclie,
            'tickets' => $tickets,
            'request' => $request // para mantener valores seleccionados
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'codclie' => 'required|exists:saclie,codclie',
            'status' => 'required|in:PENDIENTE,COMPLETADO,ANULADO,RECHAZADO',
            'observacion' => 'nullable|string',
        ]);
    
        $ticket = new Ticket();
        $ticket->codclie = $request->codclie;
        $ticket->status = $request->status;
        $ticket->solicitud = $request->solicitud;
        $ticket->save();
    
        return redirect()->back()->with('message', 'Ticket registrado correctamente.');
    }

    public function edit($id){
        $ticket = Ticket::find($id);
        $saclie = Saclie::orderby('descrip', 'asc')->where('activo', true)->get();

        return view('tickets.edit', [
            'saclie' => $saclie,
            'ticket' => $ticket
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validación de los datos recibidos
        $request->validate([
            'codclie' => 'required|exists:saclie,codclie',
            'status' => 'required|in:PENDIENTE,COMPLETADO,ANULADO,RECHAZADO',
            'solicitud' => 'nullable|string|max:1000',
        ]);

        // Buscar el ticket
        $ticket = Ticket::findOrFail($id);

        // Actualizar los campos
        $ticket->codclie = $request->codclie;
        $ticket->status = $request->status;
        $ticket->solicitud = $request->solicitud;
        $ticket->save();

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('message', 'Ticket actualizado exitosamente.');
    }
}
