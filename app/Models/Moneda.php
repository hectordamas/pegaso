<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{TipoMoneda};

class Moneda extends Model
{
    use HasFactory;

    protected $table = 'moneda';

    protected $primaryKey = 'codmoneda';

    public function tipomoneda(){
        return $this->belongsTo(TipoMoneda::class);
    }
}
