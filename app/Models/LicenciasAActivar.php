<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Saclie};

class LicenciasAActivar extends Model
{
    use HasFactory;

    protected $table = "licenciasaactivar";

    public function saclie(){
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }

    public function scopeByDateRange($query, $from, $until){
        if ($from && $until) {
            return $query->whereBetween('fechadepago', [Carbon::parse($from)->startOfDay(), Carbon::parse($until)->endOfDay()]);
        } elseif ($from) {
            return $query->whereDate('fechadepago', '>=', Carbon::parse($from)->startOfDay());
        } elseif ($until) {
            return $query->whereDate('fechadepago', '<=', Carbon::parse($until)->endOfDay());
        }
    }

    public function scopeByActivada($query, $activada){
        if($activada)
            return $query->where('activada', true);
    }

    public function scopeByPagada($query, $pagada){
        if($pagada)
            return $query->where('pagada', true);
    }
}
