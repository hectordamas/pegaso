<option>Elige una Moneda</option>
@foreach($monedas as $m)
<option value="{{ $m->codmoneda }}">{{ $m->nombre }}</option>
@endforeach