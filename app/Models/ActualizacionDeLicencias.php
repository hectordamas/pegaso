<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Saclie;
use Carbon\Carbon;

class ActualizacionDeLicencias extends Model
{
    use HasFactory;

    protected $table = 'actualizacion_de_licencias';

    public function saclie(){
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }

    public function scopeByDateRange($query, $from, $until){
        if ($from && $until) {
            return $query->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($until)->endOfDay()]);
        } elseif ($from) {
            return $query->whereDate('created_at', '>=', Carbon::parse($from)->startOfDay());
        } elseif ($until) {
            return $query->whereDate('created_at', '<=', Carbon::parse($until)->endOfDay());
        }
    }

    public function scopeBySaclie($query, $codclie){
        if($codclie)
            return $query->where('codclie', $codclie);
    }

    public function scopeByStatus($query, $status){
        if($status)
            return $query->where('status', $status);
    }

    public function scopeByIncidencias($query, $incidencias){
        if($incidencias)
            return $query->where('incidencias', $incidencias);
    }

}
