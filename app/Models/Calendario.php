<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Saclie, Consultor};

class Calendario extends Model
{
    use HasFactory;

    protected $table = "calendario";

    public function saclie(){
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }

    public function consultor(){
        return $this->belongsTo(Consultor::class, 'codconsultor', 'codconsultor');
    }

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }
}
