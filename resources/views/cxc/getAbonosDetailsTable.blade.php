<div class="dt-responsive table-responsive">

    <table align="center" class="table table-striped table-no-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Fecha de Abono</th>
                <th>Descripción</th>
                <th>Método de Pago</th>
                <th>Monto</th>
                <th>Comprobante</th>
            </tr>
        </thead>
        <tbody>
            @forelse($abonos as $item)
              <tr>
                <td>{{ $item->id }}</td>
                <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d-m-Y') }}</td>
                <td>{{ $item->fechaDePago ? \Carbon\Carbon::parse($item->fechaDePago)->format('d-m-Y') : 'N/R' }}</td>
                <td>{{ $item->descripcion }}</td>
                <td>{{ $item->tipomoneda->nombre }}</td>
                <td>{{ number_format($item->monto, 2, '.', ',') }}</td>
                <td>
                    @php
                        $isPdf = str_starts_with($item->file, 'data:application/pdf');
                    @endphp
                    <button class="btn btn-info" onclick="openComprobanteViewer('{{ $item->file }}')" title="Ver Comprobante">
                        @if($isPdf)
                            <i class="fas fa-file-pdf"></i>
                        @else
                            <i class="fas fa-file-image"></i>
                        @endif
                    </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6">No se ha abonado a esta cxc</td>
              </tr>
            @endforelse
        </tbody>
    </table>
</div>