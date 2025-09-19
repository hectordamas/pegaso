@extends('layouts.admin')
@section('metadata')
<title>{{ $savend->descrip }} - {{ env('APP_NAME') }}</title>
@endsection

@section('content')

<input type="hidden" name="mes" id="mes" value="{{ $mes }}">
<input type="hidden" name="savendId" id="savendId" value="{{ $id }}">

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
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Tabla totalizadora aparte --}}
                            <div class="mt-4" id="totalesComisiones">

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

<script>
    $(function(){
        getDetallesComisionesData();
    });

    function getDetallesComisionesData(){
        $("#loadingSpinner").css("display", "flex"); 

        if ($.fn.DataTable.isDataTable('#ventasTable')) {
            $('#ventasTable').dataTable().fnClearTable();
			$('#ventasTable').dataTable().fnDestroy();
        }

        $('#ventasTable').DataTable({
            order: [[0, 'desc']],
            "bDeferRender": true,
            "bProcessing": true,
            "sAjaxSource": "{{ route('comisionesDetallesTable') }}",
            "fnServerData": function ( sSource, aoData, fnCallback ) {
	    		$.getJSON( sSource, aoData, function (json) { 
	    			fnCallback(json)
                    $("#loadingSpinner").css("display", "none"); 
                    if (json.totalesComisiones) {
                        $('#totalesComisiones').html(json.totalesComisiones);
                    }
	    		});
	    	},
            "bPaginate": true,
            "sPaginationType":"full_numbers",
            "iDisplayLength": 20,
            "fnServerParams": function ( aoData ) {
                var mes = $('#mes').val();
                var savendId = $('#savendId').val();

	    		aoData.push( { "name": "mes", "value": mes } );
	    		aoData.push( { "name": "savendId", "value": savendId } );
            },
            dom: 'Bfrtip',
	    	buttons: [
	    		{
	    			extend: 'copy',
	    			text: 'Copiar',
	    			action: protegerExportacion($.fn.dataTable.ext.buttons.copyHtml5.action)
	    		},
	    		{
	    			extend: 'csv',
	    			text: 'Exportar CSV',
	    			action: protegerExportacion($.fn.dataTable.ext.buttons.csvHtml5.action)
	    		},
	    		{
	    			extend: 'excel',
	    			text: 'Exportar Excel',
	    			action: protegerExportacion($.fn.dataTable.ext.buttons.excelHtml5.action)
	    		},
	    		{
	    			extend: 'pdf',
	    			text: 'Exportar PDF',
	    			action: protegerExportacion($.fn.dataTable.ext.buttons.pdfHtml5.action)
	    		},
	    		{
	    			extend: 'print',
	    			text: 'Imprimir',
	    			action: protegerExportacion($.fn.dataTable.ext.buttons.print.action)
	    		}
	    	],	            
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            pageLength: 20,
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                "sInfoFiltered": "(filtrado de _MAX_ registros)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }
            }
        });
    }


</script>
@endsection