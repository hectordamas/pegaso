<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped text-center table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th><small>#</small></th>
                        <th><small>Código</small></th>
                        <th><small>Ubic.</small></th>
                        <th><small>Producto</small></th>
                        <th><small>Estatus</small></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                    <tr>
                        <td>{{ $index }}</td>
                        <td>{{ $item->CodItem }}</td>
                        <td>{{ $item->CodUbic }}</td>
                        <td>
                            {!! implode('<br>', array_filter([$item->Descrip1, $item->Descrip2, $item->Descrip3, $item->Descrip4, $item->Descrip5])) !!}
                        </td>
                        <td class="text-center">
                            <div class="custom-radio-wrapper">
                                <label class="mr-5">
                                    <input type="radio" name="status_{{ $item->id }}" value="Despachado" class="update-status-entrega-item" 
                                        data-id="{{ $item->id }}" 
                                        data-previous-status="{{ $item->estado }}" 
                                        {{ $item->estado === 'Despachado' ? 'checked' : '' }} 
                                        {{ $entrega->codestatus == 13 ? 'disabled' : '' }}> Despachado
                                </label>
                                <label class="mr-5">
                                    <input type="radio" name="status_{{ $item->id }}" value="Enviado" class="update-status-entrega-item" 
                                        data-id="{{ $item->id }}" 
                                        data-previous-status="{{ $item->estado }}" 
                                        {{ $item->estado === 'Enviado' ? 'checked' : '' }} 
                                        {{ $entrega->codestatus == 13 ? 'disabled' : '' }}> Enviado
                                </label>
                                <label>
                                    <input type="radio" name="status_{{ $item->id }}" value="Entregado" class="update-status-entrega-item" 
                                        data-id="{{ $item->id }}" 
                                        data-previous-status="{{ $item->estado }}" 
                                        {{ $item->estado === 'Entregado' ? 'checked' : '' }} 
                                        {{ $entrega->codestatus == 13 ? 'disabled' : '' }}> Entregado
                                </label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @if($entrega->estatusHistorial->isNotEmpty())
        <div class="col-md-12 mt-3">
            <h5 class="mb-2">Historial de Cambios</h5>
            <table class="table table-striped text-center table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th><small>#</small></th>
                        <th><small>Estatus</small></th>
                        <th><small>Fecha Inicio</small></th>
                        <th><small>Fecha Fin</small></th>
                        <th><small>Tiempo Total</small></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entrega->estatusHistorial as $index => $h)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $h->estatusPre->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($h->fecha_inicio)->format('d/m/Y H:i a') }}</td>
                        <td>
                            @if(in_array($h->estatusPre_id, [4, 5, 6]))
                                {{ \Carbon\Carbon::parse($h->fecha_inicio)->format('d/m/Y H:i a') }}
                            @elseif($h->fecha_fin)
                                {{ \Carbon\Carbon::parse($h->fecha_fin)->format('d/m/Y H:i a') }}
                            @else
                                <span class="badge badge-success">En curso</span>
                            @endif
                        </td>
                        <td>
                            @if(in_array($h->estatusPre_id, [4, 5, 6, 8]))
                                <span class="badge badge-primary"><i class="fas fa-check-double"></i> Finalizado</span>
                            @elseif($h->fecha_fin)
                                {{ \Carbon\Carbon::parse($h->fecha_inicio)->diffForHumans(\Carbon\Carbon::parse($h->fecha_fin), true) }}
                            @else
                                <span class="badge badge-success">En curso</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    @php
                        $fechaInicio = \Carbon\Carbon::parse($entrega->fechae);
                        $ultimoHistorial = $entrega->estatusHistorial()->latest('fecha_inicio')->first();
                        $total = 'No se guardó un historial de cambios de estatus.';

                        if ($ultimoHistorial) {
                            $fechaFin = $ultimoHistorial->fecha_fin ? \Carbon\Carbon::parse($ultimoHistorial->fecha_fin) : \Carbon\Carbon::parse($ultimoHistorial->created_at);
                            $diferencia = $fechaInicio->diff($fechaFin);

                            $total = collect([
                                $diferencia->y > 0 ? $diferencia->y . ' año' . ($diferencia->y > 1 ? 's' : '') : null,
                                $diferencia->m > 0 ? $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '') : null,
                                $diferencia->d > 0 ? $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '') : null
                            ])->filter()->implode(', ');
                        }
                    @endphp
                    <tr>
                        <td colspan="4"><strong>Fecha de Creación:</strong></td>
                        <td>{{ $fechaInicio->format('d-m-Y h:i a') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4"><strong>Duración total del proyecto:</strong></td>
                        <td>{{ $total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="col-md-12">
            <div class="table-responsive">
                @if(!in_array($entrega->codestatus, [13]))
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <span class="badge badge-success">Entrega en Curso</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Fecha de Creación:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($entrega->fechae)->format('d-m-Y h:i a') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Duración del Proyecto</strong></td>
                                <td>{{ \Carbon\Carbon::parse($entrega->fechae)->diffForHumans(\Carbon\Carbon::parse(now())) }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td>No se guardó un histórico de cambios de este proyecto</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endif
</div>


<style>
    .custom-radio-wrapper {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .custom-radio-wrapper label {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
        border: 2px solid transparent;
    }

    /* Estilo por defecto */
    .custom-radio-wrapper label {
        background-color: #f8f9fa;
        color: #333;
        border: 2px solid #ccc;
    }

    /* Deshabilitados */
    .custom-radio-wrapper input:disabled + label {
        background-color: #ddd;
        cursor: not-allowed;
        border-color: #bbb;
        color: #888;
    }

</style>