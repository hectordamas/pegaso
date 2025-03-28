<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Saclie, TipoMoneda, Moneda, DetalleCxC};

class CxC extends Model
{
    use HasFactory;

    protected $table = "cxc";
    protected $primaryKey = "codcxc";

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }

    public function saclie(){
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }

    public function moneda() {
        return $this->belongsTo(Moneda::class, 'codmoneda', 'codmoneda');
    }
    
    public function tipomoneda() {
        return $this->belongsTo(TipoMoneda::class, 'codtipomoneda', 'codtipomoneda');
    }
    
    public function detallecxc(){
        return $this->hasMany(DetalleCxC::class, 'codcxc', 'codcxc');
    }
}
