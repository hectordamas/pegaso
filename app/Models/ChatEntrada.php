<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User, EntradaEquipos};

class ChatEntrada extends Model
{
    use HasFactory;

    protected $table = "chatentrada";

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }

    public function entradaequipos(){
        return $this->belongsTo(EntradaEquipos::class, 'codentrada', 'codentrada');
    }

}
