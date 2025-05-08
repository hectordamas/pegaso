@extends('layouts.admin')
@section('metadata')
<title>Actualización de Licencias - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')

@endsection

@section('content')
<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Actualizar</h5>
                <span>Modificar Solicitud de Actualización</span>
            </div>
            <div class="card-block">
                <form action="{{ route('actualizacion-licencias.update', ['id' => $licencia->id]) }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <select name="codclie" class="form-control js-example-basic-single" id="codclie" required>
                                    <option value="">Elige una opción</option>
                                    @foreach($saclie as $cliente)
                                        <option value="{{ $cliente->codclie }}" {{ $licencia->codclie == $cliente->codclie ? 'selected' : '' }}>
                                            {{ $cliente->rif }} | {{ $cliente->descrip }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="">Selecciona una opción</option>
                                    <option value="Actualizado" {{ $licencia->status == 'Actualizado' ? 'selected' : '' }}>Actualizado</option>
                                    <option value="No Actualizado" {{ $licencia->status == 'No Actualizado' ? 'selected' : '' }}>No Actualizado</option>
                                </select>
                            </div>
                        </div>
                
                        <div class="col-md-3 form-group">
                            <label for="incidencias">Incidencias</label>
                            <select name="incidencias" id="incidencias" class="form-control">
                                <option value="">Selecciona una opción</option>
                                <option value="Sin Incidencias" {{ $licencia->incidencias == 'Sin Incidencias' ? 'selected' : '' }}>Sin Incidencias</option>
                                <option value="Con Incidencias" {{ $licencia->incidencias == 'Con Incidencias' ? 'selected' : '' }}>Con Incidencias</option>
                            </select>
                        </div>
                
                
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label">Observación</label>
                                <textarea class="form-control" id="observacion" name="observacion" rows="5">{{ old('observacion', $licencia->observacion) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <a href="{{ route('actualizacion-licencias.index') }}" class="btn btn-secondary">
                                <i class="far fa-times-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>

@endsection


@section('scripts')

@endsection