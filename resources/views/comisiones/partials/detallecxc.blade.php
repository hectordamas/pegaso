@if($detallecxc->count())
    @foreach($detallecxc as $comprobante)
        <button class="btn btn-outline-primary w-100 mb-2 text-start" onclick="openBase64Image('{{ $comprobante->file }}')">
            <i class="fas fa-eye me-2"></i> {{ Carbon\Carbon::parse($comprobante->fecha)->format('d-m-Y') }} - {{ $comprobante->descripcion }} - ${{number_format($comprobante->monto, 2, '.', ',') }}
        </button>
    @endforeach
@else
    <p class="text-muted">No hay comprobantes adjuntos.</p>
@endif
