<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wallet, Wallet_Registro, Wallet_Det, Moneda, TipoMoneda};
use Illuminate\Support\Facades\DB;
use Mail;
use Auth;
use Log;

class WalletController extends Controller
{
    public function index(){
        $wallet = Wallet::where('cuenta', true)
        ->where('inactivo', false)
        ->get();
        
        return view('wallet', compact('wallet'));
    }

    public function store(Request $request){
        $codusuario = Auth::user()->codusuario;
		$codwallet = $request->get('codwallet');
		$codmoneda = $request->get('codmoneda');
		$codtipomoneda = $request->get('codtipomoneda');
		$codoperacion = $request->get('codoperacion');
		$descripcion = $request->get('descripcion');
		$nfecha = new \DateTime(date(str_replace('/','-',$request['fecha'])));
		$fecha = $nfecha->format('Y-m-d');
		$monto = str_replace(',','.',str_replace('.','',$request->get('monto')));
		$signo = 1;
		if($codoperacion == 1){
			$signo = '-1';
		}

        $w = new Wallet_Registro();
		$w->codwallet = $codwallet;			
		$w->fecha = date('Y-m-d');			
		$w->fechapag = $fecha;			
		$w->codmoneda = $codmoneda;			
		$w->codtipomoneda = $codtipomoneda;			
		$w->codoperacion = $codoperacion;			
		$w->codusuario = $codusuario;			
		$w->descripcion	= $descripcion;			
		$w->monto = $monto;
		$w->signo = $signo;
		$w->save();

        $regEmail = DB::table('wallet_registro')
        ->select(DB::raw('wallet_registro.id,wallet_registro.fecha,wallet_registro.fechapag,moneda.nombre as moneda,tipomoneda.nombre as tipomoneda,wallet_registro.codoperacion,users.name as usuario,wallet_registro.descripcion,(wallet_registro.monto*wallet_registro.signo) as monto,wallet.nombre as wallet'))
        ->join('moneda','moneda.codmoneda','=','wallet_registro.codmoneda')
        ->join('tipomoneda','tipomoneda.codtipomoneda','=','wallet_registro.codtipomoneda')
        ->join('wallet','wallet.codwallet','=','wallet_registro.codwallet')
        ->join('users','users.codusuario','=','wallet_registro.codusuario')
        ->where('wallet_registro.id','=',$w->id)
        ->orderby('id','desc')
        ->get();
    
        if(count($regEmail)>0){
            $i=0;
            foreach($regEmail as $reg){

                $datos[$i]=$reg;
                $i++;
            }
            $email = $this->enviaremail('Registro Wallet Fecha: ' . date("d/m/Y H:i:s a"),'info@saintnet.net', $datos);			
        }

        $detSql = Wallet_Det::where('codwallet','=',$codwallet)
                ->where('codmoneda','=',$codmoneda)
                ->first();

        if(!empty($detSql)){
            $detSql->saldo = ($detSql->saldo + $monto);
            $detSql->save();
        }

        return response()->json([
            'message' => "Registro creado con exito"
        ]);
    }

    public function destroy(Request $request){
		$id = $request->get('id');
		$pgSql= Wallet_Registro::where('id', $id)->first();
		
		$codwallet = $pgSql->codwallet;
		$codmoneda = $pgSql->codmoneda;
		$codoperacion = $pgSql->codoperacion;
		$monto = $pgSql->monto;
						
		if($codoperacion == 2){
			$monto = ($monto * -1);
		}
		
		$regEmail = DB::table('wallet_registro')
				->select(DB::raw('wallet_registro.id,wallet_registro.fecha,wallet_registro.fechapag,moneda.nombre as moneda,tipomoneda.nombre as tipomoneda,wallet_registro.codoperacion,usuario.nombre as usuario,wallet_registro.descripcion,(wallet_registro.monto*wallet_registro.signo) as monto,wallet.nombre as wallet'))
                ->join('moneda','moneda.codmoneda','=','wallet_registro.codmoneda')
                ->join('tipomoneda','tipomoneda.codtipomoneda','=','wallet_registro.codtipomoneda')
                ->join('wallet','wallet.codwallet','=','wallet_registro.codwallet')
                ->join('users','users.codusuario','=','wallet_registro.codusuario')
                ->where('wallet_registro.id','=',$id )
				->orderby('id','desc')
				->get();
			
			if(count($regEmail)>0){
				$i=0;
				foreach($regEmail as $reg){
					
					$datos[$i]=$reg;
					$i++;
				}
				$email = $this->enviaremail('Se eliminó registro Wallet Fecha: '.date("d/m/Y H:i:s a"), 'info@saintnet.net', $datos);			
			}
	
		$deleteSql = Wallet_Registro::where('id', $id)->delete();
			
				
		$consultaSql = Wallet_Det::where('codwallet','=',$codwallet)
				->where('codmoneda','=',$codmoneda)
				->first();
				
		if(!empty($consultaSql)){
			$consultaSql->saldo = ($consultaSql->saldo + $monto);
			$consultaSql->save();
		}
		
		if(!empty($consultaSql)){		
			$response = ["success" => true, "data" => $consultaSql,"id" => $id];
				 
		}else{
			$response = ["success" => true, "data" => ''];
			
		}
		return response()->json($response);
		
	}

