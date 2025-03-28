<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Llamadas;

class TipoLlamada extends Model
{
    use HasFactory;

    protected $table = 'tipollamada';
 
    protected $primaryKey = 'codtipollamada';

    public function llamadas(){
        return $this->hasMany(Llamadas::class, 'codtipollamada', 'codtipollamada');
    }
}
