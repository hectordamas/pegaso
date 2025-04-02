<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\VerifyPermissions;
use App\Models\{AtencionCliente, Saclie, Consultor, EstatusAt};
use Auth;
use Validator;

class AtencionClienteController extends Controller
{
	use VerifyPermissions;

	public function index(Request $request){
		// Recoge los filtros desde el request
		$codconsultor = $request->input('consultor');
		$codclie = $request->input('client');
		$codestatus = $request->input('estatus');
		$from = $request->input('from');
		$until = $request->input('until');
	
		// Construye la consulta con los filtros
		$atencionClientes = AtencionCliente::query()
			->byConsultor($codconsultor)
			->bySaclie($codclie)
			->byDateRange($from, $until);
	
		// Si no se filtra por estatus, aplicar el filtro por defecto
		if (!$codestatus) {
			$atencionClientes->whereIn('codestatus', [1, 2]);
		} else {
			$atencionClientes->byStatus($codestatus);
		}

	
		// Ejecutar la consulta y obtener los resultados
		$atencionClientes = $atencionClientes->orderBy('id', 'desc')->get();

		if(!$this->hasPermissions('vertodo')){
			$atencionClientes = $atencionClientes->where('codusuario', Auth::id());
		}
	
		// Obtener datos auxiliares para los selects
		$saclie = Saclie::orderby('descrip', 'asc')->get();
		$consultors = Consultor::where('inactivo', false)->get();
		$estatusAt = EstatusAt::where('inactivo', false)->get();
	
		return view('atencioncliente', [
			'atencionClientes' => $atencionClientes,
			'saclie' => $saclie,
			'consultors' => $consultors,
			'estatusAt' => $estatusAt,
			'registra' => $this->hasPermissions('registra')
		]);
	}

    public function store(Request $request){

        $regla = Validator::make($request->all(),[
            'codclie' => 'required',
            'solicitud' => 'required',
            'codestatus' => 'required',
            'codconsultor' => 'required',
			'conexion' => 'required',
			'direccionconex' => 'required',
        ],
		$messages = [
			'required'	=> 'El campo :attribute es requerido',
			'numeric'   => 'EL campo :attribute debe contener solo números',
			'string' 	=> 'EL campo :attribute debe contener solo letras',
			'min'      	=> 'El campo :attribute debe contener al menos :min caracteres',
			'max'      	=> 'El campo :attribute debe contener un máximo de :max caracteres',
		]);
		
		if ($regla->fails()){
			foreach($regla->errors()->messages() as $error){
				$mensaje = $error;
			}            
			return redirect()->back()->withErrors($mensaje[0]."-4");
        }

		$codclie = $request->get('codclie');

		$regEmail = Saclie::where('codclie', $codclie)->first();

		$cliente = $request->get('cliente');
		$codestatus = $request->get('codestatus');
		$codconsultor = $request->get('codconsultor');
		$solicitud = $request->get('solicitud');
		$actividad = $request->get('actividad');
		$codusuario = Auth::user()->codusuario;

		$conexion = $request->get('conexion');
		$direccionconex = $request->get('direccionconex');
		
		$pgSql = new AtencionCliente();
		$pgSql->fecha = date('Y-m-d H:i:s'); 
		$pgSql->codclie	= $codclie; 
		$pgSql->codestatus = $codestatus; 
		$pgSql->solicitud = $solicitud; 
		$pgSql->codconsultor = $codconsultor; 
		$pgSql->actividad = $actividad; 
		$pgSql->codusuario = $codusuario; 
		$pgSql->email = $regEmail->email;
		$pgSql->conexion = $conexion;
		$pgSql->direccionconex = $direccionconex;
		$pgSql->save();

        return redirect()->back()->with('message', "Solicitud Registrada con éxito!");			

    }

    public function update(Request $request)
	{		
		$id	= $request->input('atencionClienteId');
		$codestatus	= $request->input('codestatus');
		$actividad	= $request->input('actividad');
						
		$pgSQL = AtencionCliente::find($id);
	
		if(!empty($pgSQL)){			
			$pgSQL->codestatus = $codestatus;
			$pgSQL->actividad = $actividad;
			$pgSQL->save();

			return redirect()->back()->with('message', 'Registro Actualizado correctamente.');
		}	
			
        return redirect()->back()->with('error', 'Ocurrió un error durante la actualización del estatus.');
		
	}
}
