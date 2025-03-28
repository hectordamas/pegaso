<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafactEstatusHistorial extends Model
{
    use HasFactory;

    protected $table = 'safact_estatuspre_historial'; // Especificamos la tabla

    public function safact()
    {
        return $this->belongsTo(Safact::class, 'safact_id', 'id');
    }

    public function estatusPre()
    {
        return $this->belongsTo(EstatusPre::class, 'estatusPre_id', 'id');
    }
}
