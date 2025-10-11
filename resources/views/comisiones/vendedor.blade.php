@extends('layouts.admin')

@section('metadata')
<title>{{ $savend->descrip }} - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
@php
    $meses = collect([
        ['numero' => '01', 'nombre' => 'Enero'],
        ['numero' => '02', 'nombre' => 'Febrero'],
        ['numero' => '03', 'nombre' => 'Marzo'],
        ['numero' => '04', 'nombre' => 'Abril'],
        ['numero' => '05', 'nombre' => 'Mayo'],
        ['numero' => '06', 'nombre' => 'Junio'],
        ['numero' => '07', 'nombre' => 'Julio'],
        ['numero' => '08', 'nombre' => 'Agosto'],
        ['numero' => '09', 'nombre' => 'Septiembre'],
        ['numero' => '10', 'nombre' => 'Octubre'],
        ['numero' => '11', 'nombre' => 'Noviembre'],
        ['numero' => '12', 'nombre' => 'Diciembre'],
    ])->map(fn($mes) => (object) $mes);
@endphp

<div class="row">
       <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Reporte de Comisiones 💰</h5>
                <span>Filtra y analiza las comisiones del equipo de ventas</span>
            </div>
            <div class="card-block">
                <form class="row" action="{{ url('comisiones') }}">    
                    <div class="col-md-6 form-group">
                        <label for="mes" class="fw-bold mb-2">Selecciona un Mes</label>
                        <select name="mes" id="mes" class="form-control">
                            @foreach($meses as $m)
                                <option {{ $m->numero == $mes ? 'selected' : '' }} value="{{ $m->numero }}">
                                    {{ $m->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" id="filtrar">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
    
                </form>
            </div>
        </div>
    </div>

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
                        <input type="number" class="form-control" name="comision_producto" value="{{ $savend->comision_producto }}" id="comision_producto" step="any">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="" class="control-label">
                            % Por Servicio
                        </label>
                        <input type="number" class="form-control" name="comision_servicio" value="{{ $savend->comision_servicio }}" id="comision_servicio" step="any">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="" class="control-label">
                            % Por Gerencia
                        </label>
                        <input type="number" class="form-control" name="comision_gerencia" value="{{ $savend->comision_gerencia }}" id="comision_gerencia" {{ !$savend->es_gerente ? 'disabled' : '' }} step="any">
                    </div>       
                    <div class="col-md-3 form-group">
                        <label for="email" class="control-label">E-Mail</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ $savend->email }}">
                    </div>              
                    <div class="col-md-1 form-group">
                        <label for="" class="control-label">
                            <input type="checkbox" {{ $savend->es_gerente ? 'checked' : '' }} name="es_gerente" id="es_gerente"> Gerente
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
        if($('#es_gerente').is(':checked')){
            $('#comision_gerencia').prop('disabled', false)
        }else{
            $('#comision_gerencia').prop('disabled', true)
        }
    });
</script>
@endsection