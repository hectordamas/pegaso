@extends('layouts.admin')

@section('metadata')
<title>Proyectos - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')
<style>
    .estatus-container {
        overflow-x: auto; /* Activa el scroll horizontal */
        white-space: nowrap; /* Evita el salto de línea */
        padding: 10px 0;
    }

    .estatus-row {
        display: flex;
        flex-wrap: nowrap; /* Evita que los elementos se vayan a una nueva línea */
        gap: 15px; /* Espacio entre tarjetas */
    }

    .estatus-item {
        min-width: 200px; /* Asegura que cada tarjeta tenga un ancho mínimo */
        flex-shrink: 0; /* Evita que se reduzcan demasiado */
    }

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
            ['id' => 3, 'name' => 'Proyectos', 'count' => 0, 'color' => 'primary', 'icon' => 'fas fa-project-diagram'],
            ['id' => 4, 'name' => 'Completados', 'count' => 0, 'color' => 'success', 'icon' => 'fas fa-clipboard-check'],
            ['id' => 5, 'name' => 'Rechazados', 'count' => 0, 'color' => 'danger', 'icon' => 'fas fa-times-circle'],
            ['id' => 6, 'name' => 'Descartados', 'count' => 0, 'color' => 'secondary', 'icon' => 'fas fa-trash-alt'],
            ['id' => 7, 'name' => 'En Proceso', 'count' => 0, 'color' => 'info', 'icon' => 'fas fa-spinner'],
            ['id' => 8, 'name' => 'Ejecutado', 'count' => 0, 'color' => 'warning', 'icon' => 'fas fa-play-circle'],
            ['id' => 9, 'name' => 'Pausado', 'count' => 0, 'color' => 'warning', 'icon' => 'fas fa-pause-circle'],
            ['id' => 10, 'name' => 'Control de Calidad', 'count' => 0, 'color' => 'dark', 'icon' => 'fas fa-check-double'],
        ];
    @endphp


    {{-- Consultar Presupuestos por Estatus --}}

    <div class="estatus-container">
        <div class="estatus-row">
            @foreach($estatusCollection as $status)
            <div class="estatus-item">
                <div class="card text-white bg-{{ $status['color'] }} shadow status-card getProyectosByStatus" data-id="{{ $status['id'] }}">
                    <div class="card-body text-center">
                        <i class="{{ $status['icon'] }} fa-3x mb-2"></i>
                        <h5 class="mt-2">{{ $status['name'] }}</h5>
                        <h4 class="fw-bold count{{ $status['id'] }}">0</h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Consultar Proyectos</h5>
                <span>📌 Completa los campos para filtrar y consultar Proyectos.</span>
            </div>
            <div class="card-block">
                <div class="row" id="proyectoCreateForm">
                    
                    <div class="col-md-3 form-group">
                        <label for="client">Cliente</label>
                        <select name="client" id="client" class="form-control js-example-basic-single">
                            <option selected value="">Elige una Opción</option>
                            @foreach($saclie as $client)
                                <option value="{{ $client->codclie }}">{{ $client->descrip }} | {{$client->rif}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="codvend">Vendedor</label>
                        <select name="codvend" id="codvend" class="form-control">
                            <option selected value="">Elige una Opción</option>
                            @foreach($savend as $vendedor)
                                <option value="{{ $vendedor->codvend }}">{{ $vendedor->descrip }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="estatus">Estatus</label>
                        <select name="estatus" id="estatus" class="form-control">
                            <option selected value="">Elige una Opción</option>
                            @foreach($estatusPre as $e)
                                <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mt-3" id="consultarProyectos">
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
                <h5>Proyectos</h5>
                <span>📋 Reporte / Historial de Proyectos.</span>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="proyectos-table" class="table table-striped table-bordered nowrap table-small">
                            <thead class="table-dark">
                                <tr>
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
                                    <th>Estatus</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="proyectosTbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Abrir Chat de Proyecto -->
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title font-weight-bold" id="chatModalLabel">Chat de Proyecto</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background: #F0F2F5;">
                <!-- Aquí se cargarán los mensajes del chat dinámicamente -->
                <div id="chatContainer">

                </div>

                <div class="chat-input">
                    <div class="input-group mb-2">
                        <input type="hidden" id="codproyectoChat">
                        <input id="newMessage" class="form-control" rows="1" placeholder="Escribe tu mensaje..." />
                        <button id="sendMessage" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Enviar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Para ver detalles de Proyecto -->
<div class="modal fade ProyectoModalView" tabindex="-1" id="ProyectoModalView" aria-labelledby="ProyectoModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="ProyectoModalViewLabel">Detalles del Proyecto #<span class="proyectoId"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productosProyecto">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="far fa-times-circle"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Cambiar Estatus de Proyecto -->
<div class="modal fade ProyectoModalEdit" tabindex="-1" id="ProyectoModalEdit" aria-labelledby="ProyectoModalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="ProyectoModalEditLabel">Cambiar Estatus de Proyecto #<span class="proyectoId"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="proyectoUpdateform" method="POST">
                    @csrf

                    <input type="hidden" name="proyectoId" id="proyectoId">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <select name="codestatus" class="form-control" id="estatusProyectoId" required>
                                    @foreach($estatusPre->whereNotIn('id', [1]) as $e)
                                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="far fa-times-circle"></i> Cerrar
                        </button>
                        <button id="proyectoUpdateStatusButton" class="btn btn-success float-center">
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
        getProyectosData();
    });

    function getProyectosData(updated) {
        $("#loadingSpinner").css("display", "flex"); 

        if ($.fn.DataTable.isDataTable('#proyectos-table')) {
            $('#proyectos-table').dataTable().fnClearTable();
			$('#proyectos-table').dataTable().fnDestroy();
        }

        let id = $(this).data('id')
        let codclie = $('#client').val();
        let codvend = $('#codvend').val();
        let codestatus =  id || $('#estatus').val(); // this ahora está correctamente referenciado

        $('#proyectos-table').DataTable({
            "bDeferRender": true,
            "bProcessing": true,
            "sAjaxSource": "{{ route('proyectos.data') }}",
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
                        $('.count7').html(json.enproceso);
                        $('.count8').html(json.ejecutados);
                        $('.count9').html(json.pausados);
                        $('.count10').html(json.qa);
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
				aoData.push( { "name": "codclie", "value": codclie } );
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
    window.proyectoDetalles = function(proyectoId){
        $("#loadingSpinner").css("display", "flex");

		$.ajax({
			url: '{{ url("proyectos/ver-detalles") }}' + '/' + proyectoId, // Asegúrate de que esta ruta sea correcta
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

    $('.getProyectosByStatus').on('click', function() {
        getProyectosData.call(this);
    });
    
    $('#consultarProyectos').on('click', function() {
        getProyectosData();
    });
    

    //Actualizar Estatus de Proyecto
    $('#proyectoUpdateStatusButton').on('click', function(){
        var $codestatus = $('#estatusProyectoId').val();
        var $proyectoId = $('#proyectoId').val()
        $.ajax({
			url: '{{ route("proyectos.update") }}', // Asegúrate de que esta ruta sea correcta
			method: 'POST',
			data: {
				_token: '{{ csrf_token() }}',  // CSRF Token para seguridad
                codestatus: $codestatus,
                proyectoId: $proyectoId
			},
			success: function(response) {
                getProyectosData(response.success);
                $("#ProyectoModalEdit").modal("hide")
			},
			error: function(response){
				alert('Error de Conexión')
                console.log(response)
				$("#loadingSpinner").css("display", "none");
			}
		});
    })


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



{{------ CHAT DE PROYECTO -------}}
<script>
    //Chat modal entrada equipos
	window.openChatProyectoModal = function(codproyecto) {
        $('#codproyectoChat').val(codproyecto)
		// Realizar la solicitud AJAX para cargar los chats de la entrada
        $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner

		$.ajax({
			url: "{{ url('chatproyecto/cargar') }}" + '/' + codproyecto,  // La URL de tu ruta que carga los chats
			method: 'GET',
			success: function(response) {
                $("#loadingSpinner").css("display", "none");
				// Mostrar el modal
				$('#chatModal').modal('show');
				
				// Cargar el contenido del chat en el contenedor
				$('#chatContainer').html(response.html);

				// Actualizar el título del modal para reflejar la entrada actual
				$('#chatModalLabel').text('Chat de Proyecto #' + codproyecto);
			},
			error: function() {
                $("#loadingSpinner").css("display", "none");
				alert('Hubo un error al cargar el chat.');
			}
		});
	}

    //Enviar mensaje
    function sendMessage() {
        var message = $('#newMessage').val(); // Obtener el valor del textarea
        var codproyecto = $('#codproyectoChat').val(); // Obtener el ID de la entrada del equipo
        $("#loadingSpinner").css("display", "flex");

        if (message.trim() !== '') {
            // Hacer la petición AJAX para enviar el mensaje
            $.ajax({
                url: '{{ route("chatproyecto.send") }}', // Asegúrate de que esta ruta sea correcta
                method: 'POST',
                data: {
                    mensaje: message,
                    codproyecto: codproyecto, // Pasamos el codentrada al backend
                    _token: '{{ csrf_token() }}'  // CSRF Token para seguridad
                },
                success: function(response) {
                    $('#newMessage').val('');
                    $('#chatContainer').html(response.html);        
                    $("#loadingSpinner").css("display", "none");
                },
                error: function(response){
                    alert('Error de Conexión')
                    $("#loadingSpinner").css("display", "none");
                }
            });
        }   
    }

    // Enviar el mensaje al hacer clic en el botón
    $('#sendMessage').click(function() {
        sendMessage(); // Llamar la función de enviar mensaje
    });

    // Enviar el mensaje al presionar Enter (sin Shift)
    $('#newMessage').keypress(function(e) {
        if (e.which === 13 && !e.shiftKey) { // 13 es el código de Enter, y !e.shiftKey asegura que no sea con Shift
            e.preventDefault(); // Prevenir el salto de línea
            sendMessage(); // Llamar la función de enviar mensaje
        }
    });
    
</script>
@endsection
