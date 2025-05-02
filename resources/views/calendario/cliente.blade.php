@extends('layouts.admin')
@section('metadata')
<title>{{ $saclie->descrip }} - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>👥📅 {{ $saclie->descrip }} / Eventos</h5>
            </div>
            <div class="card-block">
                <div class="table-responsive dt-responsive">
                    <table class="table table-bordered table-striped" id="clientes-table">
                        <thead class="table-dark">
                            <th>#</th>
                            <th>Consultor</th>
                            <th>Fecha del Evento</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @foreach($calendario as $event)
                            <tr>
                                <td>{{$event->id}}</td>
                                <td><span style="color: {{ $event->color ? $event->color : '#01A9AC' }}; font-size: 1.8em;">● </span> <span class="fw-bold">{{ $event->consultor->nombre }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($event->entry_date)->format('d-m-Y h:i A') }}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn-event btn btn-warning" data-event='@json($event)'
                                        data-toggle="tooltip" data-placement="top"
                                        title="Ver Detalles"
                                    >
                                        <i class="fas fa-eye"></i>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('assets/adminty/bower_components/moment/min/moment.min.js') }}"></script>

<script>
    $('.btn-event').on('click', function(){
        var event = $(this).data('event');
        console.log(event)

        // Convertir entry_date en objeto Moment
        var entryDate = moment(event.entry_date);

        // Llenar el modal con la información del evento
        $('#eventId').text(event.id);
        $('#eventIdInput').val(event.id);
        $('.modalTitle').text(event.title ?? 'No Registrado');
        $('#modalStart').text(entryDate.isValid() ? entryDate.format('YYYY-MM-DD hh:mm A') : 'Fecha inválida');
        $('#modalClient').text(event?.saclie?.descrip ?? event.lead);
        $('#modalConsultor').text(event.consultor.nombre ?? 'No Registrado');
        $('#modalDescription').text(event.description ?? 'No Registrado');
        $('#modalEventType').text(event.eventType ?? 'No Registrado');
        $('#modalInteractionType').text(event.interationType ?? 'No Registrado');

        // Mostrar el modal
        $('#showEventModal').modal('show');
    });
</script>
@endsection


