@extends('layouts.admin')

@section('metadata')
<title>Modificar Licencia - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Modificar Licencia #{{ $licencia->id }} </h5>
            </div>
            <div class="card-block">

                <form method="POST" action="{{ route('licencias.update', $licencia->id) }}" class="row">
                    @csrf
                    @method('PUT')
                
                    <div class="col-md-3 form-group">
                        <label class="control-label">Cliente</label>
                        <select name="codclie" class="form-control js-example-basic-single" id="codclie" required>
                            <option value="" selected="">Elige una Opción</option>
                            @foreach($saclie as $saclie)
                                <option value="{{ $saclie->codclie }}" {{ $licencia->codclie ==  $saclie->codclie ? 'selected' : ''}}> {{ $saclie->rif }} | {{ $saclie->descrip }}</option>  
                            @endforeach                                                 
                        </select>
                    </div>
                
                    <div class="form-group col-md-3">
                        <label for="descripcion">Descripción</label>
                        <input type="text" class="form-control" name="descripcion" value="{{ $licencia->descripcion }}">
                    </div>
                
                    <div class="form-group col-md-3">
                        <label for="licencias">Cantidad de Licencias</label>
                        <input type="text" class="form-control" name="licencias" value="{{ $licencia->licencias }}" required>
                    </div>
                
                    <div class="form-group col-md-3">
                        <label for="fechadepago">Fecha de Pago</label>
                        <input type="date" class="form-control" name="fechadepago" value="{{ \Carbon\Carbon::parse($licencia->fechadepago)->format('Y-m-d') }}" required>
                    </div>
                
                    <div class="form-group col-md-3">
                        <label class="control-label fw-bold mb-2">Monto</label>
                        <input name="monto" id="monto" type="text" class="form-control montopcd" value="{{ $licencia->monto }}" onkeyup="convertirmonto(this.form)" required>
                    </div>
                
                    <div class="form-group col-md-3">
                        <label for="serial">Serial</label>
                        <input type="text" name="serial" class="form-control" value="{{ $licencia->serial }}" required>
                    </div>
                
                    <div class="form-group col-md-3">
                        <label for="notas">Notas</label>
                        <textarea class="form-control" name="notas">{{ $licencia->notas }}</textarea>
                    </div>
                
                    <div class="col-md-3 form-group d-flex align-items-center">

                        <div class="form-check form-switch" style="margin-right: 30px;">
                            <input class="form-check-input" type="checkbox" role="switch" name="activada" id="activada" value="1"> 
                            <label for="activada" class="fw-bold mb-2">Activada</label>
                        </div>


                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="pagada" id="pagadaModal" value="1"> 
                            <label for="pagada" class="fw-bold mb-2">Pagada</label>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
                        <a href="{{ url('licencias-a-activar') }}" class="btn btn-secondary mt-3">Cancelar</a>
                    </div>                
                </form>
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
</script>
@endsection