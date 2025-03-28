@extends('layouts.admin')

@section('metadata')
    <title>Presupuestos - {{ env("APP_NAME") }}</title>
@endsection

@section('styles')
<style>
    .status-card {
        cursor: pointer;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }

    .status-card:active {
        transform: scale(0.95);
    }
</style>
@endsection

@section('content')
<div class="row">
    @php
        $estatusCollection = [
            ['id' => 1, 'name' => 'Pendientes', 'count' => 0, 'color' => 'warning', 'icon' => 'fas fa-hourglass-half'],
            ['id' => 2, 'name' => 'Aprobados', 'count' => 0, 'color' => 'success', 'icon' => 'fas fa-check-circle'],
            ['id' => 3, 'name' => 'Proyectos', 'count' => 0, 'color' => 'primary', 'icon' => 'fas fa-project-diagram'],
            ['id' => 4, 'name' => 'Completados', 'count' => 0, 'color' => 'dark', 'icon' => 'fas fa-clipboard-check'],
            ['id' => 5, 'name' => 'Rechazados', 'count' => 0, 'color' => 'danger', 'icon' => 'fas fa-times-circle'],
            ['id' => 6, 'name' => 'Descartados', 'count' => 0, 'color' => 'secondary', 'icon' => 'fas fa-trash-alt'],
        ];
    @endphp

