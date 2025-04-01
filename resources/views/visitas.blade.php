@extends('layouts.admin')
@section('metadata')
    <title>Visitas - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Consultar Visitas</h5>
                <span>🧑🏿‍🤝‍🧑🏿 Completa los campos para filtrar y consultar los registros de visitas.</span>
            </div>
            <div class="card-block">
                <form action="{{ route('visitas') }}" class="row">
                    <div class="col-md-3 form-group">
                        <label for="from">Desde</label>
                        <input type="date" class="form-control" name="from" id="from" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="until">Hasta</label>
                        <input type="date" class="form-control" name="until" id="until" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label>Consultor</label>
                        <select name="codconsultor" class="form-control">
                            <option value="" selected>Elige un Opción</option>
                            @foreach($consultores as $consultor)
                                <option value="{{ $consultor->codconsultor }}">{{ $consultor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-search"></i> Consultar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5>Historial de Visitas</h5>
                    <span>📋 Resultado de consulta de las visitas recibidas</span>
                </div>
                <button type="button" class="btn btn-dark rounded" data-bs-toggle="modal" data-bs-target="#VisitaModalCreate">
                    <i class="fas fa-id-card"></i> Registrar Visita
                </button>
            </div>
            <div class="card-block">
                <table class="table table-striped" id="visita-table">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Consultor</th>
                            <th>Acciones</th>
                            <th>
                                <i class="far fa-comments fa-2xl"></i>                                
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitas as $visita)
                            <tr>
                                <td>{{ $visita->codvisita }}</td>
                                <td>{{ \Carbon\Carbon::parse($visita->fecha)->format('d/m/Y') }}</td>
                                <td>
                                    {{ $visita->saclie->descrip ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $visita->consultor->nombre ?? 'N/A' }}
                                </td>
                                <td>
                                    <div class="btn-group">

                                        <a href="{{ route('visitas.pdf', ['codvisita' => $visita->codvisita]) }}" target="_blank" class="btn btn-danger" data-toggle="tooltip" title="Abrir Orden de Servicio">
                                            <i class="far fa-file-pdf"></i>
                                        </a>

                                        @if($visita->adjunto)
                                        <a href="{{ asset($visita->adjunto) }}" target="_blank" class="btn btn-success" data-toggle="tooltip" title="Abrir Adjunto">
                                            <i class="fas fa-external-link-square-alt"></i>
                                        </a>
                                        @else
                                        <a href="javascript:void(0);" class="btn btn-info" data-toggle="tooltip" title="Adjuntar Archivo" onclick="fileUpload('{{$visita->codvisita}}')">
                                            <i class="fas fa-cloud-upload-alt"></i>                                    
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
                                        id="chatButton_{{ $visita->codvisita }}"
                                        onclick="openChatVisitaModal({{ $visita->codvisita }})"
                                    >
                                        <i class="far fa-comments fa-2xl"></i>
                                        @if($visita->chatvisita->count() > 0)
                                        <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-pill" id="countnoti">
                                            {{$visita->chatvisita->count()}}
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


<!-- Modal Crear Visitas -->
<div class="modal fade VisitaModalCreate" tabindex="-1" id="VisitaModalCreate" aria-labelledby="VisitaModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="VisitaModalCreateLabel">Registrar Visita</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('visitas.store') }}" method="POST">
                    @csrf
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <select name="codclie" class="form-control"  id="codclie" required>
                                    <option></option>
                                    @foreach($saclie as $saclie)
                                        <option value="{{ $saclie->codclie }}"
                                            @if($ccliente)
                                                @if ($ccliente->codclie == $saclie->codclie)
                                                    selected
                                                @endif
                                            @endif                                               
                                            > {{ $saclie->rif }} | {{ $saclie->descrip }}</option>  
                                    @endforeach                                                 
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Consultor</label>
                                <select name="codconsultor" class="form-control" id="codconsultor" required="">
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

                        <div class="col-md-6 form-group">
                            <label for="">Fecha y Hora Inicio</label>
                            <input type="datetime-local" id="entry_date" name="entry_date" class="form-control datetimepicker-input" required 
                                value="{{ $requestFecha ? 
                                    \Carbon\Carbon::parse($requestFecha)->format('Y-m-d\TH:i')  
                                    : \Carbon\Carbon::now()->format('Y-m-d\TH:i') 
                                }}"
                            />
                        </div>

                        
                        <div class="col-md-6 form-group">
                            <label for="">Fecha y Hora Final</label>
                            <input type="datetime-local" id="departure_date" name="departure_date" class="form-control datetimepicker-input" required 
                            value="{{ $requestFecha ? 
                                    \Carbon\Carbon::parse($requestFechaFinal)->format('Y-m-d\TH:i')  
                                    : \Carbon\Carbon::now()->format('Y-m-d\TH:i') 
                                }}"                            
                            />
                        </div>

                        <div class="col-sm-6 form-group">
                            <label class="control-label">Mencionar acompañantes</label>
                            <select name="acompanantes[]" class="form-control" id="acompanantes" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->codusuario }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"></div>

                        <!-- Campo Observación -->
                        <div class="col-md-6 form-group">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea class="form-control" id="observacion" name="observacion" rows="4">{{ $requestDescripcion ?? '' }}</textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="notas" class="form-label">Notas del Consultor</label>
                            <textarea class="form-control" id="notas" name="notas" rows="4"></textarea>
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

