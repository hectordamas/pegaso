<a href="javascript:void(0);" 
    data-toggle="tooltip" 
    data-placement="top"
    title="Abrir Chat"
    id="chatButton_{{ $p->id }}"
    onclick="openChatEntregaModal({{ $p->id }})"
    class="btn btn-dark position-relative">
    <i class="far fa-comments"></i> Abrir Chat

    @if($p->chatentrega->count() > 0)
    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
        {{ $p->chatentrega->count() }}
    </span>
    @endif
</a>
@if(!in_array($p->codestatus, [13]))
<a href="javascript:void(0);" 
    data-bs-toggle="modal" 
    data-bs-target="#EntregaModalEdit"
    onclick="actualizarEntrega({{ $p->id }})"
    class="btn btn-success">
    <i class="fas fa-edit"></i> Actualizar Estatus
</a>
@endif
<a href="javascript:void(0);" 
    onclick="entregaDetalles({{ $p->id }})"
    class="btn btn-warning">
    <i class="fas fa-list"></i> Ver Detalles
</a>