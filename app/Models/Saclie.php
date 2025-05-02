<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{AtencionCliente, Safact, CxC, Calendario, EntradaEquipos, Visita};

class Saclie extends Model
{
    use HasFactory;

    protected $table = 'saclie';

    public function atencionclientes(){
        return $this->hasMany(AtencionCliente::class, 'codclie', 'codclie');
    }

    public function safact(){
        return $this->hasMany(Safact::class, 'codclie', 'codclie');
    }

    public function cxc(){
        return $this->hasMany(CxC::class, 'codclie', 'codclie');
    }

    public function calendario(){
        return $this->hasMany(Calendario::class, 'codclie', 'codclie');
    }

    public function entradaEquipos(){
        return $this->hasMany(EntradaEquipos::class, 'codclie', 'codclie');
    }

    public function visitas(){
        return $this->hasMany(Visita::class, 'codclie', 'codclie');
    }

}
