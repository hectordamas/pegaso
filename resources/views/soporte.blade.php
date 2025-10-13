@extends('layouts.admin')

@section('metadata')
<title>Soporte - {{ env('APP_NAME') }}</title>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Consultar Servicios de Soporte</h5>
                <span>📌 Completa los campos para filtrar y consultar servicios de Soporte.</span>
            </div>
            <div class="card-block">
                <div class="row" id="soporteCreateForm">
                    
                    <div class="col-md-3 form-group">
                        <label for="client">Cliente</label>
                        <select name="client" id="client" class="form-control js-example-basic-single">
                            <option value="">Elige una Opción</option>
                            @foreach($saclie as $c)
                                <option value="{{ $c->codclie }}" {{ $client == $c->codclie ? 'selected' : ''}}>{{ $c->descrip }} | {{$c->rif}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mt-3" id="consultarSoporte">
                            <i class="fas fa-search"></i> Consultar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Soporte</h5>
                <span>📋 Reporte / Historial de Soporte.</span>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="soporte-table" class="table table-striped table-bordered nowrap table-small">
                            <thead class="table-dark">
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Documento</th>
                                    <th>Cliente</th>
                                    <th>Exento</th>
                                    <th>Base Imponible</th>
                                    <th>IVA</th>
                                    <th>Factor</th>
                                    <th>Total Bs.</th>
                                    <th>Total USD.</th>
                                    <th>Vendedor</th>
                                    <th>Abonado</th>
                                    <th>Estatus</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="soporteTbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal Para ver detalles de soporte -->
<div class="modal fade SoporteModalView" tabindex="-1" id="SoporteModalView" aria-labelledby="SoporteModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="SoporteModalViewLabel">Detalles del Servicio #<span class="SoporteId"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productosSoporte">

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
    function convertirmonto(input){
		$(".montopcd").on({
			"focus": function (event) {
				$(event.target).select();
			},
			"keyup": function (event) {
				$(event.target).val(function (index, value ) {
					return value.replace(/\D/g, "")
						.replace(/([0-9])([0-9]{2})$/, '$1,$2')
						.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
				});	   
			}
		});
	}

    var soporteTable;

    $(function(){
        getSoporteData();
    });

    function getSoporteData(updated) {
        const exp = $('#exp').val();

        $("#loadingSpinner").css("display", "flex"); 

        if ($.fn.DataTable.isDataTable('#soporte-table')) {
            $('#soporte-table').dataTable().fnClearTable();
			$('#soporte-table').dataTable().fnDestroy();
        }

        let id = $(this).data('id')
        let codclie = $('#client').val();
        let codvend = $('#codvend').val();
        let codestatus =  id || $('#estatus').val(); // this ahora está correctamente referenciado
        let registra = $('#registra').val();
        $('#soporte-table').DataTable({
            columns: [
                { visible: false },           // columna 0: fecha cruda (oculta, para orden)
                null,                         // columna 1: ID
                { orderData: [0] },           // columna 2: fecha formateada, ordena según la columna 0
                null,                         // columna 3: días
                null,                         // columna 4: número doc
                null,                         // columna 5: descripción
                null,                         // columna 6: exento
                null,                         // columna 7: gravable
                null,                         // columna 8: impuesto
                null,                         // columna 9: factor
                null,                         // columna 10: total
                null,                         // columna 11: total / factor
                null,                         // columna 12: vendedor
                null,                         // columna 13: estatus
                null
            ],
            order: [[0, 'desc']],
            "bDeferRender": true,
            "bProcessing": true,
            "sAjaxSource": "{{ route('soporte.data') }}",
            "fnServerData": function ( sSource, aoData, fnCallback ) {
				$.getJSON( sSource, aoData, function (json) { 
					fnCallback(json)

                    $("#loadingSpinner").css("display", "none"); 

				} );
			},
            "bPaginate": true,
            "sPaginationType":"full_numbers",
            "iDisplayLength": 20,
            "fnServerParams": function ( aoData ) {
				aoData.push( { "name": "codclie", "value": codclie } );
                aoData.push( { "name": "registra", "value": registra } );
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

    // Ver Detalles
    window.soporteDetalles = function(soporteId){
        $("#loadingSpinner").css("display", "flex");

		$.ajax({
			url: '{{ url("soporte/ver-detalles") }}' + '/' + soporteId, // Asegúrate de que esta ruta sea correcta
			method: 'POST',
			data: {
				_token: '{{ csrf_token() }}'  // CSRF Token para seguridad
			},
			success: function(response) {
                $('.proyectoId').html(proyectoId);
                $("#ProyectoModalView").modal("show")
				$('#productosProyecto').html(response.items);  
                $("#loadingSpinner").css("display", "none");
  
			},
			error: function(response){
				alert('Error de Conexión')
				$("#loadingSpinner").css("display", "none");
			}
		});
	}

    $('.getSoportesByStatus').on('click', function() {
        getSoporteData.call(this);
    });
    
    $('#consultarSoportes').on('click', function() {
        getSoporteData();
    });
    
    //Actualizar item safactitem de proyecto
    $(document).on('change', '.update-status', function() {
        let checkbox = $(this);
        let itemId = checkbox.data('id');  // Obtener el ID del item
        let newStatus = checkbox.is(':checked') ? 1 : 0; // Determinar el nuevo valor

        $.ajax({
            url: "{{ route('actualizar.saitemfac') }}", // Ruta del controlador
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: itemId,
                valor: newStatus
            },
            success: function(response) {
                console.log(response)
            },
            error: function(error) {
                console.log(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el estado.'
                });
                checkbox.prop('checked', !newStatus); // Revertir en caso de error
            }
        });
    });

</script>

@endsection
