<option>Elige un Método de Pago</option>
@foreach($tipomonedas as $t)
<option value="{{ $t->codtipomoneda }}">{{ $t->nombre }}</option>
@endforeach
