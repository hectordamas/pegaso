@extends('layouts.admin')
@section('metadata')
<title>Reportes Cuentas por Cobrar - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Consultar Cuentas por Cobrar</h5>
                <span>Completa los campos para filtrar y consultar las Cuentas por Cobrar y sus abonos.</span>
            </div>
            <div class="card-block">
                <form class="row" action="{{ url('cuentas-por-cobrar/reportes') }}">
                    <div class="col-md-3 form-group">
                        <label for="from">Desde</label>
                        <input type="date" class="form-control" name="from" id="from" value="{{ $requestFrom ?? '' }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="until">Hasta</label>
                        <input type="date" class="form-control" name="until" id="until" value="{{ $requestUntil ?? '' }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="clienteSaint" class="fw-bold">Cliente Saint</label>
                        <select name="codclie" id="clienteSaint" class="form-control js-example-basic-single">
                            <option value="">Elige una Opción</option>
                            @foreach($saclie as $item)
                                <option value="{{ $item->codclie }}" {{ $requestCodclie ?  ($item->codclie == $requestCodclie ? 'selected' : '') : '' }}>{{ $item->descrip }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mt-2 shadow rounded"><i class="fas fa-search"></i> Consultar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-lg-12">
        <div class="card card-primary">
            <div class="card-header">
                <h5>Listado de Cobros</h5>
            </div>
                 
            <div class="card-block dt-responsive table-responsive">
                <table id="cxc-report-table" class="table table-bordered table-striped small">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Observación</th>
                            <th>Descripción</th>
                            <th>Moneda</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Comprobante</th>
                            <th>Usuario</th>
                          </tr>
                    </thead>
                    <tbody>
                        @foreach($abonos as $reg)
                        <tr>
                            <td><p>{{$reg->codcxc}}</p></td>
                            <td><p>{{date('d/m/Y', strtotime($reg->fecha))}}</p></td>
                            <td><p>{{$reg->cxc->cliente ?? 'N/A'}}</p></td>
                            <td><p>{{$reg->observacion}}</p></td>
                            <td><p>{{$reg->descripcion}}</p></td>
                            <td><p>{{$reg->tipomoneda->moneda->siglas ?? 'N/A'}}</p></td>
                            <td><p>{{$reg->tipomoneda->nombre ?? 'N/A'}}</p></td>
                            <td><p>{{number_format($reg->monto, 2, ',', '.')}}</p></td>
                            <td>
                                @if($reg->file)
                                    <button class="btn btn-info" onclick="openBase64Image('{{ $reg->file }}')" data-toggle="tooltip" data-placement="top"
                                        title="Ver Comprobante">
                                        <i class="fas fa-file-invoice"></i>
                                    </button>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <p>{{$reg->user->name}}</p>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar Comprobante -->
<div class="modal fade" id="comprobanteModal" tabindex="-1" aria-labelledby="comprobanteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comprobanteModalLabel">Comprobante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="comprobanteImg" src="" alt="Comprobante" class="img-fluid" />
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openBase64Image(base64) {
    // Asignamos el base64 de la imagen al modal
    document.getElementById('comprobanteImg').src = base64;

    // Abrimos el modal
    $('#comprobanteModal').modal('show');
}
</script>
@endsection