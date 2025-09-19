@extends('layouts.admin')

@section('metadata')
<title>Comisiones - {{ env('APP_NAME') }}</title>
@endsection

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

@section('content')
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
    
    <div class="col-md-10" id="comisionesContainer">
        <div class="card">
            <div class="card-header">
                <h5>Equipo de Ventas</h5>
                <span class="text-muted">Administración de comisiones y gerencia</span>
            </div>
            <div class="card-block dt-responsive table-responsive">
                <table class="table table-striped table-bordered" id="comisiones-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Vendedor</th>
                            <th>Comisión Producto</th>
                            <th>Comisión Servicio</th>
                            <th>Comisión Gerencia</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $v)
                        <tr>
                            <td>{{ $v->nombre }}</td>
                            <td class="monto-producto">
                                $ {{ number_format($v->comisionProducto, 2, '.', ',') }} ({{$v->comision_producto}}%)
                            </td>
                            <td class="monto-servicio">
                                $ {{ number_format($v->comisionServicio, 2, '.', ',') }} ({{$v->comision_servicio}}%)
                            </td>
                            <td class="monto-gerencia">
                                @if($v->comision_gerencia)
                                    $ {{ number_format($v->comisionGerencia, 2, '.', ',') }} ({{$v->comision_gerencia}}%)
                                @else
                                    N\A
                                @endif
                            </td>
                            <td>
                                $ {{ number_format($v->totalConGerencia, 2, '.', ',') }}
                            </td>
                            <td>
                                <a href="{{ url('comisiones/vendedor/' . $v->id) }}" class="btn btn-inverse" data-toggle="tooltip" data-placement="top" title="Configurar">
                                    <i class="fas fa-cog"></i> 
                                </a>
                                <a href="{{ url('comisiones/detalles/' . $v->id . '/' . $mes) }}" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Detalles">
                                    <i class="fas fa-list"></i> 
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
@endsection


