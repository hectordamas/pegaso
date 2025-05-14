@if($licencia->comprobantes->count())
    @foreach($licencia->comprobantes as $comprobante)
        <button class="btn btn-outline-primary w-100 mb-2 text-start" onclick="verComprobante('{{ asset($comprobante->ruta) }}')">
            <i class="fas fa-eye me-2"></i> {{ basename($comprobante->ruta) }}
        </button>
    @endforeach
@else
    <p class="text-muted">No hay comprobantes adjuntos.</p>
@endif

<button class="btn btn-success w-100 mb-2 text-start"                                         
    data-bs-toggle="modal" 
    data-bs-target="#LicenciasModalUpload"
    data-id="{{ $licencia->id }}"
    onclick="setLicenciaId(this)"
    >
    <i class="fas fa-cloud-upload-alt"></i> Cargar un Nuevo Archivo
</button>