@extends('layouts.admin')
@section('metadata')
<title>Entrada de Equipos - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Consultar Entrada de Equipos</h5>
                <span>💻 Completa los campos para filtrar y consultar las entradas de equipos.</span>
            </div>
            <div class="card-block">
                <form class="row" action="{{ url('entradaequipos') }}">
                    <div class="col-md-3 form-group">
                        <label for="from">Desde</label>
                        <input type="date" class="form-control" name="from" id="from">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="until">Hasta</label>
                        <input type="date" class="form-control" name="until" id="until">
                    </div>

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
                        <label for="consultor">Consultor</label>
                        <select name="consultor" id="consultor" class="form-control js-example-basic-single">
                            <option selected value="">Elige una Opción</option>
                            @foreach($consultors as $consultor)
                                <option value="{{ $consultor->codconsultor }}">{{ $consultor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div class="col-md-3 form-group">
                        <label for="estatus">Estatus</label>
                        <select name="estatus" id="estatus" class="form-control">
                            <option selected value="">Elige una Opción</option>
                            @foreach($estatus as $e)
                                <option value="{{ $e->codestatus }}">{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-search"></i> Consultar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Equipos Recibidos</h5>
                        <span>📋 Historial detallado de los equipos ingresados.</span>
                    </div>
                    <button type="button" class="btn btn-dark rounded" data-bs-toggle="modal" data-bs-target="#EntradaModalCreate">
                        <i class="fas fa-laptop"></i> Registrar Entrada de Equipos
                    </button>
                </div>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table id="entrada-equipos-table" class="table table-striped table-bordered nowrap table-small">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Fecha de Entrega</th>
                                <th>Estatus</th>
                                <th>Actividad a Realizar</th>
                                <th>Consultor</th>
                                <th>Acción</th>
                                <th>
                                    <i class="far fa-comments fa-2xl"></i>                                
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entradaequipos as $item)
                            <tr>
                                                
                                <td>
                                    {{$item->codentrada}}
                                </td>
                                <td>{{ $item->fecha ? date('d/m/Y', strtotime($item->fecha)) : 'N/A' }}</td>
                                <td><p>{{$item->saclie->descrip ?? 'N/A'}}</p></td>
                                <td>{{ $item->fechaentrega ? date('d/m/Y', strtotime($item->fechaentrega)) : 'N/A' }}</td>
                                <td>
                                    <span style="background-color:{{$item->estatus->color}};" class="badge">{{$item->estatus->nombre}}</span>
                                </td>
                                <td>
                                    <p>{{$item->actividad}}</p>
                                </td>
                                <td>
                                    <p>{{$item->consultor->nombre}}</p>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">

                                    {{-- Botón de actualizar estatus (solo si el estatus no es 2) --}}
                                    @if ($item->codestatus != 2)
                                        <a href="javascript:void(0);" 
                                            data-toggle="tooltip" 
                                            title="Actualizar Estatus"
                                            data-bs-target="#EntradaModalEdit"
                                            data-bs-toggle="modal" 

                                            onclick="btnUpdateStatusEntrada({{ $item->codentrada }});"
                                            class="btn btn-success">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                
                                    {{-- Botón de imprimir (si el estatus es 1 o 2) --}}
                                    @if (in_array($item->codestatus, [1, 2]))
                                        <a href="{{ url('entradaequipos/print/' . $item->codentrada) }}" 
                                            data-toggle="tooltip" 
                                            title="Imprimir"
                                            target="_blank"
                                            class="btn btn-danger">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    @endif
                                    </div>
                                </td>
                                <td>
                                    <a href="javascript:void(0);"
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        title="Abrir Chat"
                                        class="position-relative"
                                        id="chatButton_{{ $item->codentrada }}"
                                        onclick="openChatEntradaModal({{ $item->codentrada }})"
                                    >
                                        <i class="far fa-comments fa-2xl"></i>
                                        @if($item->chatentradas->count() > 0)
                                        <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-pill" id="countnoti">
                                            {{$item->chatentradas->count()}}
                                        </span>
                                        @endif
                                    </a>
                                </td>                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Crear Entrada de Equipos -->
<div class="modal fade EntradaModalCreate" tabindex="-1" id="EntradaModalCreate" aria-labelledby="EntradaModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="EntradaModalCreateLabel">Registrar Entrada de Equipo</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('entradaequipos.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <select name="codclie" class="form-control"  id="codclie" required>
                                    <option value="" selected></option>
                                    @foreach($saclie as $saclie)
                                        <option value="{{ $saclie->codclie }}"> {{ $saclie->rif }} | {{ $saclie->descrip }}</option>  
                                    @endforeach                                                 
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <select name="codestatus" class="form-control" id="codestatus" readonly required>
                                    <option value="{{ $estatus->first()->codestatus }}">{{ $estatus->first()->nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Consultor</label>
                                <select name="codconsultor" class="form-control" id="codconsultor" required="">
                                    <option value="" selected=""></option>
                                    @foreach($consultors as $consultor)
                                        <option value="{{ $consultor->codconsultor }}">{{ $consultor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">

                        </div>


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Actividad a Realizar</label>
                                <textarea class="form-control" id="actividad" name="actividad" rows="3" onkeyup="this.value=this.value.toUpperCase();"></textarea>
                            </div>
                        </div>



                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Observación</label>
                                <textarea class="form-control" id="observacion" name="observacion" rows="3" onkeyup="this.value=this.value.toUpperCase();"></textarea>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="far fa-times-circle"></i> Cerrar
                        </button>
                        <button id="btn-registrar" type="submit" class="btn btn-success float-center">
                            <i class="fas fa-sign-in-alt"></i> Registrar
                        </button>
                        <button id="btn-limpiar" type="reset" class="btn btn-warning float-center">
                            <i class="fas fa-trash-alt"></i> Limpiar
                        </button>
                    </div>
                </form> <!-- Cierre del formulario -->
            </div>
        </div>
    </div>
</div>


<!-- Modal Editar Entrada de Equipos -->
<div class="modal fade EntradaModalEdit" tabindex="-1" id="EntradaModalEdit" aria-labelledby="EntradaModalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="EntradaModalEditLabel">Cambiar Estatus de Entrada #<span class="entradaId"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('entradaequipos.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="entradaEquiposId" id="entradaEquiposId">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <select name="codestatus" class="form-control" id="codestatus" required>
                                    <option value="">Elige Una Opción</option>
                                    @foreach($estatus->whereNotIn('codestatus', [1]) as $e)
                                    <option value="{{ $e->codestatus }}">{{ $e->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="far fa-times-circle"></i> Cerrar
                        </button>
                        <button id="btn-registrar" type="submit" class="btn btn-success float-center">
                            <i class="fas fa-sync"></i> Actualizar Estatus
                        </button>
                        <button id="btn-limpiar" type="reset" class="btn btn-warning float-center">
                            <i class="fas fa-trash-alt"></i> Limpiar
                        </button>
                    </div>
                </form> <!-- Cierre del formulario -->
            </div>
        </div>
    </div>
</div>


<!-- Abrir Chat de Entrada de Equipos -->
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title font-weight-bold" id="chatModalLabel">Chat de Entrada de Equipos</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background: #F0F2F5;">
                <!-- Aquí se cargarán los mensajes del chat dinámicamente -->
                <div id="chatContainer">

                </div>

                <div class="chat-input">
                    <div class="input-group mb-2">
                        <input type="hidden" id="codentradaChat">
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

@endsection

@section('scripts')
<script>
    //Chat modal entrada equipos
	window.openChatEntradaModal = function(codentrada) {
        $('#codentradaChat').val(codentrada)
		// Realizar la solicitud AJAX para cargar los chats de la entrada
        $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner

		$.ajax({
			url: "{{ url('chatentrada/cargar') }}" + '/' + codentrada,  // La URL de tu ruta que carga los chats
			method: 'GET',
			success: function(response) {
                $("#loadingSpinner").css("display", "none");
				// Mostrar el modal
				$('#chatModal').modal('show');
				
				// Cargar el contenido del chat en el contenedor
				$('#chatContainer').html(response.html);

				// Actualizar el título del modal para reflejar la entrada actual
				$('#chatModalLabel').text('Chat de Entrada #' + codentrada);
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
        var codentrada = $('#codentradaChat').val(); // Obtener el ID de la entrada del equipo
        $("#loadingSpinner").css("display", "flex");

        if (message.trim() !== '') {
            // Hacer la petición AJAX para enviar el mensaje
            $.ajax({
                url: '{{ route("chat.send") }}', // Asegúrate de que esta ruta sea correcta
                method: 'POST',
                data: {
                    mensaje: message,
                    codentrada: codentrada, // Pasamos el codentrada al backend
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