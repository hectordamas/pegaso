<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{TipoMoneda};
use Carbon\Carbon;

class DetalleCxC extends Model
{
    use HasFactory;

    protected $table = 'detallecxc';

    public function tipomoneda() {
        return $this->belongsTo(TipoMoneda::class, 'codtipomoneda', 'codtipomoneda');
    }

    public function scopeByDateRange($query, $from, $until){
        if ($from && $until) {
            return $query->whereBetween('detallecxc.fecha', [Carbon::parse($from)->startOfDay(), Carbon::parse($until)->endOfDay()]);
        } elseif ($from) {
            return $query->whereDate('detallecxc.fecha', '>=', Carbon::parse($from)->startOfDay());
        } elseif ($until) {
            return $query->whereDate('detallecxc.fecha', '<=', Carbon::parse($until)->endOfDay());
        }else{
            return $query->whereBetween('detallecxc.fecha', [Carbon::now()->subMonths(2), Carbon::now()]);
        }
    }
}


