<div class="row">
    @php
        $temas = ['bg-c-pink', 'bg-c-green', 'bg-c-yellow']; 
        $textos = ['text-c-pink', 'text-c-green', 'text-c-yellow'];   
        $iconos = ['', 'fas fa-dollar-sign', 'fas fa-euro-sign'];
    @endphp

    @foreach($monedas as $index => $m)
    <div class="col-xl-4 col-md-6">
        <div class="card {{$temas[$index]}} text-white shadow">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Saldo en {{ $m->siglas }}</p>
                        <h4 class="m-b-0">{{$m->saldo}}</h4>
                    </div>
                    <div class="col col-auto text-end">
                        @if($m->siglas == 'VEB')
                            <span class="f-40 text-c-pink fw-bold" style="filter: brightness(0.9);">{{ $m->siglas }}</span>
                        @else
                            <i class="{{$iconos[$index]}} f-50 {{$textos[$index]}}" style="filter: brightness(0.9);"></i>                            
                        @endif
                    </div>
                </div>

                @foreach($m->arreglo as $arreglo)
                <div class="d-flex row justify-content-between mt-3">
                    <div class="col fw-bold">
                        <i class="far fa-check-square"></i> {{ $arreglo->tipomoneda }}
                    </div>
                    <div class="col fw-bold text-right">
                        <i class="far fa-check-square"></i> {{$arreglo->monto}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

</div>