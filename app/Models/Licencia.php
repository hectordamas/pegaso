<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{LicenciasAActivar};

class Licencia extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function activaciones()
    {
        return $this->belongsToMany(LicenciasAActivar::class, 'licencia_licenciasaactivar', 'licencia_id', 'licenciasaactivar_id');
    }
}
