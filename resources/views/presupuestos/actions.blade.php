@if(in_array($p->codestatus, [1, 2]))
<a href="javascript:void(0);" 
    data-bs-toggle="modal" 
    data-bs-target="#PresupuestoModalEdit"
    onclick="actualizarPresupuesto({{ $p->id }})"
    class="btn btn-success">
    <i class="fas fa-edit"></i> Actualizar Estatus
</a>
@endif
<a href="javascript:void(0);" 
    onclick="presupuestoDetalles({{ $p->id }})"
    class="btn btn-warning">
    <i class="fas fa-list"></i> Ver Detalles
</a>