{{-- Consultar Presupuestos por Estatus --}}

    @foreach($estatusCollection as $status)
    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
        <div class="card text-white bg-{{ $status['color'] }} shadow status-card getPresupuestosByStatus" data-id="{{ $status['id'] }}">
            <div class="card-body text-center">
                <i class="{{ $status['icon'] }} fa-3x mb-2"></i>
                <h5 class="mt-2">{{ $status['name'] }}</h5>
                <h4 class="fw-bold count{{ $status['id'] }}">0</h4>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Consultar Presupuestos por filtros --}}

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Consultar Presupuestos</h5>
                <span>💸 Completa los campos para filtrar y consultar Presupuestos.</span>
            </div>
            <div class="card-block">
                <div class="row" id="presupuestoCreateForm">
                    <div class="col-md-3 form-group">
                        <label for="from">Desde</label>
                        <input type="date" class="form-control" name="from" id="from">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="until">Hasta</label>
                        <input type="date" class="form-control" name="until" id="until">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="codvend">Vendedor</label>
                        <select name="codvend" id="codvend" class="form-control">
                            <option selected value="">Elige una Opción</option>
                            @foreach($vendedores as $vendedor)
                                <option value="{{ $vendedor->codvend }}">{{ $vendedor->descrip }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mt-3" id="consultarPresupuestos"><i class="fas fa-search"></i> Consultar</button>
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
                <h5>Presupuestos</h5>
                <span>📋 Reporte / Historial de Presupuestos.</span>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="presupuestos-table" class="table table-striped table-bordered nowrap table-small">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Días</th>
                                    <th>Documento</th>
                                    <th>Cliente</th>
                                    <th>Exento</th>
                                    <th>Base Imponible</th>
                                    <th>IVA</th>
                                    <th>Factor</th>
                                    <th>Total Bs.</th>
                                    <th>Total USD.</th>
                                    <th>Vendedor</th>
                                    <th>Estatus</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="presupuestosTbody">

                                {{-- @include('presupuestos.tr-index') --}}
                            </tbody>
                        </table>
                    </div>
                </div>
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


<!-- Modal Cambiar Estatus de Presupuesto -->
<div class="modal fade PresupuestoModalEdit" tabindex="-1" id="PresupuestoModalEdit" aria-labelledby="PresupuestoModalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="PresupuestoModalEditLabel">Cambiar Estatus de Presupuesto #<span class="presupuestoId"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="presupuestoUpdateform" method="POST">
                    @csrf

                    <input type="hidden" name="presupuestoId" id="presupuestoId">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <select name="codestatus" class="form-control" id="estatusPresupuestoId" required>
                                    @foreach($estatus->whereNotIn('id', [1, 12, 13]) as $e)
                                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 form-group d-none" id="razon-container">
                            <label for="" class="control-label" id="razonLabel"></label>
                            <textarea name="razon" id="razon" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="far fa-times-circle"></i> Cerrar
                        </button>
                        <button id="presupuestoUpdateStatusButton" class="btn btn-success float-center">
                            <i class="fas fa-sync"></i> Actualizar Estatus
                        </button>
                    </div>
                </div> <!-- Cierre del formulario -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    var presupuestosTable;

    $(function(){
        getPresupuestosData();
    });

    function getPresupuestosData(updated) {
        $("#loadingSpinner").css("display", "flex"); 

        if ($.fn.DataTable.isDataTable('#presupuestos-table')) {
            $('#presupuestos-table').dataTable().fnClearTable();
			$('#presupuestos-table').dataTable().fnDestroy();
        }

        let id = $(this).data('id')
        let from = $('#from').val();
        let until = $('#until').val();
        let codvend = $('#codvend').val();
        let codestatus =  id || $('#codestatus').val(); // this ahora está correctamente referenciado

        $('#presupuestos-table').DataTable({
            "bDeferRender": true,
            "bProcessing": true,
            "sAjaxSource": "{{ route('presupuestos.data') }}",
            "fnServerData": function ( sSource, aoData, fnCallback ) {
				$.getJSON( sSource, aoData, function (json) { 
					fnCallback(json)

                    $("#loadingSpinner").css("display", "none"); 

                    if (!id) {
                        $('.count1').html(json.pendientes);
                        $('.count2').html(json.aprobados);
                        $('.count3').html(json.proyectos);
                        $('.count4').html(json.completados);
                        $('.count5').html(json.rechazados);
                        $('.count6').html(json.descartados);
                    }

                    if (updated) {
                        Swal.fire({
                            text: "Estatus actualizado con éxito!",
                            icon: "success",
                        });
                    }
				} );
			},
            "bPaginate": true,
            "sPaginationType":"full_numbers",
            "iDisplayLength": 20,
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "from", "value": from } );
				aoData.push( { "name": "until", "value": until } );
				aoData.push( { "name": "codvend", "value": codvend } );
				aoData.push( { "name": "codestatus", "value": codestatus } );
            },
			order: [[0, 'desc']],
            dom: 'Bfrtip',
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
    window.presupuestoDetalles = function(presupuestoId){
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

    $('.getPresupuestosByStatus').on('click', function() {
        getPresupuestosData.call(this);
    });
    
    $('#consultarPresupuestos').on('click', function() {
        getPresupuestosData();
    });
    

    //Actualizar Estatus de Presupuesto
    $('#presupuestoUpdateStatusButton').on('click', function(){
        var $codestatus = $('#estatusPresupuestoId').val();
        var $presupuestoId = $('#presupuestoId').val()
        var $razon = $('#razon').val()
        $.ajax({
			url: '{{ route("presupuestos.update") }}', // Asegúrate de que esta ruta sea correcta
			method: 'POST',
			data: {
				_token: '{{ csrf_token() }}',  // CSRF Token para seguridad
                codestatus: $codestatus,
                presupuestoId: $presupuestoId,
                razon: $razon
			},
			success: function(response) {
                getPresupuestosData(response.success);
                $("#PresupuestoModalEdit").modal("hide")
			},
			error: function(response){
				alert('Error de Conexión')
                console.log(response)
				$("#loadingSpinner").css("display", "none");
			}
		});
    })

    $('#PresupuestoModalEdit').addEventListener('show.bs.modal', function () {
        $('#razon').val('');
    });

    $("#estatusPresupuestoId").on('input', function(){
        var codestatus = $(this).val();
        $('#razon').val('');

        if (codestatus == 5 || codestatus == 6) {
            $('#razon-container').removeClass('d-none'); // Muestra el campo
            codestatus == 5 ? $('#razonLabel').html('Razón del Rechazo:') : $('#razonLabel').html('Razón del Descarte:');
        } else {
            $('#razon-container').addClass('d-none'); // Oculta el campo si no es 5 o 6
        }
    });

</script>
@endsection


