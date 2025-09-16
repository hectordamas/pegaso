<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    EstatusPre, Savend, Saclie, ChatProyecto, ChatEntrega,
    saitemfac, SafactEstatusHistorial, CxC, HistoryItem, Detallecxc
};
use Carbon\Carbon;

class Safact extends Model
{
    use HasFactory;

    protected $table = 'safact';

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeByDateRange($query, $from, $until)
    {
        if ($from && $until) {
            return $query->whereBetween('fechae', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($until)->endOfDay()
            ]);
        } elseif ($from) {
            return $query->whereDate('fechae', '>=', Carbon::parse($from)->startOfDay());
        } elseif ($until) {
            return $query->whereDate('fechae', '<=', Carbon::parse($until)->endOfDay());
        }
    }

    public function scopeBySavend($query, $codvend)
    {
        if ($codvend) {
            return $query->where('codvend', $codvend);
        }
    }

    public function scopeBySaclie($query, $codclie)
    {
        if ($codclie) {
            return $query->where('codclie', $codclie);
        }
    }

    public function scopeByStatus($query, $codeStatus)
    {
        if ($codeStatus) {
            return $query->whereIn('codestatus', [$codeStatus]);
        }
    }

    public function scopeByMonth($query, $month)
    {
        if ($month) {
            return $query->whereMonth('fechae', $month)
                         ->whereYear('fechae', date('Y'));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function historyItems()
    {
        return $this->hasMany(HistoryItem::class);
    }

    public function chatproyecto()
    {
        return $this->hasMany(ChatProyecto::class, 'codproyecto', 'id');
    }

    public function chatentrega()
    {
        return $this->hasMany(ChatEntrega::class, 'codentrega', 'id');
    }

    public function saclie()
    {
        return $this->belongsTo(Saclie::class, 'codclie', 'codclie');
    }

    public function estatusPre()
    {
        return $this->belongsTo(EstatusPre::class, 'codestatus', 'id');
    }

    public function savend()
    {
        return $this->belongsTo(Savend::class, 'codvend', 'codvend');
    }

    public function saitemfac()
    {
        return $this->hasMany(saitemfac::class, 'NumeroD', 'numerod')
                    ->whereColumn('tipofac', 'TipoFac');
    }

    public function estatusHistorial()
    {
        return $this->hasMany(SafactEstatusHistorial::class, 'safact_id', 'id');
    }

    public function cxc()
    {
        return $this->hasOne(CxC::class, 'safact_id', 'id');
    }

    public function ultimoPago()
    {
        return $this->hasOne(Detallecxc::class, 'cxc_id')->latest('fechaDePago');
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE NEGOCIO / ACCESSORS
    |--------------------------------------------------------------------------
    */

    // Total presupuestado (bruto)
    public function getTotalPresupuestadoAttribute()
    {
        $factor = $this->factor ?: 1;
        return $this->tgravable / $factor;
    }

    public function getMontoTotalProductosAttribute()
    {
        $factor = $this->factor ?: 1;
        return $this->saitemfac
            ->where('EsServ', false)
            ->sum(fn($i) => $i->TotalItem / $factor);
    }

    public function getMontoTotalServiciosAttribute()
    {
        $factor = $this->factor ?: 1;
        return $this->saitemfac
            ->where('EsServ', true)
            ->sum(fn($i) => $i->TotalItem / $factor);
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

    public function getPorcentajePendienteAttribute()
    {
        $total = $this->total_presupuestado;

        if ($total <= 0) {
            return 0; // evita división por cero
        }

        return round(($this->pendiente / $total) * 100, 2);
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

        return min(
            max(0, $abonadoMes - $this->monto_total_productos),
            $this->monto_total_servicios
        );
    }

    // Total abonado a productos acumulado
    public function getTotalAbonadoProductosAttribute()
    {
        $abonado = $this->total_abonado;

        // Si no hay productos, no se asigna nada
        if ($this->monto_total_productos <= 0) {
            return 0;
        }

        return min($abonado, $this->monto_total_productos);
    }

    // Total abonado a servicios acumulado
    public function getTotalAbonadoServiciosAttribute()
    {
        $abonado = $this->total_abonado;

        // Si no hay productos, todo el abonado se asigna a servicios
        if ($this->monto_total_productos <= 0) {
            return min($abonado, $this->monto_total_servicios);
        }

        return min(
            max(0, $abonado - $this->monto_total_productos),
            $this->monto_total_servicios
        );
    }

    //Comision Producto
    public function getTotalComisionProductosAttribute(){
        return $this->total_abonado_productos * ($this->savend->comision_producto / 100);
    }

    //Comision Servicios
    public function getTotalComisionServiciosAttribute(){
        return $this->total_abonado_servicios * ($this->savend->comision_servicio / 100);
    }

    //Total Comision
    public function getTotalComisionesAttribute(){
        return $this->total_comision_productos + $this->total_comision_servicios;
    }
}
