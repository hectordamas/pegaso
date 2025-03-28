@extends('layouts.admin')

@section('metadata')
<title>Entregas y Suministros - {{ env('APP_NAME') }}</title>
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
            ['id' => 11, 'name' => 'Comprado', 'count' => 0, 'color' => 'primary', 'icon' => 'fas fa-shopping-cart'],
            ['id' => 12, 'name' => 'En Proceso', 'count' => 0, 'color' => 'warning', 'icon' => 'fas fa-spinner'],
            ['id' => 13, 'name' => 'Entregado', 'count' => 0, 'color' => 'success', 'icon' => 'fas fa-box-open'], // Icono cambiado
        ];
    @endphp

    {{-- Consultar Entregas por Estatus --}}
    @foreach($estatusCollection as $status)
    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
        <div class="card text-white bg-{{ $status['color'] }} shadow status-card getEntregasByStatus" data-id="{{ $status['id'] }}">
            <div class="card-body text-center">
                <i class="{{ $status['icon'] }} fa-3x mb-2"></i>
                <h5 class="mt-2">{{ $status['name'] }}</h5>
                <h4 class="fw-bold count{{ $status['id'] }}">0</h4>
            </div>
        </div>
    </div>
    @endforeach
</div>


{{-- Consultar Entregas por filtros --}}
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Consultar Entregas</h5>
                <span>📌 Completa los campos para filtrar y consultar entregas.</span>
            </div>
            <div class="card-block">
                <div class="row" id="entregaCreateForm">
                    
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
                        <button type="submit" class="btn btn-primary mt-3" id="consultarEntregas">
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
                <h5>Entregas y Suministros</h5>
                <span>📋 Reporte / Historial de Entregas.</span>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="entregas-table" class="table table-striped table-bordered nowrap table-small">
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
                            <tbody id="entregasTbody">
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
                <h6 class="modal-title font-weight-bold" id="chatModalLabel">Chat de Entregas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background: #F0F2F5;">
                <!-- Aquí se cargarán los mensajes del chat dinámicamente -->
                <div id="chatContainer">

                </div>

                <div class="chat-input">
                    <div class="input-group mb-2">
                        <input type="hidden" id="codentregaChat">
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

