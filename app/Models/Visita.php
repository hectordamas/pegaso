<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Saclie, Consultor, User, ChatVisita, Acompanantes};
use Carbon\Carbon;

class Visita extends Model
{
    use HasFactory;

    protected $table = 'visita';
    protected $primaryKey = 'codvisita';

    public function saclie(){
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }

    public function consultor(){
        return $this->belongsTo(Consultor::class, 'codconsultor', 'codconsultor');
    }

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }

    public function chatvisita(){
        return $this->hasMany(ChatVisita::class, 'codvisita', 'codvisita');
    }

    public function acompanantes(){
        return $this->hasMany(Acompanantes::class, 'codvisita', 'codvisita');
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

    public function scopeByConsultor($query, $codconsultor){
        if($codconsultor)
            return $query->where('codconsultor', $codconsultor);
    }
}
