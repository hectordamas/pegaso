<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wallet, Wallet_Registro, Wallet_Det, Moneda};
use Illuminate\Support\Facades\DB;
use Mail;
use Auth;


class WalletController extends Controller
{
    public function index(){
        $wallet = Wallet::where('cuenta', true)
        ->where('inactivo', false)
        ->get();
        
        return view('wallet', compact('wallet'));
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
				->join('usuario','wallet_registro.codusuario','=','usuario.codusuario')
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
}
