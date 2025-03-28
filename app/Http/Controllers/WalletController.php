<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wallet, Wallet_Registro, Wallet_Det};
use Illuminate\Support\Facades\DB;
use Mail;
use Auth;


class WalletController extends Controller
{
    public function index(){
        $wallet = Wallet::where('cuenta',true)
        ->where('inactivo',false)
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
        }else{
            $wallet_registros = Wallet_Registro::where('codwallet', $codwallet)
            ->where('codmoneda', 1)
            ->orderBy('id', 'desc')
            ->get();
        }

        $data = [];

        foreach($wallet_registros as $w){
            $row = [];

            $row[] = "<p>{$w->id}</p>";
            $row[] = "<p>" . \Carbon\Carbon::parse($w->fecha)->format('d/m/Y h:i a') . "</p>";
            $row[] = "<p>" . \Carbon\Carbon::parse($w->fechapag)->format('d/m/Y h:i a') . "</p>";
            $row[] = "<p>{$w->descripcion}</p>";
            $row[] = $w->codoperacion == 1 ? '<span class="badge badge-danger">Débito</span>' : '<span class="badge badge-success">Crédito</span>';
            $row[] = '<p>' . number_format($p->texento, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->tgravable, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->mtotax, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->factor, 2, ',', '.') . '</p>';
            $row[] = '<p>' . number_format($p->mtototal, 2, ',', '.') . '</p>';
            $row[] = '<p>' . ($p->factor ? number_format($p->mtototal / $p->factor, 2, ',', '.') : number_format(0, 2, ',', '.')) . '</p>';
            $row[] = '<p>' . ($p->savend->descrip ?? 'N/A') . '</p>';
            $row[] = '<span class="badge" style="background:' . ($p->estatusPre->color ?? "#e9e9e9") . ';">'. ($p->estatusPre->nombre ?? "N/A"). '</span>';
            $row[] = view('presupuestos.actions', compact('p'))->render();
            $data[] = $row;
        }

        return response()->json([
        ]);
    }
}
