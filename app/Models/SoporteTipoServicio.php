<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Safact};

class SoporteTipoServicio extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = "soporte_tipo_servicio";

    public function safact(){
        return $this->hasMany(Safact::class, 'soporte_tipo_servicio_id');
    }
}
