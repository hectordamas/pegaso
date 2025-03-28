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
                    <div class="col-md-4 form-group">
                        <label for="from">Desde</label>
                        <input type="date" class="form-control" name="from" id="from" value="{{ $requestFrom ?? '' }}">
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="until">Hasta</label>
                        <input type="date" class="form-control" name="until" id="until" value="{{ $requestUntil ?? '' }}">
                    </div>

                    <div class="col-md-4 form-group d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-search"></i> Consultar</button>
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
                            <td><p>{{$reg->cliente}}</p></td>
                            <td><p>{{$reg->observacion}}</p></td>
                            <td><p>{{$reg->descripcion}}</p></td>
                            <td><p>{{$reg->moneda ?? 'N/A'}}</p></td>
                            <td><p>{{$reg->tipomoneda ?? 'N/A'}}</p></td>
                            <td><p>{{number_format($reg->monto, 2, ',', '.')}}</p></td>
                            <td>
                                @if($reg->file)
                                    <a href="javascript:void(0)" onclick="openBase64Image('{{ $reg->file }}')" target="_blank">
                                        <img src="{{ $reg->file }}" style="max-width: 80px;"/>
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <p>{{$reg->usuario}}</p>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openBase64Image(base64) {
        let newWindow = window.open('about:blank');
        newWindow.document.write(`
            <html>
            <head>
                <title>Comprobante</title>
                <style>
                    body { 
                        background-color: black; 
                    }
                </style>
            </head>
            <body>
                <img src="${base64}" />
            </body>
            </html>
        `);
    }
</script>
@endsection