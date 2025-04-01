<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{CxC, Wallet, TipoMoneda, Saclie, DetalleCxC};
use Illuminate\Support\Facades\DB;
use Auth;
use Mail;

class CxCController extends Controller
{
    public function index(){
		$wallet = Wallet::where('inactivo', false)->get();  
		$tipomoneda = TipoMoneda::where('codtipomoneda', 2)->where('inactivo', false)->get();
		$saclie = Saclie::orderby('descrip','asc')->get();

        return view('cxc', [
            'wallet' => $wallet,
            'tipomoneda' => $tipomoneda, 
            'saclie' => $saclie
        ]);
    }

    public function store(Request $request){
        $codclie = $request->input('codclie');
		$codusuario = Auth::user()->codusuario;
		$codwallet = $request->input('codwallet');
		$codmoneda = $request->input('codmoneda');
		$codtipomoneda = $request->input('codtipomoneda');
		$cliente = $request->input('cliente');
		$nfecha = new \DateTime(date(str_replace('/','-',$request['fecha'])));
		$fecha = $nfecha->format('Y-m-d H:i:s');
		$monto = str_replace(',','.',str_replace('.','',$request->input('monto')));
		$observacion = $request->input('observacion');
		
        $saclie = Saclie::where('codclie', $codclie)->first();
								
		$reg = new CxC();
		$reg->codwallet	= $codwallet;			
		$reg->fecha	= date('Y-m-d');			
		$reg->codmoneda	= $codmoneda;			
		$reg->codtipomoneda	= $codtipomoneda;		
		$reg->codclie = $codclie;			
		$reg->cliente = $saclie->rif . ' | '. $saclie->descrip;			
		$reg->monto			= $monto;			
		$reg->codusuario	= $codusuario;	
		$reg->observacion	= $observacion;	
		$reg->save();
				
		$response = ["success" => true, "data" => $reg];
				
		return $response;
    }

    public function balance(Request $request)
    {
        $codusuario = Auth::user()->codusuario;
        $codwallet = $request->codwallet;
        
        //Todas las cxc de este wallet
        $cxcs = CxC::where('codwallet', $codwallet)
        ->where('codmoneda', 2)
        ->whereColumn('monto', '>', 'abono')
        ->orderByRaw('monto - abono ASC') // Ordenar por saldo restante
        ->withCount('detallecxc')
        ->get()
        ->map(function ($cxc) {
            $cxc->saldo = $cxc->monto - $cxc->abono; // Calcular saldo individual por registro
            return $cxc;
        });
    
        // Obtener las CxC con los datos necesarios por cliente
        $saldosPorCliente = CxC::selectRaw('cxc.*, SUM(monto) as total_monto, SUM(abono) as total_abono')
        ->where('codwallet', $codwallet)
        ->where('codmoneda', 2)
        ->whereColumn('monto', '>', 'abono')
        ->groupBy('codclie', 'cliente')
        ->orderByRaw('SUM(monto) - SUM(abono) ASC')
        ->get()
        ->map(function ($cxc) {
            $cxc->saldo = $cxc->total_monto - $cxc->total_abono;
            return $cxc;
        });

    
        // Calcular saldo total
        $saldo = $saldosPorCliente->sum('saldo');
    
        // Generar la vista con los datos obtenidos
        $saldosPorClienteHtml = view('cxc.cxcItem', compact('saldosPorCliente'))->render();
        $cxcHtml = view('cxc.table', compact('cxcs'))->render();
    
        return response()->json([
            'saldosPorClienteHtml' => $saldosPorClienteHtml,
            'cxcHtml' => $cxcHtml,
            'saldo' => number_format($saldo, 2, ',', '.'),
        ]);
    }

    public function updateColor(Request $request){
        $cxc = CxC::where('codcxc', $request->codcxc)->first();
        $cxc->color = $request->color;
        $cxc->save();

        return response()->json([
            'success' => true,
            'message' => 'color cambiado con exito'
        ]);
    }

    public function getDetailsByClient(Request $request){
        $codclie = $request->codclie;

        if (!$codclie) {
            return response()->json(['error' => 'Código de cliente no recibido', 'codclie' => $codclie], 400);
        }

        $cxcs = CxC::query()
        ->where('codclie', $codclie)
        ->where('codmoneda', 2)
        ->whereColumn('monto', '>', 'abono')
        ->orderByRaw('monto - abono ASC')
        ->get()
        ->map(function ($cxc) {
            $cxc->saldo = $cxc->monto - $cxc->abono;
            return $cxc;
        });

        $saclie = Saclie::where('codclie', $codclie)->first();
        
        
        $cxcDetails = view('cxc.tableCxcDetails', compact('cxcs'))->render();
        
        return response()->json([
            'cxcDetails' => $cxcDetails,
            'cxcs' => $cxcs,
            'saclie' => $saclie
        ]);
    }   

