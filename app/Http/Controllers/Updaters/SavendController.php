<?php

namespace App\Http\Controllers\Updaters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\{Savend, CxC};

class SavendController extends Controller
{
    public function SavendWs(Request $request)
	{
		try	{
            $data = Array();
            $datosReq = $request->all();
                   
            for ($i = 0; $i < count($datosReq); $i++) {
                $data['serial'][$i] = $datosReq[$i]['Serial'];
                $data['codvend'][$i] = $datosReq[$i]['CodVend'];
                $data['descrip'][$i] = $datosReq[$i]['Descrip'];
                $data['activo'][$i] = $datosReq[$i]['Activo'];
				$data['evento'][$i]  = @$datosReq[$i]['Evento'];
            }

            for ($i=0; $i < count($data['serial']); $i++)
            {
                $pgConsulSQL = Savend::where('serial', '=', $data['serial'][$i])
                    ->where('codvend','=',$data['codvend'][$i])
                    ->first();

				if($data['evento'][$i]=='D')
				{
					$pgConsulSQL->delete();
					
				}else{

					if(empty($pgConsulSQL)) {
						$pgSQL = new Savend();
						$pgSQL->serial=$data['serial'][$i];
						$pgSQL->codvend=$data['codvend'][$i];
						$pgSQL->descrip=$data['descrip'][$i];
						$pgSQL->activo=$data['activo'][$i];
						$pgSQL->save();
					}else{

						$pgConsulSQL->descrip=$data['descrip'][$i];
						$pgConsulSQL->activo=$data['activo'][$i];
						$pgConsulSQL->save();

					}
				}

            }
            $response = ["success" => "COD_000","mensaje"=>"Registro Exitoso..."];
        }catch (\Exception $e){
            $response = ["success" => "CODE_000","mensaje"=>$e->getMessage().' Linea: '.$e->getLine()];
        }
        return response()->json($response);
	}
	
}