<!-- Modal Para ver detalles de Entrega -->
<div class="modal fade EntregaModalView" tabindex="-1" id="EntregaModalView" aria-labelledby="EntregaModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="EntregaModalViewLabel">Detalles de la Entrega #<span class="entregaId"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productosEntrega">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="far fa-times-circle"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Cambiar Estatus de Entrega -->
<div class="modal fade EntregaModalEdit" tabindex="-1" id="EntregaModalEdit" aria-labelledby="EntregaModalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="EntregaModalEditLabel">Cambiar Estatus de Entrega #<span class="entregaId"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="entregaUpdateform" method="POST">
                    @csrf

                    <input type="hidden" name="entregaId" id="entregaId">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <select name="codestatus" class="form-control" id="estatusEntregaId" required>
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
                        <button id="entregaUpdateStatusButton" class="btn btn-success float-center">
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

    $(function(){
        getEntregasData();
    });

    function getEntregasData(updated) {
        $("#loadingSpinner").css("display", "flex"); 

        if ($.fn.DataTable.isDataTable('#entregas-table')) {
            $('#entregas-table').dataTable().fnClearTable();
			$('#entregas-table').dataTable().fnDestroy();
        }

        let id = $(this).data('id')
        let codclie = $('#client').val();
        let codvend = $('#codvend').val();
        let codestatus =  id || $('#estatus').val(); // this ahora está correctamente referenciado

        $('#entregas-table').DataTable({
            "bDeferRender": true,
            "bProcessing": true,
            "sAjaxSource": "{{ route('entregas.data') }}",
            "fnServerData": function ( sSource, aoData, fnCallback ) {
				$.getJSON( sSource, aoData, function (json) { 
					fnCallback(json)

                    $("#loadingSpinner").css("display", "none"); 

                    if (!id) {
                        $('.count11').html(json.comprados);
                        $('.count12').html(json.enproceso);
                        $('.count13').html(json.entregados);
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
    window.entregaDetalles = function(entregaId){
        $("#loadingSpinner").css("display", "flex");

		$.ajax({
			url: '{{ url("entregas/ver-detalles") }}' + '/' + entregaId,
			method: 'POST',
			data: {
				_token: '{{ csrf_token() }}'  // CSRF Token para seguridad
			},
			success: function(response) {
                $('.entregaId').html(entregaId);
                $("#EntregaModalView").modal("show")
				$('#productosEntrega').html(response.items);  
                $("#loadingSpinner").css("display", "none");
  
			},
			error: function(response){
				alert('Error de Conexión')
				$("#loadingSpinner").css("display", "none");
			}
		});
	}

    $('.getEntregasByStatus').on('click', function() {
        getEntregasData.call(this);
    });
    
    $('#consultarEntregas').on('click', function() {
        getEntregasData();
    });
    

    //Actualizar Estatus de Entrega
    $('#entregaUpdateStatusButton').on('click', function(){
        var $codestatus = $('#estatusEntregaId').val();
        var $entregaId = $('#entregaId').val()
        $.ajax({
			url: '{{ route("entregas.update") }}', // Asegúrate de que esta ruta sea correcta
			method: 'POST',
			data: {
				_token: '{{ csrf_token() }}',  // CSRF Token para seguridad
                codestatus: $codestatus,
                entregaId: $entregaId
			},
			success: function(response) {
                getEntregasData(response.success);
                $("#EntregaModalEdit").modal("hide")
			},
			error: function(response){
				alert('Error de Conexión')
                console.log(response)
				$("#loadingSpinner").css("display", "none");
			}
		});
    })

    $(document).on('change', '.update-status-entrega-item', function() {
        let radioButton = $(this); // Radio button seleccionado
        let itemId = radioButton.data('id'); // Obtener el ID del item
        let newStatus = radioButton.val(); // Obtener el nuevo estado seleccionado
        let previousStatus = radioButton.data('previous-status'); // Obtener el estado anterior desde el atributo de datos

        $.ajax({
            url: "{{ route('actualizar.entregasItems') }}", // Ruta del controlador
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: itemId,
                status: newStatus // Enviar el nuevo estado
            },
            success: function(response) {
                console.log(response); // Respuesta del servidor
            },
            error: function(error) {
                console.log(error); // Mostrar error en consola
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el estado.'
                });
                // Revertir el estado en caso de error
                radioButton.prop('checked', false); // Desmarcar el radio button
                $(`input[name="status_${itemId}"][value="${previousStatus}"]`).prop('checked', true); // Restaurar el estado anterior
            }
        });
    });

</script>



{{------ CHAT DE ENTREGA -------}}
<script>
    //Chat modal entrada equipos
	window.openChatEntregaModal = function(codentrega) {
        $('#codproyectoChat').val(codentrega)
		// Realizar la solicitud AJAX para cargar los chats de la entrada
        $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner

		$.ajax({
			url: "{{ url('chatentrega/cargar') }}" + '/' + codentrega,  // La URL de tu ruta que carga los chats
			method: 'GET',
			success: function(response) {
                $("#loadingSpinner").css("display", "none");
				// Mostrar el modal
				$('#chatModal').modal('show');
				
				// Cargar el contenido del chat en el contenedor
				$('#chatContainer').html(response.html);

				// Actualizar el título del modal para reflejar la entrada actual
				$('#chatModalLabel').text('Chat de Entrada #' + codentrega);
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
        var codentrega = $('#codentregaChat').val(); // Obtener el ID de la entrada del equipo
        $("#loadingSpinner").css("display", "flex");

        if (message.trim() !== '') {
            // Hacer la petición AJAX para enviar el mensaje
            $.ajax({
                url: '{{ route("chatentrega.send") }}', // Asegúrate de que esta ruta sea correcta
                method: 'POST',
                data: {
                    mensaje: message,
                    codentrega: codentrega, // Pasamos el codentrega al backend
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
