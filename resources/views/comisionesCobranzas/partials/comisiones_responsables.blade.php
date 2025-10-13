<div class="card mt-3">
    <div class="card-header bg-dark text-white">
        <h6 class="mb-0">Resumen de Comisiones del Mes</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-sm mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>Responsable</th>
                    <th>Porcentaje</th>
                    <th>Comisión (USD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($responsables as $r)
                    <tr class="responsable-row" data-id="{{ $r['id'] ?? '' }}" data-name="{{ $r['name'] }}"
                        data-porcentaje="{{ $r['porcentaje'] }}">
                        <td>{{ $r['name'] }}</td>
                        <td>{{ number_format($r['porcentaje'], 2) }}%</td>
                        <td>${{ number_format($r['total_comision'], 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold table-light">
                    <td colspan="2" class="text-end">Total Cobrado:</td>
                    <td>${{ number_format($totalCobradoUSD, 2, '.', ',') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
