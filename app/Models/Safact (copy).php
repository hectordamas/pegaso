<?php



class Safact extends Model
{
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


    /** Productos y servicios segun meses * */

    // Total abonado en un mes específico
    public function totalAbonadoMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');

        return $this->cxc?->detallecxc()
            ->whereMonth('fechaDePago', $mes)
            ->whereYear('fechaDePago', $year)
            ->sum('monto') ?? 0;
    }

    // Abonado acumulado hasta el mes anterior
    public function totalAbonadoHastaMesAnterior(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');

        return $this->cxc?->detallecxc()
            ->where(function ($q) use ($mes, $year) {
                $q->whereYear('fechaDePago', '<', $year)
                  ->orWhere(function ($q2) use ($mes, $year) {
                      $q2->whereYear('fechaDePago', $year)
                         ->whereMonth('fechaDePago', '<', $mes);
                  });
            })
            ->sum('monto') ?? 0;
    }

    // Productos mes actual (respetando pagos anteriores)
    public function totalAbonadoProductosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        $abonadoMes = $this->totalAbonadoMes($mes, $year);       // Pagos este mes
        $abonadoAntes = $this->totalAbonadoHastaMesAnterior($mes, $year); // Pagos anteriores

        // Cuánto ya se cubrió de productos
        $productosPagadosAntes = min($abonadoAntes, $this->monto_total_productos);

        // Cuánto falta por cubrir este mes
        $pendienteProductos = max(0, $this->monto_total_productos - $productosPagadosAntes);

        // Lo que se abona a productos este mes
        return min($abonadoMes, $pendienteProductos);
    }

    // Servicios mes actual (respetando pagos anteriores y productos)
    public function totalAbonadoServiciosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        $abonadoMes = $this->totalAbonadoMes($mes, $year);
        $abonadoAntes = $this->totalAbonadoHastaMesAnterior($mes, $year);

        // Productos ya cubiertos entre meses anteriores + este mes
        $productosPagados = min($abonadoAntes, $this->monto_total_productos) + $this->totalAbonadoProductosMes($mes, $year);

        // Servicios ya cubiertos hasta ahora
        $serviciosPagadosAntes = max(0, $abonadoAntes - $this->monto_total_productos);

        // Saldo disponible del mes actual para servicios
        $abonadoDisponible = max(0, $abonadoMes - $this->totalAbonadoProductosMes($mes, $year));

        // Servicios que se pueden abonar este mes
        $pendienteServicios = max(0, $this->monto_total_servicios - $serviciosPagadosAntes);

        return min($abonadoDisponible, $pendienteServicios);
    }

    //Comision Producto MES
    public function totalComisionProductosMes(int $mes, int $year = null){
        return $this->totalAbonadoProductosMes($mes, $year) * ($this->savend->comision_producto / 100);
    }

    //Comision Servicios MES
    public function totalComisionServiciosMes(int $mes, int $year = null){
        return $this->totalAbonadoServiciosMes($mes, $year) * ($this->savend->comision_servicio / 100);
    }

    //Total Comision MES
    public function totalComisionesMes(int $mes, int $year = null){
        return $this->totalAbonadoProductosMes($mes) + $this->totalAbonadoServiciosMes($mes);
    }




    public function getBaseImponibleAttribute()
    {
        $factor = $this->factor ?: 1;
        return $this->tgravable / $factor;
    }
    
    // Base imponible después de restar meses anteriores
    public function baseImponibleRestanteMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
    
        // Todo lo abonado hasta el mes anterior
        $abonadoAntes = $this->totalAbonadoHastaMesAnterior($mes, $year);
    
        // Restamos a la base imponible
        return max(0, $this->base_imponible - $abonadoAntes);
    }

    public function desgloseMes(int $mes, int $year = null): array
    {
        $year = $year ?? date('Y');

        // Totales originales
        $totalProductos = $this->monto_total_productos;
        $totalServicios = $this->monto_total_servicios;

        // 🔹 1. Abonos de meses anteriores
        $abonadoAnterior = $this->cxc?->detallecxc()
            ->where(function ($q) use ($mes, $year) {
                $q->whereYear('fechaDePago', '<', $year)
                  ->orWhere(function ($q2) use ($mes, $year) {
                      $q2->whereYear('fechaDePago', $year)
                         ->whereMonth('fechaDePago', '<', $mes);
                  });
            })
            ->sum('monto') ?? 0;

        // Distribuir esos abonos: primero productos, luego servicios
        $productosCubiertos = min($totalProductos, $abonadoAnterior);
        $serviciosCubiertos = min($totalServicios, max(0, $abonadoAnterior - $totalProductos));

        $pendienteProductos = $totalProductos - $productosCubiertos;
        $pendienteServicios = $totalServicios - $serviciosCubiertos;

        // 🔹 2. Abonos del mes actual
        $abonadoMes = $this->cxc?->detallecxc()
            ->whereMonth('fechaDePago', $mes)
            ->whereYear('fechaDePago', $year)
            ->sum('monto') ?? 0;

        // Aplicar lo abonado este mes a lo pendiente
        $abonadoProductosMes = min($pendienteProductos, $abonadoMes);
        $abonadoServiciosMes = min($pendienteServicios, max(0, $abonadoMes - $pendienteProductos));

        // 🔹 3. Retornar desglose listo
        return [
            'total_productos' => $totalProductos,
            'total_servicios' => $totalServicios,
            'abonado_anterior' => $abonadoAnterior,
            'abonado_mes' => $abonadoMes,
            'abonado_productos_mes' => $abonadoProductosMes,
            'abonado_servicios_mes' => $abonadoServiciosMes,
            'pendiente_productos' => $pendienteProductos - $abonadoProductosMes,
            'pendiente_servicios' => $pendienteServicios - $abonadoServiciosMes,
        ];
    }
}