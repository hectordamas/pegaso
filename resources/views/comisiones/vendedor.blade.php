@extends('layouts.admin')

@section('metadata')
    <title>{{ $savend->descrip }} - {{ env('APP_NAME') }}</title>
@endsection

@section('content')


    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>
                        {{ $savend->descrip }} - Configuración de Comisiones
                    </h5>

                    <a href="{{ url('comisiones') }}" class="btn btn-inverse shadow rounded">
                        <i class="fas fa-arrow-left"></i> Tabla de Comisiones
                    </a>
                </div>
                <div class="card-block">
                    <form action="{{ url('comisiones/set/' . $savend->id) }}" class="row">
                        @csrf
                        <div class="col-md-3 form-group">
                            <label for="" class="control-label">
                                % Por Producto
                            </label>
                            <input type="number" class="form-control" name="comision_producto"
                                value="{{ $savend->comision_producto }}" id="comision_producto" step="any">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="" class="control-label">
                                % Por Servicio
                            </label>
                            <input type="number" class="form-control" name="comision_servicio"
                                value="{{ $savend->comision_servicio }}" id="comision_servicio" step="any">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="" class="control-label">
                                % Por Gerencia
                            </label>
                            <input type="number" class="form-control" name="comision_gerencia"
                                value="{{ $savend->comision_gerencia }}" id="comision_gerencia"
                                {{ !$savend->es_gerente ? 'disabled' : '' }} step="any">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="email" class="control-label">E-Mail</label>
                            <input type="email" class="form-control" name="email" id="email"
                                value="{{ $savend->email }}">
                        </div>
                        <div class="col-md-1 form-group">
                            <label for="" class="control-label">
                                <input type="checkbox" {{ $savend->es_gerente ? 'checked' : '' }} name="es_gerente"
                                    id="es_gerente"> Gerente
                            </label>
                        </div>
                        <div class="col-md-1 form-group">
                            <label for="" class="control-label">
                                <input type="checkbox" {{ $savend->activo ? 'checked' : '' }} name="activo"> Activo
                            </label>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-inverse shadow">
                                <i class="fas fa-cog"></i> Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '#es_gerente', function() {
            if ($('#es_gerente').is(':checked')) {
                $('#comision_gerencia').prop('disabled', false)
            } else {
                $('#comision_gerencia').prop('disabled', true)
            }
        });
    </script>
@endsection
