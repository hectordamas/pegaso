<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Safact, Savend};
use Carbon\Carbon;

class saitemfac extends Model
{
    use HasFactory;
    protected $table = 'saitemfac';

    public function safact(){
        return $this->hasOne(Safact::class, 'numerod', 'NumeroD')
        ->whereColumn('tipofac', 'TipoFac');   
    }

    public function savend(){
        return $this->belongsTo(Savend::class, 'CodVend', 'codvend');
    }

    public function scopeByMonth($query, $month){
        if ($month) 
            return $query->whereMonth('FechaE', $month)
                    ->whereYear('FechaE', date('Y'));
        
    }
}
