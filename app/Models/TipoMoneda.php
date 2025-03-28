<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Moneda};

class TipoMoneda extends Model
{
    use HasFactory;

    protected $table = 'tipomoneda';
 
    protected $primaryKey = 'codtipomoneda';

    public function moneda(){
        return $this->hasMany(Moneda::class);
    }
}
