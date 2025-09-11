<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EstatusPre, Savend, Saclie, ChatProyecto, ChatEntrega, saitemfac, SafactEstatusHistorial, CxC, HistoryItem};
use Carbon\Carbon;

class Safact extends Model {

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
            return $query->whereIn('codestatus', [$codeStatus]);
    }

    public function historyItems()
    {
        return $this->hasMany(HistoryItem::class);
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

    public function cxc(){
        return $this->hasOne(CxC::class, 'safact_id', 'id');
    }

    public function scopeByMonth($query, $month){
        if ($month) 
            return $query->whereMonth('fechae', $month)
                    ->whereYear('fechae', date('Y'));
        
    }

    //Comisiones

    // Total presupuestado (bruto)
    public function getTotalPresupuestadoAttribute()
    {
        $factor = $this->factor ?: 1;
        return $this->saitemfac->sum(fn($i) => $i->TotalItem / $factor);
    }

    public function getMontoTotalProductosAttribute(){
        $items = $this->saitemfac;
        $factor = $this->factor ?: 1;
    
        $productoTotal = $items->where('EsServ', false)->sum(fn($item) => $item->TotalItem / $factor);

        return $productoTotal;
    }

    public function getMontoTotalServiciosAttribute(){
        $items = $this->saitemfac;
        $factor = $this->factor ?: 1;
    
        $servicioTotal = $items->where('EsServ', true)->sum(fn($item) => $item->TotalItem / $factor);

        return $servicioTotal;
    }

    // Total abonado acumulado
    public function getTotalAbonadoAttribute()
    {
        return $this->cxc?->detallecxc()->sum('monto') ?? 0;
    }

    // Total abonado en un mes específico
    public function totalAbonadoMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        return $this->cxc?->detallecxc()
            ->whereMonth('fechaDePago', $mes)
            ->whereYear('fechaDePago', $year)
            ->sum('monto') ?? 0;
    }

    // Pendiente por cobrar
    public function getPendienteAttribute()
    {
        return max(0, $this->total_presupuestado - $this->total_abonado);
    }

    // Total abonado a productos en un mes específico
    public function totalAbonadoProductosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        $abonadoMes = $this->totalAbonadoMes($mes, $year);
    
        return min($abonadoMes, $this->monto_total_productos);
    }
    
    // Total abonado a servicios en un mes específico
    public function totalAbonadoServiciosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        $abonadoMes = $this->totalAbonadoMes($mes, $year);
    
        // Lo que sobra después de cubrir productos se va a servicios
        return min(max(0, $abonadoMes - $this->monto_total_productos), $this->monto_total_servicios);
    }

}
