<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{CuadreDeCaja, Saclie};

class MovimientosCuadre extends Model
{
    use HasFactory;

    protected $table = "movimientos_cuadre";

    public function cuadre_de_caja(){
        return $this->belongsTo(CuadreDeCaja::class, 'cuadre_id', 'id');
    }


    public function saclie(){
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }
}
