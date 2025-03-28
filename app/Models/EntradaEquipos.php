<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Saclie, Consultor, Estatus, User, ChatEntrada};

class EntradaEquipos extends Model
{
    use HasFactory;

    protected $table = "entradaequipos";
    protected $primaryKey = 'codentrada';

    public function saclie(){
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }

    public function consultor(){
        return $this->belongsTo(Consultor::class, 'codconsultor', 'codconsultor');
    }

    public function estatus(){
        return $this->belongsTo(Estatus::class, 'codestatus', 'codestatus');
    }

    public function user(){
        return $this->belongsTo(User::class, 'codusuario', 'codusuario');
    }

    public function chatentradas(){
        return $this->hasMany(ChatEntrada::class, 'codentrada', 'codentrada');
    }

    public function scopeByConsultor($query, $codconsultor){
        if($codconsultor)
            return $query->where('codconsultor', $codconsultor);
    }

    public function scopeBySaclie($query, $codclie){
        if($codclie)
            return $query->where('codclie', $codclie);
        
    }

    public function scopeByStatus($query, $codeStatus){
        if($codeStatus)
            return $query->where('codestatus', $codeStatus);
    }

    public function scopeByDateRange($query, $from, $until){
        if ($from && $until) {
            return $query->whereBetween('fecha', [Carbon::parse($from)->startOfDay(), Carbon::parse($until)->endOfDay()]);
        } elseif ($from) {
            return $query->whereDate('fecha', '>=', Carbon::parse($from)->startOfDay());
        } elseif ($until) {
            return $query->whereDate('fecha', '<=', Carbon::parse($until)->endOfDay());
        }
    }
}
