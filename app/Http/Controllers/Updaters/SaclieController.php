<?php

namespace App\Http\Controllers\Updaters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Saclie;
use App\Model\CxC;

class SaclieController extends Controller
{
    public function SaclieWs(Request $request)
	{
		$data = Array();
		$datosReq = $request->all(); 
	        
	  //dd($datosReq);
      
		for ($i = 0; $i < count($datosReq); $i++) 
		{
			$data['codclie'][$i] = @$datosReq[$i]['CodClie'];
			$data['descrip'][$i] = @$datosReq[$i]['Descrip'];
			$data['rif'][$i]     = @$datosReq[$i]['Rif'];
			$data['evento'][$i]     = @$datosReq[$i]['Evento'];
		}
		
		//return $data;
		
		for ($i=0; $i < count($data['codclie']); $i++) 
		{
			$pgConsulSQL = Saclie::where('codclie','=',$data['codclie'][$i])->first();
			
			if($data['evento'][$i]=='D')
			{
				$pgConsulSQL->delete();
				
			}else{
						
    			if(empty($pgConsulSQL) /*&& count($pgConsulSQL) == 0*/) {
    				$pgSQL = new Saclie();
    				$pgSQL->codclie=@$data['codclie'][$i];
    				$pgSQL->descrip=@$data['descrip'][$i];
    				$pgSQL->rif=@$data['rif'][$i];
    				$pgSQL->fechaupdate= date('Y-m-d H:i:s');
    	    		$pgSQL->save();	
    			}else{
    				$pgConsulSQL->descrip=@$data['descrip'][$i];
    				$pgConsulSQL->rif=@$data['rif'][$i];
    				$pgConsulSQL->fechaupdate= date('Y-m-d H:i:s');
    				$pgConsulSQL->save();
    		
    			    $cxc = CxC::where('codclie','=',$data['codclie'][$i])->update([ 'cliente' => @$data['rif'][$i]." | ".@$data['descrip'][$i] ]);
    			}
			}
		}
		$response = ["success" => true];
		return response()->json($response);	
	}
	
	public function SaclieDsWs(Request $request)
	{
	
		$codclie = $request->get('CodClie');
		$descrip = $request->get('Descrip');
		$rif = $request->get('Rif');
	  
		$pgConsulSQL = Saclie::where('codclie','=',$codclie)->first();
		
		$descrip = str_replace("'", "", $descrip);
					
		if(empty($pgConsulSQL))
		{
			$pgSQL = new Saclie();
			$pgSQL->codclie=@$codclie;
			$pgSQL->descrip=@$descrip;
			$pgSQL->rif=@$rif;
			$pgSQL->fechaupdate= date('Y-m-d H:i:s');
    		$pgSQL->save();	
		}else{
			$pgConsulSQL->descrip=@$descrip;
			$pgConsulSQL->rif=@$rif;
			$pgConsulSQL->fechaupdate= date('Y-m-d H:i:s');
			$pgConsulSQL->save();
			
			$cxc = CxC::where('codclie','=',$codclie)->update([ 'cliente' => @$rif." | ".@$descrip ]);
		}
		
		$response = ["success" => true];
		return response()->json($response);	
	}
	
	public function sanear_string($string)
    {
     
        $string = trim($string);
     
        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );
     
        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );
     
        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );
     
        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );
     
        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );
     
        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );
     
        //Esta parte se encarga de eliminar cualquier caracter extraño
         $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             "."),
            '',
            $string
        );
     
     
        return $string;
    }
}
