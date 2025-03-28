<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Consultor, TipoLlamada, Motivo, ChatLlamada, Menciones};
use Carbon\Carbon;

class Llamadas extends Model
{
    use HasFactory;

    protected $primaryKey = 'codllamada';
    protected $table = 'llamadas';

    public function tipollamada(){
        return $this->belongsTo(TipoLlamada::class, 'codtipollamada', 'codtipollamada');
    }

    public function motivo(){
        return $this->belongsTo(Motivo::class, 'codmotivo', 'codmotivo');
    }

    public function consultor(){
        return $this->belongsTo(Consultor::class, 'codconsultor', 'codconsultor');
    }

    public function chatllamada(){
        return $this->hasMany(ChatLlamada::class, 'codllamada', 'codllamada');
    }

    public function menciones(){
        return $this->hasMany(Menciones::class, 'codllamada', 'codllamada');
    }

    public function scopeByDateRange($query, $from, $until){
        if ($from && $until) {
            return $query->whereBetween('fecha', [Carbon::parse($from)->startOfDay(), Carbon::parse($until)->endOfDay()]);
        } elseif ($from) {
            return $query->whereDate('fecha', '>=', Carbon::parse($from)->startOfDay());
        } elseif ($until) {
            return $query->whereDate('fecha', '<=', Carbon::parse($until)->endOfDay());
        }else{
            return $query->whereBetween('fecha', [Carbon::now()->subMonths(2), Carbon::now()]);
        }
    }

    public function scopeByTipoLlamada($query, $codtipollamada){
        if($codtipollamada)
            return $query->where('codtipollamada', $codtipollamada);
    }

    public function scopeByMotivo($query, $codmotivo){
        if($codmotivo)
            return $query->where('codmotivo', $codmotivo);
    }

}
