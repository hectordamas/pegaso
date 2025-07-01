@extends('layouts.admin')

@section('metadata')
<title>Licencias a Activar - {{ env('APP_NAME') }}</title>
@endsection

@php
    $esDirectiva = auth()->user()->role === 'Directiva' || auth()->user()->id === 34;
@endphp

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
                            <th>Serial</th>
                            <th>Pago Cliente</th>
                            <th>Pago a DS</th>
                            <th>Activada</th>
                            <th>Acción</th>
                            @if($esDirectiva)
                            <th>Verificada</th>
                            @endif
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
                                <td class="text-success fw-bold">$ {{ number_format($item->monto, 2, '.', ',') }}</td>
                                <td>{{ $item->serial ?? 'N/R' }}</td>
                                <td>
                                    <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                        <input class="form-check-input toggle-status" type="checkbox" role="switch" 
                                               name="pagada" data-id="{{ $item->id }}" value="1" 
                                                data-value="{{ $item->pagada }}"

                                               {{ $item->pagada ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                        <input class="form-check-input toggle-status" type="checkbox" role="switch" 
                                               name="pagadads" data-id="{{ $item->id }}" value="1" 
                                                data-value="{{ $item->pagadads }}"

                                               {{ $item->pagadads ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                        <input class="form-check-input toggle-status" type="checkbox" role="switch" 
                                               name="activada" data-id="{{ $item->id }}" value="1"
                                               data-value="{{ $item->activada }}" 
                                               {{ $item->activada ? 'checked' : '' }}>
                                    </div>
                                </td>                                
                                <td>

                                    <a  class="btn btn-outline-warning" 
                                        href="javascript:void(0)"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Ver Notas"
                                        data-bs-toggle="modal"
                                        data-bs-target="#LicenciasModalDetails"
                                        data-notas="{{ $item->notas }}"
                                        onclick="mostrarDetalles(this)"
                                        >
                                        <i class="fas fa-list-ul"></i>
                                    </a>

                                    @if($item->comprobantes->count() > 0)
                                        <a 
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Ver Comprobantes"

                                            href="javascript:void(0);"
                                            class="btn btn-outline-info"
                                            onclick="cargarComprobantes({{ $item->id }})"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#filesOffCanvas">
                                            <i class="fas fa-file-invoice"></i>                                     
                                        </a>
                                    @else
                                        <a 
                                            class="btn btn-outline-success" 
                                            href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Subir Comprobante"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#LicenciasModalUpload"
                                            data-id="{{ $item->id }}"
                                            onclick="setLicenciaId(this)"
                                            >
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </a>
                                    @endif

                                    <a  href="{{ route('licencias.edit', $item->id) }}"
                                        class="btn btn-outline-primary"
                                        data-toggle="tooltip"
                                        title="Editar Licencia">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button 
                                        class="btn btn-outline-danger btn-delete-licencia"
                                        data-id="{{ $item->id }}"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Eliminar Licencia"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>


                                </td>

                                @if($esDirectiva)
                                <td>
                                    <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                        <input class="form-check-input toggle-status" type="checkbox" role="switch" 
                                               name="verificada" data-id="{{ $item->id }}" value="1"
                                               data-value="{{ $item->verificada }}" 
                                               {{ $item->verificada ? 'checked disabled' : '' }}>
                                    </div>
                                </td>                            
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form id="deleteLicenciaForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<!-- Modal Crear Licencias -->
<div class="modal fade LicenciasModalCreate" tabindex="-1" id="LicenciasModalCreate" aria-labelledby="LicenciasModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="LicenciasModalCreateLabel">Registrar Licencia a Activar</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('licencias/store') }}" method="POST" enctype="multipart/form-data">
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
                            <input type="text" class="form-control" id="descripcion" name="descripcion">
                        </div>

                        
                        <div class="col-md-6 form-group">
                            <label class="control-label">Licencias</label>
                            <input type="text" class="form-control" id="licencias" name="licencias" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label">Fecha de Pago</label>
                            <input type="date" class="form-control" id="fechadepago" name="fechadepago" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label fw-bold mb-2">Monto</label>
                            <input name="monto" id="monto" type="text" class="form-control montopcd" onkeyup="convertirmonto(this.form)" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="serial mb-2">Serial</label>
                            <input type="text" name="serial" class="form-control">
                        </div>

                        <div class="col-md-6 form-group d-flex align-items-center">

                            <div class="form-check form-switch" style="margin-right: 30px;">
                                <input class="form-check-input" type="checkbox" role="switch" name="activada" id="activada" value="1"> 
                                <label for="activada" class="fw-bold mb-2">Activada</label>
                            </div>
    
    
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="pagada" id="pagadaModal" value="1"> 
                                <label for="pagada" class="fw-bold mb-2">Pagada</label>
                            </div>
    
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label">Notas</label>
                            <textarea class="form-control" id="notas" name="notas"></textarea>
                        </div>

                        <div class="col-md-6 form-group d-none" id="fileInputLicencias">
                            <label for="control-label">Subir Comprobante</label>
                            <input type="file" class="form-control" name="comprobantes[]" multiple accept="*" />
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

<!-- Subir Comprobante Licencias -->
<div class="modal fade LicenciasModalUpload" tabindex="-1" id="LicenciasModalUpload" aria-labelledby="LicenciasModalUploadLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="LicenciasModalUploadLabel">Carga un Comprobante de Pago</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('licencias/upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="control-label">Subir Comprobante</label>
                            <input type="hidden" id="licenciaId" name="licenciaId">
                            <input type="file" class="form-control" name="comprobantes[]" multiple accept="*" />
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

<!-- Modal Ver Detalles -->
<div class="modal fade LicenciasModalDetails" tabindex="-1" id="LicenciasModalDetails" aria-labelledby="LicenciasModalDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="LicenciasModalDetailsLabel">Ver Notas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalNotasTexto"></p>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas archivos -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filesOffCanvas" aria-labelledby="filesOffCanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Comprobantes Adjuntos</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" id="comprobantesAdjuntosLicencias">
      <p class="text-center">Cargando...</p>
    </div>
</div>
  

<!-- Modal visor -->
<div class="modal fade" id="visorModal" tabindex="-1" aria-labelledby="visorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Visor de Archivos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <iframe id="visorIframe" src="" frameborder="0" style="width:100%; height:80vh;" allowfullscreen></iframe>
        </div>
      </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).on('click', '.btn-delete-licencia', function () {
        const id = $(this).data('id');
        const claveIngresada = prompt("Ingrese la clave para eliminar esta licencia:");
        const claveEsperada = $('#exp').val();
    
        if (claveIngresada === claveEsperada) {
            const form = $('#deleteLicenciaForm');
            form.attr('action', '/licencias-a-activar/' + id); // Ajusta la ruta si es diferente
            form.submit();
        } else {
            alert("Clave incorrecta. No se eliminó la licencia.");
        }
    });
 </script>

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

    function setLicenciaId(button) {
        const id = $(button).data('id');
        $('#licenciaId').val(id);
    }

    function mostrarDetalles(button) {
        const notas = $(button).data('notas') || 'Sin notas';
        $('#modalNotasTexto').text(notas);
    }

    function cargarComprobantes(licenciaId) {
        $('#comprobantesAdjuntosLicencias').html('<p class="text-center">Cargando...</p>');
        $('#filesOffCanvas').offcanvas('show');

        $.get(`/licencias/${licenciaId}/comprobantes`, function(data) {
            $('#comprobantesAdjuntosLicencias').html(data);
        });
    }

    function verComprobante(ruta) {
        $('#visorIframe').attr('src', ruta);
        $('#visorModal').modal('show');
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

        $('#pagadaModal').on('change', function(){
            console.log($(this).is(":checked"))
            if($(this).is(":checked")){
                $('#fileInputLicencias').removeClass('d-none')
            }else{
                $('#fileInputLicencias').addClass('d-none')
            }
        })
    });
</script>

@endsection