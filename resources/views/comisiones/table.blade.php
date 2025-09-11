<div class="card">
    <div class="card-header">
        <h5>Equipo de Ventas</h5>
        <span class="text-muted">Administración de comisiones y gerencia</span>
    </div>
    <div class="card-block dt-responsive table-responsive">
        <table class="table table-striped table-bordered" id="comisiones-table">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Vendedor</th>
                    <th>Monto Producto</th>
                    <th>Monto Servicio</th>
                    <th>Monto Gerencia</th>
                    <th>Gerente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comisiones as $c)
                <tr class="comisiones-row" data-id="{{ $c->id }}">
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->vendedor }}</td>
                    <td class="monto-producto">
                        $ {{ number_format($c->comision_producto, 2, '.', ',') }}
                    </td>
                    <td class="monto-servicio">
                        $ {{ number_format($c->comision_servicio, 2, '.', ',') }}
                    </td>
                    <td class="monto-gerencia">
                        $ {{ number_format($c->comision_gerencial, 2, '.', ',') }}
                    </td>
                    <td>
                        @if($c->es_gerente) 
                            <i class="fas fa-check fa-2x"></i>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('comisiones/vendedor/' . $c->id) }}" class="btn btn-inverse"  data-toggle="tooltip" data-placement="top" title="Configurar">
                            <i class="fas fa-cog"></i> 
                        </a>
                        <a href="{{ url('comisiones/detalles/' . $c->id) }}" class="btn btn-warning"  data-toggle="tooltip" data-placement="top" title="Detalles">
                            <i class="fas fa-list"></i> 
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>