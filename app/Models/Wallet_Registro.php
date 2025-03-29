<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Moneda, TipoMoneda};

class Wallet_Registro extends Model
{
    use HasFactory;

    protected $table = 'wallet_registro';

    public function moneda(){
        return $this->belongsTo(Moneda::class, 'codmoneda', 'codmoneda');
    }

    public function tipomoneda(){
        return $this->belongsTo(TipoMoneda::class, 'codtipomoneda', 'codtipomoneda');
    }
}
