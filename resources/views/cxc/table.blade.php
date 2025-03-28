<div class="dt-responsive table-responsive">
    <table class="table table-bordered table-striped" id="cxc-table">
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
                <th></th>
                <th>Acción</th>
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
                <td>
                    <i class="fas fa-circle" style="color: {{ $cxc->color }};"></i>
                    @if($cxc->color == "#00FF00" || $cxc->color == "#00ff00")
                        Cobrar
                    @elseif($cxc->color == "#FFA500" ||$cxc->color == "#ffa500")
                        Julio Farfán
                    @elseif($cxc->color == "#0000FF" || $cxc->color == "#0000ff")
                        Daniel Sousa
                    @elseif($cxc->color == "#FF0000" || $cxc->color == "#ff0000")
                        No Cobrar
                    @endif
                </td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success" data-toggle="tooltip" title="Registrar Abono"
                            data-bs-target="#AbonoModalView" data-bs-toggle="modal" 
                            onclick="setCxCCode('{{ $cxc->codcxc }}')"
                            >
                            <i class="fas fa-donate"></i>
                        </button>
                        @if($cxc->detallecxc_count > 0)
                            <button type="button" class="btn btn-primary" 
                                data-toggle="tooltip" title="Ver Abonos"
                                onclick="getAbonosDetails('{{ $cxc->codcxc }}')">
                                <i class="fas fa-list"></i>
                            </button>
                        @endif
                        <button type="button" 
                            class="btn btn-danger" data-toggle="tooltip" title="Eliminar" 
                            onclick="aceptarEliminar('{{ $cxc->codcxc }}')">
                            <i class="far fa-trash-alt"></i>                        
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>