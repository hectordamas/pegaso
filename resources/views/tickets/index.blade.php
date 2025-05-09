@extends('layouts.admin')
@section('metadata')
<title>Tickets - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Filtrar Tickets</h5>
                
                <span>Filtra por Estado, Fecha y Clientes</span>
            </div>
            <div class="card-block">
                <form action="{{ route('tickets.index') }}" method="get" class="row">
                    <div class="col-md-3 form-group">
                        <label for="from">Desde</label>
                        <input type="date" class="form-control" name="from" id="from" value="{{ request('from') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="until">Hasta</label>
                        <input type="date" class="form-control" name="until" id="until" value="{{ request('until') }}">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="status">Estado</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Selecciona una Opción</option>
                            @foreach(['PENDIENTE','COMPLETADO','ANULADO','RECHAZADO'] as $estado)
                                <option value="{{ $estado }}" {{ request('status') == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="control-label">Cliente</label>
                        <select name="codclie" class="form-control js-example-basic-single">
                            <option value="">Elige una Opción</option>
                            @foreach($saclie as $c)
                                <option value="{{ $c->codclie }}" {{ request('codclie') == $c->codclie ? 'selected' : '' }}>
                                    {{ $c->rif }} | {{ $c->descrip }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5>Listado de Tickets</h5>
                </div>
                <div>
                    <button type="button" class="btn btn-success rounded shadow" data-bs-toggle="modal" data-bs-target="#TicketModalCreate">
                        <i class="fas fa-ticket-alt"></i> Registrar Ticket
                    </button>
                </div>
            </div>
            <div class="card-block">
                <table class="table table-striped table-bordered" id="licencias-table">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha de Registro</th>
                            <th>Fecha de Actualización</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Solicitud</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->created_at->format('d-m-Y h:i a') }}</td>
                            <td>{{ $ticket->updated_at->format('d-m-Y h:i a') }}</td>
                            <td>{{ $ticket->saclie->descrip}}</td>
                            <td>
                                @php
                                    switch ($ticket->status) {
                                        case 'PENDIENTE':
                                            $badgeClass = 'warning';
                                            break;
                                        case 'COMPLETADO':
                                            $badgeClass = 'success';
                                            break;
                                        case 'ANULADO':
                                            $badgeClass = 'danger';
                                            break;
                                        case 'RECHAZADO':
                                            $badgeClass = 'secondary';
                                            break;
                                        default:
                                            $badgeClass = 'light';
                                    }
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>                            
                            <td>{{ $ticket->solicitud}}</td>
                            <td>
                                <a href="{{ route('tickets.edit', ['id' => $ticket->id]) }}" class="btn btn-success">
                                    <i class="far fa-edit"></i>
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

<!-- Modal Crear Ticket -->
<div class="modal fade TicketModalCreate" tabindex="-1" id="TicketModalCreate" aria-labelledby="TicketModalCreate" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="TicketModalCreate">Registrar Ticket</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <select name="codclie" class="form-control js-example-basic-single"  id="codclie" required>
                                    <option value="" selected>Elige una Opción</option>
                                    @foreach($saclie as $saclie)
                                        <option value="{{ $saclie->codclie }}"> {{ $saclie->rif }} | {{ $saclie->descrip }}</option>  
                                    @endforeach                                                 
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="">Selecciona una Opción</option>
                                    <option value="PENDIENTE">PENDIENTE</option>
                                    <option value="COMPLETADO">COMPLETADO</option>
                                    <option value="ANULADO">ANULADO</option>
                                    <option value="RECHAZADO">RECHAZADO</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Solicitud o Requerimiento</label>
                                <textarea class="form-control" id="solicitud" name="solicitud" rows="5"></textarea>
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
@endsection


@section('scripts')

@endsection