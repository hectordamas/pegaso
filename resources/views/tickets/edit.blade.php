@extends('layouts.admin')

@section('metadata')
<title>Actualizar Ticket - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Actualizar Ticket</h5>
            </div>
            <div class="card-block">
                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <select name="codclie" class="form-control js-example-basic-single" id="codclie" required>
                                    <option value="">Elige una Opción</option>
                                    @foreach($saclie as $cliente)
                                        <option value="{{ $cliente->codclie }}" {{ $cliente->codclie == $ticket->codclie ? 'selected' : '' }}>
                                            {{ $cliente->rif }} | {{ $cliente->descrip }}
                                        </option>  
                                    @endforeach                                                 
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="">Selecciona una Opción</option>
                                    @foreach(['PENDIENTE', 'COMPLETADO', 'ANULADO', 'RECHAZADO'] as $status)
                                        <option value="{{ $status }}" {{ $ticket->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Solicitud o Requerimiento</label>
                                <textarea class="form-control" id="solicitud" name="solicitud" rows="5">{{ old('solicitud', $ticket->solicitud) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                            <i class="far fa-times-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection