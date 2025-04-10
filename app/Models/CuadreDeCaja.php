<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{MovimientosCuadre};

class CuadreDeCaja extends Model
{
    use HasFactory;

    protected $table = "cuadre_de_caja";

    public function movimientos(){
        return $this->hasMany(MovimientosCuadre::class, 'cuadre_id', 'id');
    }
}
