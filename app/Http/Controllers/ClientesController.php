<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Saclie, AtencionCliente, Calendario, CxC, EntradaEquipos, Visita, Llamadas, Safact};

class ClientesController extends Controller
{
    public function index(Request $request){
		$saclie = Saclie::orderby('descrip', 'asc')->where('activo', true)->get();
        $client = $request->client;

        return view('clientes', [
            'saclie' => $saclie,
            'client' => $client
        ]);
    }

    public function show($id){
        $saclie = Saclie::find($id);
        $codclie = $saclie->codclie;

        return view('clientes.show', [
            'saclie' => $saclie,
        ]);
    }

    public function calendario($codclie){
        $saclie = Saclie::where('codclie', $codclie)->first();

        return view('calendario.cliente', [
            'saclie' => $saclie,
            'calendario' => $saclie->calendario()->with(['saclie', 'consultor'])->orderBy('id', 'desc')->get()
        ]);
    }
}
