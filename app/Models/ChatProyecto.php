<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Safact};

class ChatProyecto extends Model
{
    use HasFactory;

    protected $table = "chatproyecto";

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }

    public function safact(){
        return $this->belongsTo(Safact::class, 'codproyecto', 'id');
    }

}