	public function registrarCxcAbono(Request $request){
		
		$codusuario = Auth::user()->codusuario;
		
		$codwallet = $request->get('codwallet');
		$codcxc = $request->get('codcxc');
		$montodeuda = $request->get('montodeuda');
		$codtipomoneda = $request->get('cmbcodtipomonedaabono');
		$descripcion = $request->get('descripcionabono');
		$montoabono = str_replace(',','.',str_replace('.','',$request->get('montoabono')));
        $file = $request->input('file');
				
		if($montoabono > $montodeuda){
			$response = ["success" => false, "data" => ''];
		}else{
			$reg = new DetalleCxC();
			$reg->codcxc = $codcxc;	
			$reg->codtipomoneda	= $codtipomoneda;
			$reg->fecha	= date('Y-m-d');			
			$reg->monto = $montoabono;			
			$reg->descripcion = $descripcion;	
			$reg->file = $file;	
			$reg->codusuario = $codusuario;	
			$reg->save();
			
			$cxc = Cxc::where('codcxc','=',$codcxc)->first();
			
			if($cxc){
				$cxc->abono = $cxc->abono + $montoabono;
				$cxc->save();
			}
			
			if($cxc->codlic > 0){
			   if($montoabono == $montodeuda){ 
    			    $regLig = Control_Licencia::where('id', $cxc->codlic)->first();
            		if(!empty($regLig)){
            		    $regLig->pagada = 1;
            		    $regLig->save();
            		    $email = $this->enviaremail('PAGO A LICENCIA Nro.: '.$cxc->codlic.' Fecha: '.date("d/m/Y H:i:s a"),$email,$datos,"mails.addCxC");
            		}  
			   }
			}
			
			if($cxc->codlicotras > 0){
			   if($montoabono == $montodeuda){ 
    			    $regLig = ControlLicenciaOtras::where('id', $cxc->codlicotras)->first();
            		if(!empty($regLig))
            		{
            		    $regLig->pagada = 1;
            		    $regLig->save();
            		    $email = $this->enviaremail('PAGO A LICENCIA OTRAS Nro.: '.$cxc->codlicotras.' Fecha: '.date("d/m/Y H:i:s a"),$email,$datos,"mails.addCxC");
            		}  
			   }
			}
			$response = ["success" => true, "data" => $reg];
		}
		return $response;
	}

    public function getAbonosDetails(Request $request){
        $codcxc = $request->codcxc;
        $abonos = DetalleCxC::where('codcxc', $codcxc)->get();

        $html = view('cxc.getAbonosDetailsTable', compact('abonos'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function reportes(Request $request){
        $abonos = DetalleCxC::byDateRange($request->from, $request->until)
        ->select(DB::raw('detallecxc.file, detallecxc.fecha,detallecxc.codcxc,cxc.cliente,cxc.observacion,moneda.nombre as moneda,tipomoneda.nombre as tipomoneda,detallecxc.descripcion,detallecxc.monto,usuario.nombre as usuario'))
        ->join('tipomoneda','tipomoneda.codtipomoneda','=','detallecxc.codtipomoneda')
        ->join('moneda','moneda.codmoneda','=','tipomoneda.codmoneda')
        ->join('cxc','cxc.codcxc','=','detallecxc.codcxc')
        ->join('usuario','usuario.codusuario','=','detallecxc.codusuario')
        ->orderBy('codcxc','desc')
        ->get();

        return view('cxc.reportes', [
            'abonos' => $abonos,
            'requestFrom' => $request->from,
            'requestUntil' => $request->until,
        ]);
    }

    public function destroy($codcxc)
    {
        try {
            $cxc = CxC::find($codcxc);
    
            if (!$cxc) {
                return response()->json(["success" => false, "message" => "Cuenta por cobrar no encontrada"]);
            }
    
            // Guardar IDs antes de eliminar
            $idLicencia = $cxc->codlic ?? null;
            $idLicOtras = $cxc->codlicotras ?? null;
    
            // Eliminar la cuenta por cobrar
            $cxc->delete();
    
            // Manejo de Control_Licencia si existe
            if ($idLicencia) {
                $licencia = Control_Licencia::find($idLicencia);
                if ($licencia) {
                    $licencia->eliminada = 1;
                    $licencia->save();
    
                    $this->enviaremail(
                        'SE ELIMINÓ LICENCIA Nro.: ' . $idLicencia . ' Fecha: ' . now(),
                        "",
                        "",
                        "mails.addCxC"
                    );
                }
            }
    
            // Manejo de ControlLicenciaOtras si existe
            if ($idLicOtras) {
                $licenciaOtras = ControlLicenciaOtras::find($idLicOtras);
                if ($licenciaOtras) {
                    $licenciaOtras->eliminada = 1;
                    $licenciaOtras->save();
    
                    $this->enviaremail(
                        'SE ELIMINÓ LICENCIA OTRAS Nro.: ' . $idLicOtras . ' Fecha: ' . now(),
                        "",
                        "",
                        "mails.addCxC"
                    );
                }
            }
    
            return response()->json(["success" => true, "message" => "Cuenta por cobrar eliminada correctamente"]);
    
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => "Error al eliminar: " . $e->getMessage()]);
        }
    }
    

    protected function enviaremail($asunto, $emaildestino, $datos, $formato){
        try {    

			 $EmailEnviado = Mail::send($formato, [
			    'datos' => $datos
            ], 
            function ($message) use ($datos, $asunto, $emaildestino) {

				 $message->from('no-responder@saintnetweb.info','Ds Pegaso');
				 $message->subject($asunto);
				 $message->to($emaildestino);
			 });
             
            if(is_object($EmailEnviado)) 
            {
                $fecha=date("Y-m-d H:i:s",time()+1800);
                $enviados=1;
				return 1;
            }elseif(is_string($EmailEnviado)){
                if ($EmailEnviado=='1') 
                {
                    return 1;
                } else {
                    return 0;
                }
            }else{
                return $EmailEnviado;
            }
        }catch (\Exception $e){
           // return $e->getMessage();
            return "0";
        }
    }
    
}