    public function getWalletData(Request $request){
        $codusuario = Auth::user()->codusuario;
		$master = Auth::user()->master;
		$codwallet = $request->input('codwallet');

        if(($master || in_array($codwallet, [2, 5, 6])) || $codusuario == 10){
            $wallet_registros = Wallet_Registro::where('codwallet', $codwallet)
            ->orderBy('id', 'desc')
            ->get();

            $monedas = DB::table('moneda')
            ->select(DB::raw('moneda.codmoneda,moneda.nombre,moneda.siglas, moneda.color,moneda.decimales'))
            ->join('wallet_det','wallet_det.codmoneda','=','moneda.codmoneda')
            ->join('wallet','wallet.codwallet', 'wallet_det.codwallet')
            ->where('wallet.codwallet', $codwallet)
            ->where('wallet_det.inactivo', false)
            ->get();

        }else{
            $wallet_registros = Wallet_Registro::where('codwallet', $codwallet)
            ->where('codmoneda', 1)
            ->orderBy('id', 'desc')
            ->get();

            $monedas = DB::table('moneda')
            ->select(DB::raw('moneda.codmoneda,moneda.nombre,moneda.siglas, moneda.color,moneda.decimales'))
            ->join('wallet_det','wallet_det.codmoneda','=','moneda.codmoneda')
            ->join('wallet','wallet.codwallet','=','wallet_det.codwallet')
            ->where('wallet.codwallet', $codwallet)
            ->where('wallet_det.inactivo', false)
            ->where('moneda.codmoneda', 1)
            ->get();

        }

        if($monedas->count() > 0){
			foreach($monedas as $wm){
				$consulta = DB::table('wallet_registro')
				->select(DB::raw('wallet_registro.id,wallet_registro.codwallet,wallet_registro.codmoneda,SUM((wallet_registro.monto*wallet_registro.signo)) as monto,tipomoneda.nombre as tipomoneda,wallet_registro.codtipomoneda'))
				->join('moneda','wallet_registro.codmoneda','=','moneda.codmoneda')
				->join('tipomoneda','wallet_registro.codtipomoneda','=','tipomoneda.codtipomoneda')
				->join('users','wallet_registro.codusuario','=','users.codusuario')
				->where('wallet_registro.codwallet','=',$codwallet)
				->where('wallet_registro.codmoneda','=',$wm->codmoneda)
				->orderby('wallet_registro.id','desc')
				->groupby('codtipomoneda')
				->get();
				
				$valor = 0;
				
				foreach($consulta as $c){
					$valor = $valor + $c->monto;
					$c->monto= number_format($c->monto,2,',','.');
				}
				
				$wm->arreglo = $consulta;
				$wm->saldo= number_format($valor, $wm->decimales,',','.');
			}
        }

        $data = [];
        $totalRecords = (clone $wallet_registros)->count();

        foreach($wallet_registros as $w){
            $row = [];

            $row[] = "<p>{$w->id}</p>";
            $row[] = "<p>" . \Carbon\Carbon::parse($w->fecha)->format('d/m/Y') . "</p>";
            $row[] = "<p>" . \Carbon\Carbon::parse($w->fechapag)->format('d/m/Y') . "</p>";
            $row[] = "<p>{$w->descripcion}</p>";
            $row[] = $w->codoperacion == 1 ? '<span class="badge badge-danger">Débito</span>' : '<span class="badge badge-success">Crédito</span>';
            $row[] = "<p>{$w->moneda->nombre}</p>";
            $row[] = "<p>{$w->tipomoneda->nombre}</p>";
            $row[] = '<p>' . number_format($w->monto, 2, ',', '.') . '</p>';
            $row[] = view('wallet.actions', compact('w'))->render();
            $data[] = $row;
        }

        $html = view('wallet.saldoPorMoneda', ['monedas' => $monedas])->render();
        $selectMoneda = view('wallet.selectMoneda', ['monedas' => $monedas])->render();

        return response()->json([
            "sEcho" => 1,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            'aaData' => $data,
            'html' => $html,
            'selectMoneda' => $selectMoneda
        ]);
    }

    public function getTipoMonedas(Request $request){
        $codmoneda = $request->codmoneda;
        $tipomonedas = TipoMoneda::where('codmoneda', $codmoneda)->get();
        $selectTipoMoneda = view('wallet.selectTipoMoneda', ['tipomonedas' => $tipomonedas])->render();

        return response()->json([
            'selectTipoMoneda' => $selectTipoMoneda
        ]);
    }

    protected function enviaremail($asunto, $emaildestino, $datos){
        try {
            return Mail::send('mails.addWallet', ['datos' => $datos], function ($message) use ($asunto, $emaildestino) {
                $message->from('no-responder@saintnetweb.info', env('APP_NAME'))
                ->subject($asunto)
                ->to([$emaildestino, 'jfarfan@saintnet.net', 'hectorgabrieldm@hotmail.com']);
            });
        } catch (\Exception $e) {
            return Log::error("Error al enviar correo: " . $e->getMessage()); // Registrar error
        }
    }

}
