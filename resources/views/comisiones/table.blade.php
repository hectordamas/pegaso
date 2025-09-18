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
                    <th>Comisión Producto</th>
                    <th>Comisión Servicio</th>
                    <th>Comisión Gerencia</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $v)
                <tr class="comisiones-row">
                    <td>{{ $v->id }}</td>
                    <td>{{ $v->nombre }}</td>
                    <td class="monto-producto">
                        $ {{ number_format($v->comisionProducto, 2, '.', ',') }} (%{{$v->comision_producto}})
                    </td>
                    <td class="monto-servicio">
                        $ {{ number_format($v->comisionServicio, 2, '.', ',') }} (%{{$v->comision_servicio}})
                    </td>
                    <td class="monto-gerencia">
                        @if($v->comision_gerencia)
                            $ {{ number_format($v->comisionGerencia, 2, '.', ',') }} (%{{$v->comision_gerencia}})
                        @else
                            N\A
                        @endif
                    </td>
                    <td>
                        $ {{ number_format($v->totalConGerencia, 2, '.', ',') }}
                    </td>
                    <td>
                        <a href="{{ url('comisiones/vendedor/' . $v->id) }}" class="btn btn-inverse" data-toggle="tooltip" data-placement="top" title="Configurar">
                            <i class="fas fa-cog"></i> 
                        </a>
                        <a href="{{ url('comisiones/detalles/' . $v->id) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Detalles">
                            <i class="fas fa-list"></i> 
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>