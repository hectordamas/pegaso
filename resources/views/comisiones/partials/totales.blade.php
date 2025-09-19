<h5 class="mb-3">Totales</h5>
<table class="table table-bordered table-sm border-dark shadow">
    <tr>
        <th class="bg-dark text-light">Base Imponible</th>
        <td>${{ number_format($totalBaseImponible, 2, '.', ',') }}</td>
        <th class="bg-dark text-light">Total Productos</th>
        <td>${{ number_format($totalPendienteProductos, 2, '.', ',') }}</td>
        <th class="bg-dark text-light">Total Servicios</th>
        <td>${{ number_format($totalPendienteServicios, 2, '.', ',') }}</td>
    </tr>
    <tr>
        <th class="bg-dark text-light">Cobrado Productos.</th>
        <td>${{ number_format($totalAbonadoProductos, 2, '.', ',') }}</td>
        <th class="bg-dark text-light">Cobrado Servicios.</th>
        <td>${{ number_format($totalAbonadoServicios, 2, '.', ',') }}</td>
        <th class="bg-dark text-light">Total Cobrado</th>
        <td>${{ number_format($totalAbonosMes, 2, '.', ',') }}</td>
    </tr>
    <tr>
        <th class="bg-dark text-light">Comisión Productos</th>
        <td>${{ number_format($totalComisionProductos, 2, '.', ',') }}</td>
        <th class="bg-dark text-light">Comisión Servicio</th>
        <td>${{ number_format($totalComisionServicios, 2, '.', ',') }}</td>
        <th class="bg-dark text-light">Total Comisión</th>
        <td>${{ number_format($totalComision, 2, '.', ',') }}</td>
    </tr>
    <tr>
        <th class="bg-dark text-light">Total Pendiente</th>
        <td>${{ number_format($totalPendiente, 2, '.', ',') }}</td>
        <th class="bg-dark text-light">% Cobrado</th>
        <td>
            {{ $totalBaseImponible > 0 
                ? round((($totalAbonadoProductos + $totalAbonadoServicios) / $totalBaseImponible) * 100) 
                : 0 }}%
        </td>
        <th class="bg-dark text-light">% Pendiente</th>
        <td>
            {{ $totalBaseImponible > 0 
                ? round(($totalPendiente / $totalBaseImponible) * 100) 
                : 0 }}%
        </td>
    </tr>
    <tr>
        <th class="bg-dark text-light">Comisión Productos (%)</th>
        <td>{{ $savend->comision_producto }}%</td>
        <th class="bg-dark text-light">Comisión Servicio (%)</th>
        <td>{{ $savend->comision_servicio }}%</td>
        <th class="bg-dark text-light">Comisión Gerencial (%)</th>
        <td>{{ $savend->comision_gerencia ? $savend->comision_gerencia .'%': 'N/A' }}</td>
    </tr>
</table>

<div class="mt-4">
    @if($savend->comision_gerencia)
    <table class="table table-bordered ">
        <tr>
            <th class="bg-dark text-light">Comisión Gerencial</th>
            <td colspan="5">
                ${{ number_format($savend->getComisionGerencial($mes, $year), 2, '.', ',') }}
                <span class="text-muted">
                    ({{ $savend->comision_gerencia }}% sobre todo el departamento)
                </span>
            </td>
        </tr>
    </table>
    @endif
</div>