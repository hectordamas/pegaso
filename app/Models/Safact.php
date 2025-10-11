<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    EstatusPre, Savend, Saclie, ChatProyecto, ChatEntrega,
    saitemfac, SafactEstatusHistorial, CxC, HistoryItem, Detallecxc, CobranzasOrigen
};
use Carbon\Carbon;

class Safact extends Model
{
    use HasFactory;

    protected $table = 'safact';

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

    public function esClienteNuevo(): bool
    {
        if (!$this->saclie) {
            return true; // sin cliente asociado, lo tratamos como nuevo
        }

        return !$this->saclie->safact()
            ->where('codestatus', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])
            ->where('id', '<', $this->id) // solo anteriores
            ->where('id', '!=', $this->id) // excluir el proyecto actual
            ->exists();
    }


    /* Datos de Proyectos */

    //Base Imponible
    public function base_imponible(){
        $factor = $this->factor ?: 1;

        return $this->tgravable / $factor;
    }

    public function getAbonosMesesAnteriores(int $mes, int $year = null){
        $year = $year ?? date('Y');

        return $this->cxc?->detallecxc()->where(function ($q) use ($mes, $year) {
                $q->whereYear('fechaDePago', '<', $year)->orWhere(function ($q2) use ($mes, $year) {
                      $q2->whereYear('fechaDePago', $year)->whereMonth('fechaDePago', '<', $mes);
                  });
            })
            ->sum('monto') ?? 0;
    }

    public function getAbonosMesActual(int $mes, int $year = null){
        $year = $year ?? date('Y');

        return $this->cxc?->detallecxc()->whereYear('fechaDePago', $year)->whereMonth('fechaDePago', $mes)
        ->sum('monto') ?? 0;
    }

    public function abonadoPorcentaje(int $mes, int $year = null){
        $year = $year ?? date('Y');

        return ($this->getAbonosMesActual($mes, $year) * 100) / ($this->getBaseImponibleRestante($mes, $year) ?: 1);
    }

    //Base imponible menos los abonos de meses anteriores
    public function getBaseImponibleRestante(int $mes, int $year = null){
        $year = $year ?? date('Y');

        $acumulado = $this->getAbonosMesesAnteriores($mes, $year);

        return $this->base_imponible() - $acumulado;
    }

    //Pendiente
    public function pendiente(int $mes, int $year = null){
        $year = $year ?? date('Y');

        return $this->getBaseImponibleRestante($mes, $year) - $this->getAbonosMesActual($mes, $year);
    }

    //Pendiente Porcentaje
    public function pendientePorcentaje(int $mes, int $year = null){
        $year = $year ?? date('Y');
    
        $restante = $this->getBaseImponibleRestante($mes, $year);
        $pendiente = $this->pendiente($mes, $year);
    
        return ($pendiente * 100) / ($restante ?: 1);
    }


    /**  * Productos y Servicios * */
    public function montoTotalProductos()
    {
        $factor = $this->factor ?: 1;
        return $this->saitemfac
            ->where('EsServ', false)
            ->sum(fn($i) => $i->TotalItem / $factor);
    }

    public function montoTotalServicios()
    {
        $factor = $this->factor ?: 1;
        return $this->saitemfac
            ->where('EsServ', true)
            ->sum(fn($i) => $i->TotalItem / $factor);
    }

    public function totalAbonado()
    {
        return $this->cxc?->detallecxc()->sum('monto') ?? 0;
    }

    // Total abonado a productos acumulado
    public function totalAbonadoProductos()
    {
        $abonado = $this->totalAbonado();

        // Si no hay productos, no se asigna nada
        if ($this->montoTotalProductos() <= 0) {
            return 0;
        }

        return min($abonado, $this->montoTotalProductos());
    }

    // Total abonado a servicios acumulado
    public function totalAbonadoServicios()
    {
        $abonado = $this->totalAbonado();

        // Si no hay productos, todo el abonado se asigna a servicios
        if ($this->montoTotalProductos() <= 0) {
            return min($abonado, $this->montoTotalServicios());
        }

        return min(
            max(0, $abonado - $this->montoTotalProductos()),
            $this->montoTotalServicios()
        );
    }

    // Pendiente de productos este mes
    public function pendienteProductosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        $abonadoAnterior = $this->getAbonosMesesAnteriores($mes, $year);
    
        // Si abonado anterior cubre parte de productos
        $restante = max(0, $this->montoTotalProductos() - $abonadoAnterior);
    
        return $restante;
    }
    
    // Pendiente de servicios este mes
    public function pendienteServiciosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        $abonadoAnterior = $this->getAbonosMesesAnteriores($mes, $year);
    
        // Primero cubrimos productos con lo abonado
        $restanteAbonos = max(0, $abonadoAnterior - $this->montoTotalProductos());
    
        // Lo pendiente de servicios es lo total menos lo cubierto
        $restante = max(0, $this->montoTotalServicios() - $restanteAbonos);
    
        return $restante;
    }

    // Abonado a productos en este mes
    public function abonadoProductosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');

        $abonadoAnterior = $this->getAbonosMesesAnteriores($mes, $year);
        $abonadoMes = $this->getAbonosMesActual($mes, $year);

        $pendienteProductos = max(0, $this->montoTotalProductos() - $abonadoAnterior);

        // Lo abonado este mes a productos es lo que alcance del pendiente
        return min($abonadoMes, $pendienteProductos);
    }

    // Abonado a servicios en este mes
    public function abonadoServiciosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');

        $abonadoAnterior = $this->getAbonosMesesAnteriores($mes, $year);
        $abonadoMes = $this->getAbonosMesActual($mes, $year);

        $pendienteProductos = max(0, $this->montoTotalProductos() - $abonadoAnterior);

        // Primero se descuenta lo que va a productos
        $abonadoProductosMes = min($abonadoMes, $pendienteProductos);

        // Lo que sobra del abono del mes se va a servicios
        return max(0, $abonadoMes - $abonadoProductosMes);
    }



    //Comisiones
    public function totalComisionProducto(int $mes, int $year = null){
        $year = $year ?? date('Y');

        if ($this->check_admin && $this->check_manager) {
            return $this->abonadoProductosMes($mes, $year) * ($this->porcentajeComisionProducto / 100);
        }
        
        return $this->abonadoProductosMes($mes, $year) * ($this->savend->comision_producto / 100);
    }

    public function totalComisionServicio(int $mes, int $year = null){
        $year = $year ?? date('Y');

        if ($this->check_admin && $this->check_manager) {
            return $this->abonadoServiciosMes($mes, $year) * ($this->porcentajeComisionServicio / 100);
        }

        return $this->abonadoServiciosMes($mes, $year) * ($this->savend->comision_servicio / 100);
    }

    public function totalComision(int $mes, int $year = null){
        $year = $year ?? date('Y');

        return $this->totalComisionProducto($mes, $year) + $this->totalComisionServicio($mes, $year);
    }



   /* // Comisiones Cobranzas
    public function montoAPagarHasta($detalleId)
    {
        $base = $this->base_imponible() ?? 0;
        $detalle = DetalleCxC::find($detalleId);
        if (!$detalle) return $base;

        // Obtener todos los pagos anteriores al actual (no inclusive)
        $pagosPrevios = DetalleCxC::whereHas('cxc', function ($q) {
                $q->where('safact_id', $this->id);
            })
            ->where(function ($q) use ($detalle) {
                $q->where('fechaDePago', '<', $detalle->fechaDePago)
                  ->orWhere(function ($q2) use ($detalle) {
                      $q2->where('fechaDePago', $detalle->fechaDePago)
                         ->where('id', '<', $detalle->id);
                  });
            })
            ->orderBy('fechaDePago')
            ->orderBy('id')
            ->get();

        $totalAbonadoPrevio = $pagosPrevios->sum('monto');

        // Este es el monto que queda pendiente justo antes de este abono
        return max($base - $totalAbonadoPrevio, 0);
    }

    public function quedaPendiente($detalleId)
    {
        $base = $this->base_imponible() ?? 0;
        $detalle = DetalleCxC::find($detalleId);
        if (!$detalle) return $base;

        // Obtener todos los pagos hasta e incluyendo el actual
        $pagos = DetalleCxC::whereHas('cxc', function ($q) {
                $q->where('safact_id', $this->id);
            })
            ->where(function ($q) use ($detalle) {
                $q->where('fechaDePago', '<', $detalle->fechaDePago)
                  ->orWhere(function ($q2) use ($detalle) {
                      $q2->where('fechaDePago', $detalle->fechaDePago)
                         ->where('id', '<=', $detalle->id);
                  });
            })
            ->orderBy('fechaDePago')
            ->orderBy('id')
            ->get();

        $totalAbonado = $pagos->sum('monto');

        // Este es el saldo pendiente después del abono actual
        return max($base - $totalAbonado, 0);
    }

    public function porcentajeAPagarHasta($detalleId)
    {
        $base = $this->base_imponible() ?? 0;
        if ($base == 0) return 0;

        $montoPendienteAntes = $this->montoAPagarHasta($detalleId);

        // Cuánto representa el saldo antes del pago respecto a la base
        return round(($montoPendienteAntes / $base) * 100, 2);
    }

    public function porcentajePendiente($detalleId)
    {
        $base = $this->base_imponible() ?? 0;
        if ($base == 0) return 0;

        $saldoPendiente = $this->quedaPendiente($detalleId);

        // Cuánto representa el saldo después del pago respecto a la base
        return round(($saldoPendiente / $base) * 100, 2);
    }

    public function porcentajePagado($detalleId)
    {
        $base = $this->base_imponible() ?? 0;
        if ($base == 0) return 0;

        $detalle = DetalleCxC::find($detalleId);
        if (!$detalle) return 0;

        $montoPagado = $detalle->monto;

        // Cuánto representa este pago respecto a la base
        return round(($montoPagado / $base) * 100, 2);
    }
*/

}
