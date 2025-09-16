@extends('layouts.admin')
@section('metadata')
<title>{{ $savend->descrip }} - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>
                    {{ $savend->descrip }} - Detalle de Comisiones
                </h5>

                <a href="{{ url('comisiones') }}" class="btn btn-inverse shadow rounded">
                    <i class="fas fa-arrow-left"></i> Tabla de Comisiones
                </a>
            </div>
            <div class="card-block">
                <ul class="nav nav-tabs md-tabs mb-4" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-bold active" id="thismonth-tab" data-bs-toggle="tab" href="#thismonth" type="button" role="tab">
                            💸 Abonado Este Mes
                        </a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-bold" id="info-tab" data-bs-toggle="tab" href="#info" type="button" role="tab">
                            🧾 Por cobrar (Meses Anteriores)
                        </a>
                        <div class="slide"></div>
                    </li>

                </ul>   
                
                <div class="tab-content" id="settingsTabContent">
                
                    <!-- Abonado Este Mes -->
                    <div class="tab-pane fade show active" id="thismonth" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12 table-responsive dt-responsive">
                                <table class="table table-striped" id="atencion-clientes-table">
                                    <thead class="table-dark">
                                        <th>#</th>
                                        <th>Presupuesto</th>
                                        <th>Fecha Presupuesto</th>
                                        <th>Último Pago</th>
                                        <th>Tasa (Bs.)</th>
                                        <th>Cliente</th>
                                        <th>Base Imponible ($)</th>
                                        <th>Total Productos ($)</th>
                                        <th>Total Servicios ($)</th>
                                        <th>Total Abonado ($)</th>
                                        <th>Total Abonado Productos ($)</th>
                                        <th>Total Abonado Servicios ($)</th>
                                        <th>Comisión Productos ($)</th>
                                        <th>Comisión Servicios ($)</th>
                                        <th>Total Comisiones ($)</th>
                                        <th>Pendiente Por Cobrar ($)</th>
                                        <th>% Pendiente Por Cobrar ($)</th>
                                        <th>Detalle Pagos</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($safacts as $safact)
                                            <tr>
                                                <td>{{ $safact->id }}</td>
                                                <td>{{ $safact->numerod }}</td>
                                                <td>{{ Carbon\Carbon::parse($safact->fechae)->format('d-m-Y') }}</td>
                                                <td>{{ Carbon\Carbon::parse(optional($safact->cxc->ultimoPago)->fechaDePago)->format('d-m-Y')  }}</td>
                                                <td>{{ $safact->factor }}</td>
                                                <td>{{ $safact->descrip }}</td>
                                                <td>{{ number_format($safact->tgravable / ($safact->factor ?  $safact->factor : 1), 2, ',', '.') }}</td>
                                                <td>{{ number_format($safact->monto_total_productos, 2, ',', '.') }}</td>
                                                <td>{{ number_format($safact->monto_total_servicios, 2, ',', '.') }}</td>
                                                <td>{{ number_format($safact->total_abonado, 2, ',', '.') }}</td>
                                                <td>{{ number_format($safact->total_abonado_productos, 2, ',', '.') }}</td>
                                                <td>{{ number_format($safact->total_abonado_servicios, 2, ',', '.') }}</td>
                                                <td>{{ number_format($safact->total_comision_productos, 2, ',', '.') }}</td>
                                                <td>{{ number_format($safact->total_comision_servicios, 2, ',', '.') }}</td>
                                                <td>{{ number_format($safact->total_comisiones, 2, ',', '.') }}</td>
                                                <td>{{ number_format($safact->pendiente, 2, ',', '.') }}</td>
                                                <td>{{ $safact->porcentaje_pendiente }}%</td>
                                                <td> 
                                                    <a 
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="Ver Comprobantes"
                                                        href="javascript:void(0);"
                                                        class="btn btn-outline-info"
                                                        onclick="cargarComprobantes({{ $safact->id }})"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#filesOffCanvas">
                                                        <i class="fas fa-file-invoice"></i>                                     
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                
                    <!-- Por cobrar meses anteriores -->
                    <div class="tab-pane fade" id="info" role="tabpanel">

                    </div>

 
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas archivos -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filesOffCanvas" aria-labelledby="filesOffCanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Comprobantes Adjuntos</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" id="comprobantesAdjuntosSafact">
      <p class="text-center">Cargando...</p>
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
                <iframe id="comprobantePdf" allow="fullscreen" src="" style="width: 100%; height: 80vh;" frameborder="0" class="d-none"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function cargarComprobantes(safactId) {
        $('#comprobantesAdjuntosSafact').html('<p class="text-center">Cargando...</p>');
        $('#filesOffCanvas').offcanvas('show');

        $.get(`/comisiones/safact/${safactId}/comprobantes`, function(data) {
            $('#comprobantesAdjuntosSafact').html(data.html);
        });
    }

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