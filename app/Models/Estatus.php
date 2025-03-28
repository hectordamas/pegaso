<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EntradaEquipos;

class Estatus extends Model
{
    use HasFactory;

    protected $table = 'estatus';
    protected $primaryKey = 'codestatus';

    public function entradaequipos(){
        return $this->hasMany(EntradaEquipos::class);
    }
}
