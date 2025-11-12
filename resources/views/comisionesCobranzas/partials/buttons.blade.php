<a href="javascript:void(0)" data-id="{{ $pago->id }}" class="btn btn-inverse addInfo">
    Agregar Informacion
</a>

@php
    $isPdf = str_starts_with($pago->file, 'data:application/pdf');
@endphp
<a href="javascript:void(0)" class="btn btn-info" onclick="openBase64Image('{{ $pago->file }}')" title="Ver Comprobante">
    @if($isPdf)
        <i class="fas fa-file-pdf"></i>
    @else
        <i class="fas fa-file-image"></i>
    @endif
</a
>
<a href="javascript:void(0)" onclick="presupuestoDetalles({{$safact->id}})" class="btn btn-warning">
  <i class="fas fa-list"></i>
</a>