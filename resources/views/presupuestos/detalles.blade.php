<div class="row">
    <div class="col-md-12">
        <div class="table-responsive dt-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <td><small>#</small></td>
                        <td><small>Código</small></td>
                        <td><small>Ubic.</small></td>
                        <td><small>Producto</small></td>
                        <td><small>Precio</small></td>
                        <td><small>Cant.</small></td>
                        <td><small>Total Item</small></td>
                        <td><small>Total Item ($)</small></td>
                        <td><small>Factor</small></td>
                        <td><small>IVA</small></td>
                        <td><small>Total</small></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->CodItem }}</td>
                            <td>{{ $item->CodUbic }}</td>
                            <td>
                                <small>
                                    {!! implode(
                                        '<br>',
                                        array_filter([$item->Descrip1, $item->Descrip2, $item->Descrip3, $item->Descrip4, $item->Descrip5]),
                                    ) !!}
                                </small>
                            </td>
                            <td>{{ number_format($item->Precio, 2, '.', ',') }}</td>
                            <td>{{ $item->Cantidad }}</td>
                            <td>{{ number_format($item->TotalItem, 2, '.', ',') }}</td>
                            <td>{{ number_format($item->TotalItem / ($item->safact->factor ?: 1), 2, '.', ',') }}</td>
                            <td>{{ number_format($item->safact->factor ?: 1, 2, '.', ',') }}</td>
                            <td>{{ number_format($item->MtoTax, 2, '.', ',') }}</td>
                            <td>{{ number_format($item->TotalItem + $item->MtoTax, 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="4">
                            Totales
                        </td>
                        <td>{{ number_format($items->sum('Precio'), 2, '.', ',') }}</td>
                        <td>{{ number_format($items->sum('Cantidad'), 2, '.', ',') }}</td>
                        <td>{{ number_format($items->sum('TotalItem'), 2, '.', ',') }}</td>
                        <td>{{ number_format(
                            $items->sum(function ($item) {
                                return $item->TotalItem / ($item->safact->factor ?: 1);
                            }),
                            2,
                            '.',
                            ',',
                        ) }}
                        </td>
                        <td></td>
                        <td>{{ number_format($items->sum('MtoTax'), 2, '.', ',') }}</td>
                        <td>{{ number_format($items->sum('TotalItem') + $items->sum('MtoTax'), 2, '.', ',') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


@if ($presupuesto->razon)
    <div class="row">
        <div class="col-md-12 mt-3">
            <p><strong>Razón: </strong> {{ $presupuesto->razon }}</p>
        </div>
    </div>
@endif
