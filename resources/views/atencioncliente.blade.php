@extends('layouts.admin')
@section('metadata')
<title>Atención Clientes - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Consultar Solicitudes</h5>
                <span>🎧 Completa los campos para filtrar y consultar las solicitudes de atención al cliente.</span>
            </div>
            <div class="card-block">
                <form class="row" action="{{ url('atencionclientes') }}">
                    <div class="col-md-3 form-group">
                        <label for="from">Desde</label>
                        <input type="date" class="form-control" name="from" id="from">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="until">Hasta</label>
                        <input type="date" class="form-control" name="until" id="until">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="client">Cliente</label>
                        <select name="client" id="client" class="form-control js-example-basic-single">
                            <option selected value="">Elige una Opción</option>
                            @foreach($saclie as $client)
                                <option value="{{ $client->codclie }}">{{ $client->descrip }} | {{$client->rif}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="consultor">Consultor</label>
                        <select name="consultor" id="consultor" class="form-control js-example-basic-single">
                            <option selected value="">Elige una Opción</option>
                            @foreach($consultors as $consultor)
                                <option value="{{ $consultor->codconsultor }}">{{ $consultor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div class="col-md-3 form-group">
                        <label for="estatus">Estatus</label>
                        <select name="estatus" id="estatus" class="form-control">
                            <option selected value="">Elige una Opción</option>
                            @foreach($estatusAt as $estatus)
                                <option value="{{ $estatus->codestatus }}">{{ $estatus->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-search"></i> Consultar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Operaciones Diarias</h5>
                        <span>📋 Historial de Solicitudes de atención al cliente.</span>
                    </div>
                    <button type="button" class="btn btn-dark rounded" data-bs-toggle="modal" data-bs-target="#SoporteModalCreate">
                        <i class="fas fa-headset"></i> Registrar Actividad
                    </button>
                </div>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table id="atencion-clientes-table" class="table table-striped table-bordered nowrap table-small">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Estatus</th>
                                <th>Solicitud</th>
                                <th>Actividad</th>
                                <th>Consultor</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($atencionClientes as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td><p class="fecha">{{ \Carbon\Carbon::parse($item->fecha)->format('d-m-Y g:i A') }}</p></td>
                                <td><p>{{ $item->saclie->descrip ?? 'N/A' }}</p></td>
                                <td>
                                    <span class="badge" style="background: {{$item->estatusAt->color}};">
                                        {{ $item->estatusAt->nombre }}
                                    </span>
                                </td>
                                <td><p>{{ $item->solicitud ? \Str::words($item->solicitud, 50, $end='...') : "No Registrada"}}</p></td>
                                <td><p>{{ $item->actividad ? \Str::words($item->actividad, 50, $end='...') : "No Registrada"}}</p></td>
                                <td>{{ $item->consultor->nombre ?? 'N/A'}}</td>
                                <td>
                                    @if(!($item->codestatus == 3))
                                    <a  
                                        href="javascript:void(0);" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        title="Actualizar Estatus"
                                        onclick="btnUpdateStatus({{ $item->id }});"

                                        class="btn btn-success btn-update-status" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#SoporteModalEdit"
                                        data-id="{{ $item->id }}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    @endif

                                    <a href="javascript:void(0);" 
                                    
                                        data-toggle="tooltip" data-placement="top"
                                        title="Ver Detalles"
                                        onclick="btnViewDetails(this);"
                                        class="btn btn-warning btn-view-details" 
                                        data-bs-toggle="modal" 
                                        data-id="{{ $item->id }}"
                                        data-fecha="{{ \Carbon\Carbon::parse($item->fecha)->format('d-m-Y g:i A') }}"
                                        data-descrip="{{ $item->saclie->descrip ?? '' }}"
                                        data-estatus="{{ $item->estatusAt->nombre }}"
                                        data-color="{{ $item->estatusAt->color }}"
                                        data-solicitud="{{ $item->solicitud }}"
                                        data-actividad="{{ $item->actividad }}"
                                        data-consultor="{{ $item->consultor->nombre ?? '' }}"
                                        data-bs-target="#SoporteModalView"
                                        class="btn btn-warning">
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


<!-- Modal Crear Actividades de Soporte -->
<div class="modal fade SoporteModalCreate" tabindex="-1" id="SoporteModalCreate" aria-labelledby="SoporteModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="SoporteModalCreateLabel">Registrar Actividad</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('atencionclientes.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
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
                                <label class="control-label">Estatus</label>
                                <select name="codestatus" class="form-control" id="codestatus" readonly required>
                                    <option value="1">PENDIENTE</option>                                                                                                                                                                                                                                                                                                                                 
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Solicitud</label>
                                <input minlength="3" maxlength="120" type="text" class="form-control" id="solicitud" name="solicitud" value="" onkeyup="this.value=this.value.toUpperCase();" required="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Tipo de Conexión</label>
                                <select name="conexion" class="form-control" id="conexion" required="">
                                    <option style="" value="" selected=""></option>
                                    <option style="" value="1">AnyDesk</option>
                                    <option style="" value="2">RustDesk</option>
                                    <option style="" value="3">Team Viewer</option>
                                    <option style="" value="4">Escritorio Remoto</option>
                                    <option style="" value="5">Otros</option>	
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label class="control-label">ID / IP</label>
                                <div class="input-group">
                                    <span class="input-group-text "><i class="fas fa-laptop "></i></span>
                                    <input name="direccionconex" id="direccionconex" type="text" class="form-control" data-inputmask="'alias': 'ip'" data-mask required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Consultor</label>
                                <select name="codconsultor" class="form-control" id="codconsultor" required="">
                                    <option value="" selected=""></option>
                                    @foreach($consultors as $consultor)
                                        <option value="{{ $consultor->codconsultor }}">{{ $consultor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Actividad Realizada</label>
                                <textarea class="form-control" id="actividad" name="actividad" rows="5" onkeyup="this.value=this.value.toUpperCase();"></textarea>
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

<!-- Modal Para ver detalles de Actividades de Soporte -->
<div class="modal fade SoporteModalView" tabindex="-1" id="SoporteModalView" aria-labelledby="SoporteModalViewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="SoporteModalViewLabel">Detalles de Solicitud</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<!-- Modal Editar Actividades de Soporte -->
<div class="modal fade SoporteModalEdit" tabindex="-1" id="SoporteModalEdit" aria-labelledby="SoporteModalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="SoporteModalEditLabel">Cambiar Estatus de Solicitud</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('atencionclientes.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="atencionClienteId" id="atencionClienteId">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Estatus</label>
                                <select name="codestatus" class="form-control" id="codestatus" required>
                                    @foreach($estatusAt as $estatus)
                                        <option value="{{ $estatus->codestatus }}">{{ $estatus->nombre }}</option>                                                                                                                                                                                                                                                                                                                                 
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Actividad Realizada</label>
                                <textarea class="form-control" id="actividad" name="actividad" rows="5" onkeyup="this.value=this.value.toUpperCase();"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="far fa-times-circle"></i> Cerrar
                        </button>
                        <button id="btn-registrar" type="submit" class="btn btn-success float-center">
                            <i class="fas fa-sync"></i> Actualizar Estatus
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

