<div class="card mt-3">
    <div class="card-block p-0">
        <table class="table table-bordered table-sm mb-0 table-striped">
            <thead class="table-dark">
                <tr>
                    <th>SERVICIOS</th>
                    <th>TOTAL SERVICIO</th>
                    <th>TOTAL COBRADO</th>
                    <th>TOTAL COMISIÓN</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalServicios = 0;
                    $totalCobrados = 0;
                    $totalComisiones = 0;
                @endphp

                @foreach ($resumen as $item)
                    <tr>
                        <td class="fw-bold">{{ $item['name'] }}</td>
                        <td>${{ number_format($item['total_servicio'], 2, ',', '.') }}</td>
                        <td>${{ number_format($item['total_cobrado'], 2, ',', '.') }}</td>
                        <td>${{ number_format($item['total_comision'], 2, ',', '.') }}</td>
                    </tr>

                    @php
                        $totalServicios += $item['total_servicio'];
                        $totalCobrados += $item['total_cobrado'];
                        $totalComisiones += $item['total_comision'];
                    @endphp
                @endforeach

                <tr class="fw-bold bg-dark text-light">
                    <td>TOTALES</td>
                    <td>${{ number_format($totalServicios, 2, ',', '.') }}</td>
                    <td>${{ number_format($totalCobrados, 2, ',', '.') }}</td>
                    <td>${{ number_format($totalComisiones, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
