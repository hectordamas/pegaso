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

                        <div class="col-md-6">
                            <div class="row">
                                <hr>
                                <label>Fecha de Registro</label>
                                <hr>
                                <div class="col-md-6 form-group">
                                    <label for="from">Desde</label>
                                    <input type="date" class="form-control" name="from" id="from"
                                        value="{{ $requestFrom ?? '' }}">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="until">Hasta</label>
                                    <input type="date" class="form-control" name="until" id="until"
                                        value="{{ $requestUntil ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <hr>
                                <label>Fecha de Pago</label>
                                <hr>
                                <div class="col-md-6 form-group">
                                    <label for="from">Desde</label>
                                    <input type="date" class="form-control" name="fechaDePago_from" id="fechaDePago_from"
                                        value="{{ $requestFromFechaDePago ?? '' }}">
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="until">Hasta</label>
                                    <input type="date" class="form-control" name="fechaDePago_until"
                                        id="fechaDePago_until" value="{{ $requestUntilFechaDePago ?? '' }}">
                                </div>
                            </div>
                        </div>


                        <div class="form-group col-md-3">
                            <label for="clienteSaint" class="fw-bold">Cliente Saint</label>
                            <select name="codclie" id="clienteSaint" class="form-control js-example-basic-single">
                                <option value="">Elige una Opción</option>
                                @foreach ($saclie as $item)
                                    <option value="{{ $item->codclie }}"
                                        {{ $requestCodclie ? ($item->codclie == $requestCodclie ? 'selected' : '') : '' }}>
                                        {{ $item->descrip }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="clienteSaint" class="fw-bold">Usuario</label>
                            <select name="userId" id="userId" class="form-control js-example-basic-single">
                                <option value="">Elige una Opción</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->codusuario }}" {{ $userId ? ($user->id == $userId ? 'selected' : '') : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 form-group d-flex align-items-center">
                            <button type="submit" class="btn btn-primary mt-2 shadow rounded"><i class="fas fa-search"></i>
                                Consultar</button>
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
                                <th>Fecha de Pago</th>
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
                            @foreach ($abonos as $reg)
                                <tr>
                                    <td>
                                        <p>{{ $reg->codcxc }}</p>
                                    </td>
                                    <td>
                                        <p>{{ date('d/m/Y', strtotime($reg->fecha)) }}</p>
                                    </td>
                                    <td>{{ $reg->fechaDePago ? \Carbon\Carbon::parse($reg->fechaDePago)->format('d-m-Y') : 'N/R' }}
                                    </td>
                                    <td>
                                        <p>{{ $reg->cxc->cliente ?? 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $reg->observacion }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $reg->descripcion }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $reg->tipomoneda->moneda->siglas ?? 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $reg->tipomoneda->nombre ?? 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <p>{{ number_format($reg->monto, 2, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        @php
                                            $isPdf = str_starts_with($reg->file, 'data:application/pdf');
                                        @endphp
                                        <button class="btn btn-info" onclick="openBase64Image('{{ $reg->file }}')"
                                            title="Ver Comprobante">
                                            @if ($isPdf)
                                                <i class="fas fa-file-pdf"></i>
                                            @else
                                                <i class="fas fa-file-image"></i>
                                            @endif
                                        </button>
                                    </td>
                                    <td>
                                        <p>{{ $reg->user->name }}</p>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>


                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="bg-dark text-light fw-bold">Total</td>
                                <td>${{ number_format($total, 2, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
            </div>
        </div>
    </div>

    <!-- Modal para mostrar Comprobante -->
    <div class="modal fade" id="comprobanteModal" tabindex="-1" aria-labelledby="comprobanteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="comprobanteModalLabel">Comprobante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Imagen -->
                    <img id="comprobanteImg" src="" alt="Comprobante" class="img-fluid d-none" />

                    <!-- PDF -->
                    <iframe id="comprobantePdf" allow="fullscreen" src="" style="width: 100%; height: 80vh;"
                        frameborder="0" class="d-none"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openBase64Image(base64) {
            const mime = base64.substring(5, base64.indexOf(';'));

            const img = document.getElementById('comprobanteImg');
            const pdf = document.getElementById('comprobantePdf');

            if (mime === 'application/pdf') {
                // Mostrar PDF
                img.classList.add('d-none');
                pdf.classList.remove('d-none');
                pdf.src = base64;
            } else {
                // Mostrar imagen
                pdf.classList.add('d-none');
                img.classList.remove('d-none');
                img.src = base64;
            }

            $('#comprobanteModal').modal('show');
        }
    </script>
@endsection
