@extends('layouts.admin')

@section('metadata')
<title>Licencias a Activar - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Consultar Licencias a Activar</h5>
                <span>Seleccione los criterios para encontrar licencias a activar</span>
            </div>
            <div class="card-block">
                <form action="{{ url('licencias-a-activar') }}" method="GET" class="row">
                    <div class="col-md-3 form-group">
                        <label for="from" class="fw-bold mb-2">
                            Desde
                        </label>
                        <input type="date" class="form-control" name="from" id="from"> 
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="until" class="fw-bold mb-2">
                            Hasta
                        </label>
                        <input type="date" class="form-control" name="until" id="until">
                    </div>
                    <div class="col-md-3 form-group d-flex align-items-center">

                        <div class="form-check form-switch" style="margin-right: 30px;">
                            <input class="form-check-input" type="checkbox" role="switch" name="activada" id="activada" value="1"> 
                            <label for="activada" class="fw-bold mb-2">Activada</label>
                        </div>


                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="pagada" id="pagada" value="1"> 
                            <label for="pagada" class="fw-bold mb-2">Pagada</label>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <button class="btn btn-primary">
                            <i class="fas fa-search"></i> Consultar
                        </button>
                    </div>


                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5>Lista de Licencias a Activar</h5>
                    <span>Listado actualizado de licencias listas para activación</span>
                </div>

                <a href="javascript:void(0)" class="btn btn-success shadow" data-bs-toggle="modal" data-bs-target="#LicenciasModalCreate">
                    <i class="fas fa-certificate"></i> Registrar Licencia
                </a>
            </div>
            <div class="card-block table-responsive dt-responsive">
                <table class="table table-bordered table-striped" id="licencias-table">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>RIF</th>
                            <th>Razón Social</th>
                            <th>Descripción</th>
                            <th>Licencias</th>
                            <th>Fecha de Pago</th>
                            <th>Monto</th>
                            <th>Pagada</th>
                            <th>Activada</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($licenciasAActivar as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->saclie->rif }}</td>
                                <td>{{ $item->saclie->descrip }}</td>
                                <td>{{ $item->descripcion }}</td>
                                <td>{{ $item->licencias }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->fechadepago)->format('d-m-Y') }}</td>
                                <td>{{ $item->monto }}</td>
                                <td>
                                    <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                        <input class="form-check-input toggle-status" type="checkbox" role="switch" 
                                               name="pagada" data-id="{{ $item->id }}" value="1" 
                                               {{ $item->pagada ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                        <input class="form-check-input toggle-status" type="checkbox" role="switch" 
                                               name="activada" data-id="{{ $item->id }}" value="1" 
                                               {{ $item->activada ? 'checked' : '' }}>
                                    </div>
                                </td>                                
                                <td>{{ $item->notas }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal Crear Licencias -->
<div class="modal fade LicenciasModalCreate" tabindex="-1" id="LicenciasModalCreate" aria-labelledby="LicenciasModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="LicenciasModalCreateLabel">Registrar Licencia a Activar</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('licencias/store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <select name="codclie" class="form-control js-example-basic-single" id="codclie" required>
                                    <option value="" selected="">Elige una Opción</option>
                                    @foreach($saclie as $saclie)
                                        <option value="{{ $saclie->codclie }}"> {{ $saclie->rif }} | {{ $saclie->descrip }}</option>  
                                    @endforeach                                                 
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>

                        
                        <div class="col-md-6 form-group">
                            <label class="control-label">Licencias</label>
                            <input type="text" class="form-control" id="licencias" name="licencias" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label">Fecha de Pago</label>
                            <input type="text" class="form-control" id="fechadepago" name="fechadepago" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label fw-bold mb-2">Monto</label>
                            <input name="monto" id="monto" type="text" class="form-control montopcd" onkeyup="convertirmonto(this.form)" required>
                        </div>

                        <div class="col-md-6 form-group d-flex align-items-center">

                            <div class="form-check form-switch" style="margin-right: 30px;">
                                <input class="form-check-input" type="checkbox" role="switch" name="activada" id="activada" value="1"> 
                                <label for="activada" class="fw-bold mb-2">Activada</label>
                            </div>
    
    
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="pagada" id="pagada" value="1"> 
                                <label for="pagada" class="fw-bold mb-2">Pagada</label>
                            </div>
    
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label">Notas</label>
                            <textarea class="form-control" id="notas" name="notas"></textarea>
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
<script>
    function convertirmonto(input){
    	$(".montopcd").on({
    		"focus": function (event) {
    			$(event.target).select();
    		},
    		"keyup": function (event) {
    			$(event.target).val(function (index, value ) {
    				return value.replace(/\D/g, "")
    					.replace(/([0-9])([0-9]{2})$/, '$1,$2')
    					.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
    			});	   
    		}
    	});
    }

    $(document).ready(function() {
        $(document).on('change', '.toggle-status', function() {
            let licenciaId = $(this).data("id");
            let field = $(this).attr("name");
            let value = $(this).is(":checked") ? 1 : 0;
            
            $.ajax({
                url: `licencias-a-activar/update-status/${licenciaId}`,
                type: "POST",
                data: {
                    field: field,
                    value: value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (!response.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo actualizar el estado'
                        });
                        checkbox.prop("checked", !value); // Revertir checkbox si hay error
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema con la actualización'
                    });
                    checkbox.prop("checked", !value); // Revertir checkbox si hay error
                }
            });
        });
    });
</script>

@endsection