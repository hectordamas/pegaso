<div class="card mt-3">
    <div class="card-header bg-dark text-white">
        <h6 class="mb-0">Resumen de Cobros por Origen</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-sm mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>Origen</th>
                    <th>Total Cobrado (USD)</th>
                    <th>% del Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($origenes as $o)
                    <tr>
                        <td>{{ $o['nombre'] }}</td>
                        <td>${{ number_format($o['total'], 2, '.', ',') }}</td>
                        <td>{{ number_format($o['porcentaje'], 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold table-light">
                    <td class="text-end">Total General:</td>
                    <td>${{ number_format($totalCobradoUSD, 2, '.', ',') }}</td>
                    <td>100%</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
