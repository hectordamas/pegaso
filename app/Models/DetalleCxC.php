<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{TipoMoneda, CxC, User, Moneda};
use Carbon\Carbon;

class DetalleCxC extends Model
{
    use HasFactory;

    protected $table = 'detallecxc';

    public function tipomoneda() {
        return $this->belongsTo(TipoMoneda::class, 'codtipomoneda', 'codtipomoneda');
    }

    public function cxc(){
        return $this->belongsTo(CxC::class, 'codcxc', 'codcxc');
    }
    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }

    public function scopeByDateRange($query, $from = null, $until = null)
    {
        if ($from && $until) {
            return $query->whereBetween('fecha', [ Carbon::parse($from), Carbon::parse($until) ]);
        } elseif ($from) {
            return $query->where('fecha', '>=', Carbon::parse($from));
        } elseif ($until) {
            return $query->where('fecha', '<=', Carbon::parse($until));
        } else {
            return $query->whereBetween('fecha', [now()->subMonths(2), now()]);
        }
    }

    public function scopeBySaclie($query, $codclie)
    {
        if ($codclie) {
            return $query->whereHas('cxc', function ($q) use ($codclie) {
                $q->where('codclie', $codclie);
            });
        }
    
        return $query;
    }

}


