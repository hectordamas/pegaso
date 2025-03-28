<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{AtencionCliente, User};

class Consultor extends Model
{
    use HasFactory;

    protected $table = 'consultor';

    public function atencionclientes(){
        return $this->hasMany(AtencionCliente::class, 'codconsultor', 'codconsultor');
    }

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }
}