{{-- Chat de visitas --}}
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title font-weight-bold" id="chatModalLabel">Chat de Visitas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background: #F0F2F5;">
                <!-- Aquí se cargarán los mensajes del chat dinámicamente -->
                <div id="chatContainer">

                </div>

                <div class="chat-input">
                    <div class="input-group mb-2">
                        <input type="hidden" id="codvisitaChat">
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


{{-- Adjuntar Archivo --}}
<div class="modal fade VisitaFile" tabindex="-1" id="VisitaFile" aria-labelledby="VisitaFileLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="VisitaFileLabel">Adjuntar Archivo Visitas #<span class="visitaId"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('visitas/subir-archivo') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="visitaId" id="visitaId">

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="file" class="fw-bold mb-2">Archivo</label>
                            <input type="file" class="form-control" name="file" id="file" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="far fa-times-circle"></i> Cerrar
                        </button>
                        <button id="btn-registrar" type="submit" class="btn btn-success float-center">
                            <i class="fas fa-file-upload"></i> Subir Archivo
                        </button>
                    </div>
                </form> <!-- Cierre del formulario -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@if($requestAccion)
    <script>
        $(document).ready(function(){
            $('#VisitaModalCreate').modal('show');
        })
    </script>
@endif

<script>
    //Chat modal entrada equipos
	window.openChatVisitaModal = function(codvisita) {
        $('#codvisitaChat').val(codvisita)
		// Realizar la solicitud AJAX para cargar los chats de la entrada
        $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner

		$.ajax({
			url: "{{ url('chatvisita/cargar') }}" + '/' + codvisita,  // La URL de tu ruta que carga los chats
			method: 'GET',
			success: function(response) {
                $("#loadingSpinner").css("display", "none");
				// Mostrar el modal
				$('#chatModal').modal('show');
				
				// Cargar el contenido del chat en el contenedor
				$('#chatContainer').html(response.html);

				// Actualizar el título del modal para reflejar la entrada actual
				$('#chatModalLabel').text('Chat de Entrada #' + codvisita);
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
        var codvisita = $('#codvisitaChat').val(); // Obtener el ID de la entrada del equipo
        $("#loadingSpinner").css("display", "flex");

        if (message.trim() !== '') {
            // Hacer la petición AJAX para enviar el mensaje
            $.ajax({
                url: '{{ route("chatvisita.send") }}', // Asegúrate de que esta ruta sea correcta
                method: 'POST',
                data: {
                    mensaje: message,
                    codvisita: codvisita, // Pasamos el codentrada al backend
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


    window.fileUpload = function(codvisita){
        $('#VisitaFile').modal('show')
        $('.visitaId').html(codvisita)
        $('#visitaId').val(codvisita)
    }
    
</script>
@endsection