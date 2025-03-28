<div class="dt-responsive table-responsive">

    <table align="center" class="table table-striped table-no-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
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
                <td>{{ $item->descripcion }}</td>
                <td>{{ $item->tipomoneda->nombre }}</td>
                <td>{{ number_format($item->monto, 2, '.', ',') }}</td>
                <td>
                    @if($item->file)
                        <a href="javascript:void(0)" onclick="openBase64Image('{{ $item->file }}')" target="_blank">
                            <img src="{{ $item->file }}" style="max-width: 80px;"/>
                        </a>
                    @else
                        No disponible
                    @endif
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