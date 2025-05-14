<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{LicenciasAActivar};

class ComprobanteLicencia extends Model
{
    use HasFactory;

    protected $table = 'comprobante_licencia';

    public function licencia(){
        return $this->belongsTo(LicenciasAActivar::class, 'licencia_id', 'id');
    }

}
