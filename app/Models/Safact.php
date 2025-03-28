<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EstatusPre, Savend, Saclie, ChatProyecto, ChatEntrega, saitemfac, SafactEstatusHistorial};
use Carbon\Carbon;

class Safact extends Model
{
    use HasFactory;

    protected $table = 'safact';
    
    public function scopeByDateRange($query, $from, $until){
        if ($from && $until) {
            return $query->whereBetween('fechae', [Carbon::parse($from)->startOfDay(), Carbon::parse($until)->endOfDay()]);
        } elseif ($from) {
            return $query->whereDate('fechae', '>=', Carbon::parse($from)->startOfDay());
        } elseif ($until) {
            return $query->whereDate('fechae', '<=', Carbon::parse($until)->endOfDay());
        }
    }

    public function scopeBySavend($query, $codvend){
        if($codvend)
            return $query->where('codvend', $codvend);
    }

    public function scopeBySaclie($query, $codclie){
        if($codclie)
            return $query->where('codclie', $codclie);
    }

    public function scopeByStatus($query, $codeStatus){
        if($codeStatus)
            if($codeStatus == 3){
                return $query->whereIn('codestatus', [3, 7, 8, 9, 10]);
            }else{
                return $query->whereIn('codestatus', [$codeStatus]);
            }
    }

    public function chatproyecto(){
        return $this->hasMany(ChatProyecto::class, 'codproyecto', 'id');
    }

    public function chatentrega(){
        return $this->hasMany(ChatEntrega::class, 'codentrega', 'id');
    }

    public function saclie(){
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }

    public function estatusPre(){
        //en belongsTo primero es la llave foranea de la tabla de este modelo y luego de la tabla a relacionar
        return $this->belongsTo(EstatusPre::class, 'codestatus', 'id');
    }

    public function savend(){
        return $this->belongsTo(Savend::class, 'codvend', 'codvend');
    }

    public function saitemfac(){
        return $this->hasMany(saitemfac::class, 'NumeroD', 'numerod')
        ->whereColumn('tipofac', 'TipoFac');
    }

    public function estatusHistorial()
    {
        return $this->hasMany(SafactEstatusHistorial::class, 'safact_id', 'id');
    }

}
