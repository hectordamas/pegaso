<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Llamadas;

class Motivo extends Model
{
    use HasFactory;

    protected $table = 'motivo';
 
    protected $primaryKey = 'codmotivo';
 
    public function llamadas(){
        return $this->hasMany(Llamadas::class, 'codmotivo', 'codmotivo');
    }
}
