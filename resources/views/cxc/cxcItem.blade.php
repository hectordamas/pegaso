
<div class="row">
    @foreach($saldosPorCliente as $item)
        <div class="col-md-6 col-xl-4 cxc-item" data-codclie="{{ $item->codclie }}">
            <div class="card widget-card-1 shadow-sm border-success border-3 shadow-lg">
                <div class="card-block-small p-3">
                    <i class="fas fa-hand-holding-usd bg-c-green card1-icon"></i>
                    <span>
                        <label class="badge badge-success">{{ $item->codclie }}</label>
                    </span>
                    <h4 class="text-danger fw-bold">$ {{ number_format($item->saldo, 2, ',', '.') }}</h4>
                    <div class="text-muted">
                        <span class="f-left">
                            <p class="text-left text-dark fw-bold clienteText">
                                {{ $item->saclie->rif ?? 'N/A' }} - {{ $item->saclie->descrip ?? 'N/A'}}
                            </p>
                        </span>
                    </div>
                    <div class="f-right">
                        <input onchange="updateColor('{{ $item->codcxc }}');" list="rainbow" name="codcxc_{{ $item->codcxc }}" id="codcxc_{{ $item->codcxc }}" type="color" value="{{ $item->color }}" {{ !Auth::user()->master ? 'disabled' : '' }}>
                        <datalist id="rainbow"> 
                            <option value="#00FF00">Lime</option>
                            <option value="#FFA500">Orange</option>
                            <option value="#0000FF">Blue</option>
                            <option value="#FF0000">Red</option>
                        </datalist>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row align-items-center">
                        <div class="col-9 text-start">
                            <span class="text-muted">Creado por: <span class="text-primary">{{ $item->user->name ?? 'N/A' }}</span></span>
                        </div>
                        <div class="col-3 text-end">
                            <a href="javascript:void(0)" class="btn btn-danger" data-toggle="tooltip" title="Ver CxC de Cliente" onclick="modalClienteCxc('{{ $item->codclie }}');">
                                <i class="fas fa-file-alt fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

