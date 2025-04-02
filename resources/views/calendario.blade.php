@extends('layouts.admin')
@section('metadata')
<title>Calendario de Eventos - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')
    <!-- Calender css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/bower_components/fullcalendar/dist/fullcalendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/bower_components/fullcalendar/dist/fullcalendar.print.css') }}" media='print'>
@endsection

@section('content')
<!-- Crear Evento Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('eventos.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Cliente o Prospecto</label>
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
                                <label class="control-label">Consultor</label>
                                <select name="codconsultor" class="form-control" id="codconsultor" required>
                                    <option value="" selected=""></option>
                                    @foreach($consultors as $consultor)
                                        <option value="{{ $consultor->codconsultor }}">{{ $consultor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Fecha y Hora del Evento</label>
                                <input type="datetime-local" id="desde" name="desde" class="form-control datetimepicker-input" data-target="#desdedate" required 
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}"
                                />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Nombre del Evento</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Notas del Evento</label>
                                <textarea class="form-control" name="description" id="" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eventType">Tipo de Evento</label>
                                <select class="form-control" id="eventType" name="eventType" required>
                                    <option value="">Elige Una Opción</option>
                                    <option value="Presencial">Presencial</option>
                                    <option value="Remoto">Remoto</option>
                                    <option value="Local Oficina">Local Oficina</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eventInteraction">Tipo de Interacción</label>
                                <select class="form-control" id="interactionType" name="interactionType" required>
                                    <option value="">Elige Una Opción</option>
                                    <option value="Proyecto">Proyecto</option>
                                    <option value="Visita">Visita</option>
                                    <option value="Llamada">Llamada</option>
                                </select>
                            </div>
                        </div>


                    </div>

                    <input type="hidden" id="start">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mostrar Evento Modal -->
<div class="modal fade" id="showEventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Detalles del Evento #<span id="eventId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Título del Evento:</strong> <br><span class="modalTitle"></span></p>
                    </div>

                    <div class="col-md-12">
                        <p><strong>Fecha del Evento:</strong> <br><span id="modalStart"></span></p>
                    </div>

                    <div class="col-md-12">
                        <p><strong>Cliente / Prospecto:</strong><br> <span id="modalClient"></span></p>
                    </div>

                    <div class="col-md-12">
                        <p><strong>Consultor:</strong> <br> <span id="modalConsultor"></span></p>
                    </div>

                    <div class="col-md-12">
                        <p><strong>Notas del Evento:</strong><br> <span id="modalDescription"></span></p>
                    </div>
                    
                    <div class="col-md-6">
                        <p><strong>Tipo de Evento:</strong> <br><span id="modalEventType"></span></p>
                    </div>

                    <div class="col-md-6">
                        <p><strong>Tipo de Interacción:</strong> <br><span id="modalInteractionType"></span></p>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="eventIdInput">
                <button type="button" class="btn btn-danger" id="destroyEvent" data-bs-dismiss="modal">
                    <i class="fas fa-trash"></i> Eliminar Evento
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Calendario de Eventos</h5>
                        <span>📅 Gestión y Organización de Eventos.</span>
                    </div>

                    <button type="button" class="btn btn-success rounded shadow" data-bs-toggle="modal" data-bs-target="#createEventModal">
                        <i class="far fa-calendar-alt"></i> Registra un Evento
                    </button>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- calender js -->
    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/moment/min/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/locale/es.js'></script>

    <script>
	    "use strict";
	    $(document).ready(function() {
	        $('#calendar').fullCalendar({
	            header: {
	                left: 'prev,next today',
	                center: 'title',
	                right: 'month,  listMonth'
	            },
                eventBackgroundColor: '#f0f0f0',
                locale: 'es',
	            defaultDate: '{{ date("Y-m-d") }}',
	            navLinks: true, // can click day/week names to navigate views
	            businessHours: true, // display business hours
	            editable: true,
	            droppable: true, // this allows things to be dropped onto the calendar
                dayClick: function(date, jsEvent, view) {
                    var today = moment().startOf('day'); // Obtiene la fecha actual sin horas

                    if (date.isBefore(today, 'day')) {
                        // Si la fecha seleccionada es menor a hoy, muestra el modal de error
                        Swal.fire({
                            title: "Fecha no Válida!",
                            text: "No es posible crear un evento en una fecha anterior a la actual.",
                            icon: "error",
                            confirmButtonText: "Entendido!", 
                            confirmButtonColor: '#dc3545'
                        });
                    } else {
                        var dateWithTime = moment(date).set({'hour': 8, 'minute': 0, 'second': 0, 'millisecond': 0});

                        // Si la fecha es válida, abre el modal de creación
                        var formattedDate = dateWithTime.format('YYYY-MM-DD[T]HH:mm'); // Formato para datetime-local
                        console.log(formattedDate)
                        $('#desde').val(formattedDate);                        
                        $('#createEventModal').modal('show');
                    }
                },
	            drop: function() {

	                // is the "remove after drop" checkbox checked?
	                if ($('#checkbox2').is(':checked')) {
	                    // if so, remove the element from the "Draggable Events" list
	                    $(this).remove();
	                }
	            },
	            events: @json($eventos),
                eventClick: function(event) {
                    // Llenar el modal con la información del evento
                    $('#eventId').text(event.id);
                    $('#eventIdInput').val(event.id)
                    $('.modalTitle').text(event.title ?? 'No Registrado');
                    $('#modalStart').text(event.start.format('YYYY-MM-DD HH:mm A'));
                    $('#modalClient').text(event.cliente ?? 'No Registrado');
                    $('#modalConsultor').text(event.consultor ?? 'No Registrado');
                    $('#modalDescription').text(event.description ?? 'No Registrado');
                    $('#modalEventType').text(event.eventType ?? 'No Registrado');
                    $('#modalInteractionType').text(event.interationType ?? 'No Registrado');


                    // Mostrar el modal
                    $('#showEventModal').modal('show');
                },
                eventRender: function(event, element, view) {
                    if (view.name === 'listMonth') {
                        element.find('.fc-event-dot').css('background-color', event.itemDotColor);
                    }else{
                        // Agrega un punto de color antes del título
                        element.find('.fc-title').prepend(`<span style="color: ${event.itemDotColor}; font-size: 1.8em;">● </span>`);
                    }
                },
                eventDrop: function(event, delta, revertFunc) {
                    let today = moment().startOf('day'); // Fecha de hoy sin horas
                    let newDate = event.start; // Nueva fecha del evento después de moverlo
                    let eventEntryDate = moment(event.entry_date); // Fecha de inicio original del evento (entry_date)

                        // Verificar si el evento ya ocurrió
                        if (eventEntryDate.isBefore(today)) {
                            // Si el evento ya ocurrió, no permitir que se mueva a una fecha futura
                            let newDate = moment(event.start).add(delta); // Nueva fecha a la que se intenta mover el evento
                        
                            if (newDate.isAfter(today)) {
                                // Si la nueva fecha es en el futuro, revertir el movimiento
                                Swal.fire({
                                    title: "¡Evento ya ocurrido!",
                                    text: "No se puede mover un evento que ya ha pasado a una fecha futura.",
                                    icon: "error",
                                    confirmButtonText: "Entendido!", 
                                    confirmButtonColor: '#dc3545'
                                });
                            
                                return revertFunc(); // Revertir el movimiento
                            }
                        }

                    if (newDate.isBefore(today)) {
                        // Si la fecha es anterior a hoy, revertir el movimiento y mostrar alerta
                        Swal.fire({
                            title: "Fecha no Válida!",
                            text: "No es posible mover un evento a una fecha anterior a la actual.",
                            icon: "error",
                            confirmButtonText: "Entendido!", 
                            confirmButtonColor: '#dc3545'
                        });
                    
                        revertFunc(); // Vuelve a la posición original
                    } else {
                        $("#loadingSpinner").css("display", "flex");

                        $.ajax({
                            url: "{{ url('eventos/update') }}" + "/" +  event.id,
                            type: 'POST',
                            data: {
                                start: event.start.format('YYYY-MM-DD HH:mm:ss'),
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    text: "Fecha del evento actualizada",
                                    icon: "success",
                                    confirmButtonText: "Entendido!",
                                });
                                $("#loadingSpinner").css("display", "none");

                            },
                            error: function() {
                                Swal.fire({
                                    title: "Error!",
                                    text: "No se pudo actualizar el evento.",
                                    icon: "error",
                                    confirmButtonText: "Entendido!",
                                });

                                revertFunc(); // Si hay un error en la actualización, revierte el movimiento
                                $("#loadingSpinner").css("display", "none");

                            }
                        })

                    }
                }
	        });

            $('#destroyEvent').click(function () {
                var eventId = $('#eventIdInput').val(); // Obtener el ID del evento

                if (!eventId) {
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo obtener el ID del evento.",
                        icon: "error",
                        confirmButtonText: "Entendido",
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }
            
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Esta acción eliminará el evento permanentemente.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#loadingSpinner").css("display", "flex");

                        $.ajax({
                            url: "{{ url('eventos/delete/') }}/" + eventId, // Ruta para eliminar
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}" // Token de seguridad CSRF
                            },
                            success: function (response) {
                                $("#loadingSpinner").css("display", "none");

                                if (response.success) {
                                    $('#showEventModal').modal('hide'); // Cerrar modal
                                    $('#calendar').fullCalendar('removeEvents', eventId); // Eliminar del calendario

                                    Swal.fire({
                                        title: "Eliminado",
                                        text: "El evento ha sido eliminado correctamente.",
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: response.message || "No se pudo eliminar el evento.",
                                        icon: "error",
                                        confirmButtonText: "Entendido"
                                    });
                                }
                            },
                            error: function () {
                                $("#loadingSpinner").css("display", "none");

                                Swal.fire({
                                    title: "Error",
                                    text: "Ocurrió un problema al intentar eliminar el evento.",
                                    icon: "error",
                                    confirmButtonText: "Entendido"
                                });
                            }
                        });
                    }
                });
            });

	    });

    </script>
@endsection