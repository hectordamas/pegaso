{{-- view comisiones.table --}}
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
                    <th>Gerente</th>
                    <th>Vendedor</th>
                    <th>Servicio (%)</th>
                    <th>Producto (%)</th>
                    <th>Gerencia (%)</th>
                    <th>Monto Servicio</th>
                    <th>Monto Producto</th>
                    <th>Monto Gerencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comisiones as $c)
                <tr class="comisiones-row" data-id="{{ $c->id }}">
                    <td>{{ $c->id }}</td>
                    <td class="text-center">
                        <input type="checkbox" name="es_gerente" class="es_gerente" {{ $c->es_gerente ? 'checked' : '' }}>
                    </td>
                    <td>{{ $c->vendedor }}</td>
                    <td>
                        <div class="input-group mb-0 pb-0">
                            <input type="number" name="servicio" class="form-control servicio" value="{{$c->percent_comision_servicio}}"
                                placeholder="0" min="0" max="100">
                            <span class="input-group-text bg-inverse text-light fw-bold update-comisiones" data-toggle="tooltip" data-placement="top"
                            title="Calcular Comisión">
                                <i class="fas fa-percent"></i>                                    
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group mb-0 pb-0">
                            <input type="number" name="producto" class="form-control producto" value="{{$c->percent_comision_producto}}"
                                placeholder="0" min="0" max="100">
                            <span class="input-group-text bg-inverse text-light fw-bold update-comisiones" data-toggle="tooltip" data-placement="top"
                            title="Calcular Comisión">
                                <i class="fas fa-percent"></i>                                    
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group mb-0 pb-0">
                            <input type="number" name="gerencia" class="form-control gerencia" value="{{$c->percent_comision_gerencial}}"
                                placeholder="0" min="0" max="100" {{ $c->es_gerente ? '' : 'disabled' }} >
                            <span class="input-group-text bg-inverse text-light fw-bold update-comisiones" data-toggle="tooltip" data-placement="top"
                                title="Calcular Comisión">
                                <i class="fas fa-percent"></i>                                    
                            </span>
                        </div>
                    </td>
                    <td class="monto-servicio">
                        $ {{ number_format($c->comision_servicio, 2, '.', ',') }}
                    </td>
                    <td class="monto-producto">
                        $ {{ number_format($c->comision_producto, 2, '.', ',') }}
                    </td>
                    <td class="monto-gerencia">
                        $ {{ number_format($c->comision_gerencial, 2, '.', ',') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>