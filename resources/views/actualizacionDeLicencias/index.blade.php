@extends('layouts.admin')
@section('metadata')
<title>Actualización Homologación - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">

    @foreach($widgets as $w)
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card shadow">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-{{ $w->color }} f-w-600">{{ $w->count }}</h4>
                        <h6 class="text-muted m-b-0">{{ $w->title }}</h6>
                    </div>
                    <div class="col-4 text-end">
                        <i class="{{ $w->icon }} f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-{{ $w->color }}">
                <div class="row align-items-center">
                    <div class="col-12">
                        <p class="text-white m-b-0">{{ $w->subtitle }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Filtrar Actualización de Homologación</h5>
                
                <span>Filtra por Estado, Fecha e Incidencias</span>
            </div>
            <div class="card-block">
                <form action="{{ route('actualizacion-licencias.index') }}" method="get" class="row">
                    <div class="col-md-3 form-group">
                        <label for="from">Desde</label>
                        <input type="date" class="form-control" name="from" id="from">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="until">Hasta</label>
                        <input type="date" class="form-control" name="until" id="until">
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="status">Estado</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Selecciona una Opción</option>
                            <option value="Actualizado">Actualizado</option>
                            <option value="No Actualizado">No Actualizado</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="incidencias">Incidencias</label>
                        <select name="incidencias" id="incidencias" class="form-control">
                            <option value="">Selecciona una Opción</option>
                            <option value="Sin Incidencias">Sin Incidencias</option>
                            <option value="Con Incidencias">Con Incidencias</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group">
                        <label class="control-label">Cliente</label>
                        <select name="codclie" class="form-control js-example-basic-single">
                            <option value="" selected>Elige una Opción</option>
                            @foreach($saclie as $c)
                                <option value="{{ $c->codclie }}"> {{ $c->rif }} | {{ $c->descrip }}</option>  
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
                    <h5>Lista de Homologación</h5>
                    <span>Licencias actualizadas y pendientes por actualización</span>
                </div>
                <div>
                    <button type="button" class="btn btn-success rounded shadow" data-bs-toggle="modal" data-bs-target="#ActualizacionDeLicenciasModalCreate">
                        <i class="fas fa-sync"></i> Registrar Solicitud
                    </button>
                </div>
            </div>
            <div class="card-block">
                <table class="table table-striped table-bordered" id="licencias-table">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha de Registro</th>
                            <th>Cliente</th>
                            <th>Estado de Actualización</th>
                            <th>Incidencias</th>
                            <th>Observación</th>
                            <th>Pagada</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($actualizacionDeLicencias as $a)
                        <tr>
                            <td>{{ $a->id }}</td>
                            <td>{{ $a->created_at->format('d-m-Y h:i A') }}</td>
                            <td>{{ $a->saclie->descrip }}</td>
                            <td>{{ $a->status }}</td>
                            <td>{{ $a->incidencias }}</td>
                            <td>{{ $a->observacion }}</td>
                            <td>
                                @if($a->pagada)
                                    <i class="fas fa-check fa-2x"></i>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('actualizacion-licencias.edit', ['id' => $a->id]) }}" class="btn btn-success">
                                    <i class="far fa-edit"></i>
                                </a>

                                    @if($a->adjunto)
                                     <a 
                                        target="_blank"
                                        class="btn btn-info" 
                                        href="{{ asset($a->adjunto) }}"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Ver Contrato"
                                     >
                                        <i class="fas fa-file-invoice"></i>                                     
                                    </a>
                                    @endif

                                    @if($a->qrseniat)
                                    <a 
                                       target="_blank"
                                       class="btn btn-dark" 
                                       href="{{ asset($a->qrseniat) }}"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="Ver QR Seniat"
                                    >
                                        <i class="fas fa-qrcode"></i>
                                   </a>
                                   @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Actividades de Soporte -->
<div class="modal fade ActualizacionDeLicenciasModalCreate" tabindex="-1" id="ActualizacionDeLicenciasModalCreate" aria-labelledby="ActualizacionDeLicenciasModalCreate" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="ActualizacionDeLicenciasModalCreate">Registrar Actualización Homologación</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('actualizacion-licencias.store') }}" method="POST" enctype="multipart/form-data">
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
                                    <option value="Actualizado">Actualizado</option>
                                    <option value="No Actualizado">No Actualizado</option>                                                                                                                                                                                                                                                                                                                           
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="incidencias">Incidencias</label>
                            <select name="incidencias" id="incidencias" class="form-control">
                                <option value="">Selecciona una Opción</option>
                                <option value="Sin Incidencias">Sin Incidencias</option>
                                <option value="Con Incidencias">Con Incidencias</option>
                            </select>
                        </div>




                        <div class="col-sm-6 form-group form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="pagada" value="1"> 
                            <label for="pagada" class="fw-bold mb-2">Pagada</label>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="control-label">Adjuntar Contrato</label>
                            <input type="file" class="form-control" name="file" accept="*" />
                        </div> 

                        <div class="col-md-6 form-group">
                            <label for="control-label">Adjuntar QR Seniat</label>
                            <input type="file" class="form-control" name="qrseniat" accept="*" />
                        </div> 

                        <div class="col-sm-6 pt-3">
                            <div class="form-group">
                                <label class="control-label">Observación</label>
                                <textarea class="form-control" id="observacion" name="observacion" rows="5"></textarea>
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