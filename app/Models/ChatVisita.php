<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Visita};

class ChatVisita extends Model
{
    use HasFactory;

    protected $table = 'chatvisita';

    public function visita(){
        return $this->belongsTo(Visita::class, 'codvisita', 'codvisita');
    }

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }
}
