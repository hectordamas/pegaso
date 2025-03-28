<div class="dt-responsive table-responsive">
    <table class="table table-bordered table-striped" id="cxcDetails-table">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Observación</th>
                <th>Moneda</th>
                <th>Monto</th>
                <th>Abono</th>
                <th>Resta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cxcs as $cxc)
            <tr>
                <td><p>{{ $cxc->codcxc }}</p></td>
                <td><p>{{ \Carbon\Carbon::parse($cxc->fecha)->format('d-m-Y') }}</p></td>
                <td><p>{{ $cxc->cliente }}</p></td>
                <td><p>{{ $cxc->observacion }}</p></td>
                <td><p>{{ $cxc->moneda->nombre }}</p></td>
                <td><p>{{ number_format($cxc->monto, 2, ',', '.') }}</p></td>
                <td><p>{{ number_format($cxc->abono, 2, ',', '.') }}</p></td>
                <td><p>{{ number_format($cxc->saldo, 2, ',', '.') }}</p></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="table-dark">
            <tr>
                <th colspan="5">
                    Totales
                </th>
                <th>{{ number_format($cxcs->sum('monto'), 2, '.', ',') }}</th>
                <th>{{ number_format($cxcs->sum('abono'), 2, '.', ',') }}</th>
                <th>{{ number_format($cxcs->sum('saldo'), 2, '.', ',') }}</th>
            </tr>
        </tfoot>
    </table>
</div>