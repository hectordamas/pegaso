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
                                <table id="ventasTable" class="table table-striped table-bordered nowrap">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th> 
                                            <th>Presupuesto</th> 
                                            <th>Fecha Presupuesto</th> 
                                            <th>Último Pago</th> 
                                            <th>Tasa (Bs.)</th> 
                                            <th>Cliente</th> 
                                            <th>Base Imponible ($)</th> 
                                            <th>Total Productos</th> 
                                            <th>Total Servicios</th> 
                                            <th>Cobrado Productos ($)</th> 
                                            <th>Cobrado Servicios ($)</th> 
                                            <th>Total Cobrado ($)</th> 
                                            <th>Cobrado (%)</th> 
                                            <th>Comisión Producto ($)</th> 
                                            <th>Comisión Servicio ($)</th> 
                                            <th>Total Comisión ($)</th> 
                                            <th>Pendiente Por Cobrar ($)</th> 
                                            <th>Pendiente (%)</th> 
                                            <th>Detalle Pagos</th>
                                            <th>Detalle Presupuesto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalBaseImponible = 0;
                                            $totalPendienteProductos = 0;
                                            $totalPendienteServicios = 0;
                                            $totalAbonadoProductos = 0;
                                            $totalAbonadoServicios = 0;
                                            $totalAbonosMes = 0;
                                            $totalComisionProductos = 0;
                                            $totalComisionServicios = 0;
                                            $totalComision = 0;
                                            $totalPendiente = 0;
                                        @endphp
                            
                                        @foreach ($safacts as $safact)
                                            @php
                                                $totalBaseImponible += $safact->getBaseImponibleRestante(8, 2025);
                                                $totalPendienteProductos += $safact->pendienteProductosMes(8, 2025);
                                                $totalPendienteServicios += $safact->pendienteServiciosMes(8, 2025);
                                                $totalAbonadoProductos += $safact->abonadoProductosMes(8, 2025);
                                                $totalAbonadoServicios += $safact->abonadoServiciosMes(8, 2025);
                                                $totalAbonosMes += $safact->getAbonosMesActual(8, 2025);
                                                $totalComisionProductos += $safact->totalComisionProducto(8, 2025);
                                                $totalComisionServicios += $safact->totalComisionServicio(8, 2025);
                                                $totalComision += $safact->totalComision(8, 2025);
                                                $totalPendiente += $safact->pendiente(8, 2025);
                                            @endphp
                            
                                            <tr>
                                                <td>{{ $safact->id }}</td>
                                                <td>{{ $safact->numerod }}</td>
                                                <td>{{ \Carbon\Carbon::parse($safact->fechae)->format('d-m-Y') }}</td>
                                                <td>
                                                    {{ optional(optional($safact->cxc)->ultimoPago)->fechaDePago
                                                        ? \Carbon\Carbon::parse($safact->cxc->ultimoPago->fechaDePago)->format('d-m-Y')
                                                        : '-' }}
                                                </td>
                                                <td>{{ number_format($safact->factor, 2) }}</td>
                                                <td>{{ $safact->descrip }}</td>
                                                <td>{{ number_format($safact->getBaseImponibleRestante(8, 2025), 2, '.', ',') }}</td>
                                                <td>{{ number_format($safact->pendienteProductosMes(8, 2025), 2, '.', ',') }}</td>
                                                <td>{{ number_format($safact->pendienteServiciosMes(8, 2025), 2, '.', ',') }}</td>
                                                <td>{{ number_format($safact->abonadoProductosMes(8, 2025), 2, '.', ',') }}</td>
                                                <td>{{ number_format($safact->abonadoServiciosMes(8, 2025), 2, '.', ',') }}</td>
                                                <td>{{ number_format($safact->getAbonosMesActual(8, 2025), 2, '.', ',')  }}</td>
                                                <td>{{ round($safact->abonadoPorcentaje(8, 2025))  }}%</td>
                                            
                                                <td>{{ number_format($safact->totalComisionProducto(8, 2025), 2, '.', ',') }}</td>
                                                <td>{{ number_format($safact->totalComisionServicio(8, 2025), 2, '.', ',') }}</td>
                                                <td>{{ number_format($safact->totalComision(8, 2025), 2, '.', ',') }}</td>
                                            
                                                <td>{{ number_format($safact->pendiente(8, 2025), 2, '.', ',')  }}</td>
                                                <td>{{ round($safact->pendientePorcentaje(8, 2025))  }}%</td>
                                                <td>
                                                    <a 
                                                        href="javascript:void(0)" 
                                                        onclick="cargarComprobantes({{$safact->id}})" 
                                                        class="btn btn-info"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#filesOffCanvas">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" 
                                                        onclick="presupuestoDetalles({{ $safact->id }})"
                                                        class="btn btn-warning">
                                                        <i class="fas fa-list"></i> Ver Detalles
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Tabla totalizadora aparte --}}
                            <div class="mt-4">
                                <h5>Totales</h5>
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th class="bg-dark text-light">Base Imponible</th>
                                        <td>${{ number_format($totalBaseImponible, 2, '.', ',') }}</td>
                                        <th class="bg-dark text-light">Total Productos</th>
                                        <td>${{ number_format($totalPendienteProductos, 2, '.', ',') }}</td>
                                        <th class="bg-dark text-light">Total Servicios</th>
                                        <td>${{ number_format($totalPendienteServicios, 2, '.', ',') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-light">Cobrado Productos.</th>
                                        <td>${{ number_format($totalAbonadoProductos, 2, '.', ',') }}</td>
                                        <th class="bg-dark text-light">Cobrado Servicios.</th>
                                        <td>${{ number_format($totalAbonadoServicios, 2, '.', ',') }}</td>
                                        <th class="bg-dark text-light">Total Cobrado</th>
                                        <td>${{ number_format($totalAbonosMes, 2, '.', ',') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-light">Comisión Productos</th>
                                        <td>${{ number_format($totalComisionProductos, 2, '.', ',') }}</td>
                                        <th class="bg-dark text-light">Comisión Servicio</th>
                                        <td>${{ number_format($totalComisionServicios, 2, '.', ',') }}</td>
                                        <th class="bg-dark text-light">Total Comisión</th>
                                        <td>${{ number_format($totalComision, 2, '.', ',') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-light">Total Pendiente</th>
                                        <td>${{ number_format($totalPendiente, 2, '.', ',') }}</td>
                                        <th class="bg-dark text-light">% Cobrado</th>
                                        <td>
                                            {{ $totalBaseImponible > 0 
                                                ? round((($totalAbonadoProductos + $totalAbonadoServicios) / $totalBaseImponible) * 100) 
                                                : 0 }}%
                                        </td>
                                        <th class="bg-dark text-light">% Pendiente</th>
                                        <td>
                                            {{ $totalBaseImponible > 0 
                                                ? round(($totalPendiente / $totalBaseImponible) * 100) 
                                                : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-light">Comisión Productos (%)</th>
                                        <td>{{ $savend->comision_producto }}%</td>
                                        <th class="bg-dark text-light">Comisión Servicio (%)</th>
                                        <td>{{ $savend->comision_servicio }}%</td>
                                        <th class="bg-dark text-light">Comisión Gerencial (%)</th>
                                        <td>{{ $savend->comision_gerencia ? $savend->comision_gerencia .'%': 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="mt-4">
                                @if($savend->comision_gerencia)
                                <table class="table table-bordered ">
                                    <tr>
                                        <th class="bg-dark text-light">Comisión Gerencial</th>
                                        <td colspan="5">
                                            ${{ number_format($savend->getComisionGerencial(8, 2025), 2, '.', ',') }}
                                            <span class="text-muted">
                                                ({{ $savend->comision_gerencia }}% sobre todo el departamento)
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                                @endif
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

<!-- Modal Para ver detalles de Presupuesto -->
<div class="modal fade PresupuestoModalView" tabindex="-1" id="PresupuestoModalView" aria-labelledby="PresupuestoModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="PresupuestoModalViewLabel">Detalles del Presupuesto #<span class="presupuestoId"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productosPresupuesto">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="far fa-times-circle"></i> Cerrar
                </button>
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

    function presupuestoDetalles (presupuestoId){
        $("#loadingSpinner").css("display", "flex");

		$.ajax({
			url: '{{ url("presupuestos/ver-detalles") }}' + '/' + presupuestoId, // Asegúrate de que esta ruta sea correcta
			method: 'POST',
			data: {
				_token: '{{ csrf_token() }}'  // CSRF Token para seguridad
			},
			success: function(response) {
                $('.presupuestoId').html(presupuestoId);
                $("#PresupuestoModalView").modal("show")
				$('#productosPresupuesto').html(response.items);  
                $("#loadingSpinner").css("display", "none");
  
			},
			error: function(response){
				alert('Error de Conexión')
				$("#loadingSpinner").css("display", "none");
			}
		});
	}
</script>
@endsection