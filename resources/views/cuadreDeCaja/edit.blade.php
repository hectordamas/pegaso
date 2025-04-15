@extends('layouts.admin')

@section('metadata')
<title>Editar Cuadre de Caja - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div id="plantilla-movimiento" class="d-none">
    @include('caja.partials.movimiento')
</div>

<div class="row">
    <div class="col-md-12">

        <form action="{{ route('cuadre-de-caja.update', ['id' => $cuadre->id]) }}" method="POST">
            @csrf

            <div class="card mb-4">
                <div class="card-header">
                    <h5>📠 Editar Cuadre de Caja</h5>
                </div>
                <div class="card-block row">
                    <div class="form-group col-md-4">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" class="form-control" required value="{{ \Carbon\Carbon::parse($cuadre->date)->format('Y-m-d') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="numero_orden">Número de Orden</label>
                        <input type="number" name="numero_orden" class="form-control" value="{{ $cuadre->numero_orden }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" rows="1" class="form-control">{{ $cuadre->observaciones }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <button type="button" class="btn btn-success" onclick="agregarMovimiento()">
                            <i class="fas fa-plus"></i> Agregar Movimiento
                        </button>     
                    </div>
                </div>
            </div>

            <div id="movimientos-container">
                @foreach($cuadre->movimientos as $movimiento)
                    @include('caja.partials.movimiento')
                @endforeach
            </div>


            <div class="text-end">
                <button type="submit" class="btn btn-primary shadow rounded">
                    <i class="fas fa-cash-register"></i> Registrar Cuadre de Caja
                </button>
            </div>
        </form>

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

    function initializeSelect2(element) {
        if (!element.hasClass("select2-hidden-accessible")) {
            element.select2({
                width: '100%',
                dropdownParent: element.closest('.movimiento-item') // 👈 cambia esto según tu estructura
            });
        }
    }

    function agregarMovimiento() {
        let plantilla = $('#plantilla-movimiento').html();
        $('#movimientos-container').append(plantilla);

        let nuevoSelect = $('#movimientos-container .select-cliente').last();
        initializeSelect2(nuevoSelect);     
        
        convertirmonto();
    }

    // Agregar un movimiento al cargar la vista
    document.addEventListener("DOMContentLoaded", function () {
        agregarMovimiento();
    });
</script>
@endsection

