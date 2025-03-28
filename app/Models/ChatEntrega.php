<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Safact};

class ChatEntrega extends Model
{
    use HasFactory;

    protected $table = "chatentrega";

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }

    public function safact(){
        return $this->belongsTo(Safact::class, 'codentrega', 'id');
    }

}
