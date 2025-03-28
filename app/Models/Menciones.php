<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Consultor, Llamadas};

class Menciones extends Model
{
    use HasFactory;

    protected $table = "menciones";

    public function consultor(){
        return $this->belongsTo(Consultor::class, 'codconsultor', 'codconsultor');
    }

    public function llamada(){
        return $this->belongsTo(Llamadas::class, 'codllamada', 'codllamada');
    }
}
