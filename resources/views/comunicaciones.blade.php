@extends('layouts.admin')
@section('metadata')
<title>Comunicaciones - {{ env('APP_NAME') }}</title>
@endsection
@section('content')


<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Consultar Comunicaciones</h5>
                <span>📲 Completa los campos para filtrar y consultar las comunicaciones.</span>
            </div>
            <div class="card-block">
                <form action="{{ route('comunicaciones') }}" class="row">
                    <div class="col-md-3 form-group">
                        <label for="from">Desde</label>
                        <input type="date" class="form-control" name="from" id="from" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="until">Hasta</label>
                        <input type="date" class="form-control" name="until" id="until" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Tipo de Llamada</label>
                        <select name="codtipollamada" class="form-control" id="codtipollamada">
                            <option value="" selected>Elige un Opción</option>
                            @foreach($tipoLlamadas as $tipo)
                                <option value="{{ $tipo->codtipollamada }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Motivo</label>
                        <select name="codmotivo" class="form-control" id="codmotivo">
                            <option value="" selected>Elige un Opción</option>
                            @foreach($motivos as $motivo)
                                <option value="{{ $motivo->codmotivo }}">{{ $motivo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Consultar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5>Historial de Llamadas y Mensajes</h5>
                    <span>📋 Control de Mensajeria, Llamadas Entrantes y Salientes</span>
                </div>

                <button type="button" class="btn btn-success shadow rounded" data-bs-toggle="modal" data-bs-target="#ComunicacionesModalCreate">
                    <i class="fas fa-phone-volume"></i> Registrar Comunicaciones
                </button>
            </div>
            <div class="card-block">
                <table id="comunicaciones-table" class="table">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Medio</th>
                            <th>Tipo</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>E-Mail</th>
                            <th>Motivo</th>
                            <th>Consultor</th>
                            <th>Menciones</th>
                            <th>
                               Acciones                            
                            </th>                       
                         </tr>
                    </thead>
                    <tbody>
                        @foreach($llamadas as $llamada)
                        <tr>
                            <td>{{$llamada->codllamada}}</td>
                            <td>{{ \Carbon\Carbon::parse($llamada->fecha)->format('d-m-Y') }}</td>
                            <td><span class="badge badge-success">{{ $llamada->tipoDeComunicacion ?? 'N/A' }}</span></td>
                            <td><span class="badge" style="background: {{ $llamada->tipollamada->color ?? '#e9e9e9'  }};">{{ $llamada->tipollamada->nombre ?? 'N/A' }}</span></td>
                            <td>{{ $llamada->contacto}}</td>
                            <td>{{ $llamada->telefono}}</td>
                            <td>{{ $llamada->email}}</td>
                            <td><span class="badge" style="background: {{ $llamada->motivo->color ?? '#e9e9e9'  }};">{{ $llamada->motivo->nombre ?? 'N/A' }}</span></td>
                            <td>{{ $llamada->consultor->nombre ?? 'N/A' }}</td>
                            <td>
                                @if($llamada->menciones->isNotEmpty())
                                    {{ $llamada->menciones->pluck('consultor.nombre')->implode(', ') }}
                                @else
                                    N/A
                                @endif
                            </td>                            
                            <td>
                                @if($llamada->adjunto)
                                    <a href="{{ asset($llamada->adjunto) }}" target="_blank" class="btn btn-primary">
                                        <i class="far fa-file-alt"></i> Ver Adjunto
                                    </a>
                                @else
                                <a href="javascrip:void(0);" class="btn btn-danger">
                                    <i class="far fa-file-alt"></i> Adjunto No Disponible
                                </a>                                
                                @endif

                                <a href="javascript:void(0);"
                                    data-toggle="tooltip" 
                                    data-placement="top"
                                    title="Abrir Chat"
                                    class="btn btn-dark position-relative"
                                    id="chatButton_{{ $llamada->codllamada }}"
                                    onclick="openChatLlamadaModal({{ $llamada->codllamada }})"
                                >
                                    <i class="far fa-comments"></i> Abrir Chat
                                    @if($llamada->chatllamada->count() > 0)
                                    <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-pill" id="countnoti">
                                        {{$llamada->chatllamada->count()}}
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

<!-- Modal Crear Comunicaciones -->
<div class="modal fade ComunicacionesModalCreate" tabindex="-1" id="ComunicacionesModalCreate" aria-labelledby="ComunicacionesModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="ComunicacionesModalCreateLabel">Registrar Comunicación</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('comunicaciones.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="">Tipo de comunicación</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-comment-alt"></i>
                                </span>

                                <select name="tipoDeComunicacion" class="form-control" id="tipoDeComunicacion" required>
                                    <option value="" selected></option>
                                    <option value="Llamada">Llamada</option>
                                    <option value="Mensaje">Mensaje</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Tipo de Envío</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone-volume"></i>
                                    </span>

                                    <select name="codtipollamada" class="form-control"  id="codtipollamada" required>
                                        <option value="" selected></option>
                                        @foreach($tipoLlamadas as $tipollamada)
                                            <option value="{{ $tipollamada->codtipollamada }}">{{ $tipollamada->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Persona de Contacto</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input name="contacto" class="form-control"  id="contacto" value="{{ $ccliente->descrip ?? '' }}" required/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>

                                    <input name="telefono" class="form-control"  id="telefonoComunicaciones" data-inputmask='"mask": "(9999) 999-9999"' data-mask required/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">E-Mail</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-at"></i>                                    
                                    </span>

                                    <input name="email" class="form-control"  id="email" type="email" required/>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Motivo</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-question-circle"></i>                                    
                                    </span>
                                    <select name="codmotivo" class="form-control" id="codmotivo" required>
                                        <option></option>
                                        @foreach($motivos as $motivo)
                                        <option value="{{ $motivo->codmotivo }}">{{ $motivo->nombre }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Consultor</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tie"></i>                                    
                                    </span>
                                    <select name="codconsultor" class="form-control" id="codconsultor" required>
                                        <option></option>
                                        @foreach($consultores as $consultor)
                                        <option value="{{ $consultor->codconsultor }}" 
                                            @if($cconsultor)
                                                @if ($cconsultor->codconsultor == $consultor->codconsultor)
                                                    selected
                                                @endif
                                            @endif    
                                        >{{ $consultor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label class="control-label">Mencionar Consultores</label>
                            <select name="menciones[]" class="form-control" id="menciones" multiple>
                                @foreach($consultores as $consultor)
                                    <option value="{{ $consultor->codconsultor }}">{{ $consultor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        

                        <div class="col-md-6 form-group">
                            <label for="">Observaciones</label>
                            <textarea name="observacion" id="observacion" class="form-control">{{ $requestDescripcion ?? '' }}</textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="">Adjuntar</label>
                            <input type="file" class="form-control" name="file" accept="image/*, .pdf"/>
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

<!-- Abrir Chat  -->
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title font-weight-bold" id="chatModalLabel">Chat de Comunicaciones</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background: #F0F2F5;">
                <!-- Aquí se cargarán los mensajes del chat dinámicamente -->
                <div id="chatContainer">

                </div>

                <div class="chat-input">
                    <div class="input-group mb-2">
                        <input type="hidden" id="codllamadaChat">
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

@if($requestAccion)
    <script>
        $(document).ready(function(){
            $('#ComunicacionesModalCreate').modal('show');
        })
    </script>
@endif


<script>
    //Chat modal entrada equipos
	window.openChatLlamadaModal = function(codllamada) {
        $('#codentradaChat').val(codllamada)
		// Realizar la solicitud AJAX para cargar los chats de la entrada
        $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner

		$.ajax({
			url: "{{ url('chatllamada/cargar') }}" + '/' + codllamada,  // La URL de tu ruta que carga los chats
			method: 'GET',
			success: function(response) {
                $("#loadingSpinner").css("display", "none");
				// Mostrar el modal
				$('#chatModal').modal('show');
				
				// Cargar el contenido del chat en el contenedor
				$('#chatContainer').html(response.html);

				// Actualizar el título del modal para reflejar la entrada actual
				$('#chatModalLabel').text('Chat de Comunicaciones #' + codllamada);
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
        var codllamada = $('#codllamadaChat').val(); // Obtener el ID de la entrada del equipo
        $("#loadingSpinner").css("display", "flex");

        if (message.trim() !== '') {
            // Hacer la petición AJAX para enviar el mensaje
            $.ajax({
                url: '{{ route("chatllamada.send") }}', // Asegúrate de que esta ruta sea correcta
                method: 'POST',
                data: {
                    mensaje: message,
                    codentrada: codllamada, // Pasamos el codentrada al backend
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