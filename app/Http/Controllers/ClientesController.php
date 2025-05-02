<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Saclie, AtencionCliente, Calendario, CxC, EntradaEquipos, Visita, Llamadas, Safact};

class ClientesController extends Controller
{
    public function index(){
		$saclie = Saclie::orderby('descrip', 'asc')->get();

        return view('clientes', [
            'saclie' => $saclie
        ]);
    }

    public function show($id){
        $saclie = Saclie::find($id);
        $codclie = $saclie->codclie;

        return view('clientes.show', [
            'saclie' => $saclie,
        ]);
    }
}
