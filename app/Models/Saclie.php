<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{AtencionCliente, Safact, CxC};

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
}
