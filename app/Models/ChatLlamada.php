<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Llamadas};

class ChatLlamada extends Model
{
    use HasFactory;

    protected $table = 'chatllamada';

    public function llamada(){
        return $this->belongsTo(Llamadas::class, 'codllamada', 'codllamada');
    }  

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }
}
