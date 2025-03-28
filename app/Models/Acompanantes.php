<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Visita};

class Acompanantes extends Model
{
    use HasFactory;

    protected $table = "acompanantes";

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }

    public function llamada(){
        return $this->belongsTo(Visita::class, 'codvisita', 'codvisita');
    }
}
