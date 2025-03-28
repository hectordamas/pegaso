<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Safact, SafactEstatusHistorial};

class EstatusPre extends Model
{
    use HasFactory;

    protected $table = 'estatuspre'; 

    public function safact(){
        //en has many primero es la llave foranea de la tabla de este modela y luego de la tabla a relacionar
        return $this->hasMany(Safact::class, 'codestatus', 'id');
    }

    public function safactsHistorial()
    {
        return $this->hasMany(SafactEstatusHistorial::class, 'estatusPre_id', 'id');
    }

}
