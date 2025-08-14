@extends('layouts.admin')

@section('metadata')
    <title>Informe de Proyectos - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')
    <style>
        .project-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .project-summary h6 {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .project-summary p {
            margin: 0;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Editar Informe de Proyecto</h5>
                <span>📝 Redacta el informe del proyecto y guarda los cambios.</span>
            </div>
            <div class="card-body">

                <!-- Resumen del Proyecto -->
                <div class="project-summary">
                    <h6>Resumen del Proyecto</h6>
                    <p><strong>ID:</strong> {{ $proyecto->id }}</p>
                    <p><strong>Número:</strong> {{ $proyecto->numerod }}</p>
                    <p><strong>Descripción:</strong> {{ $proyecto->descrip ?? 'N/A' }}</p>
                    <p><strong>Vendedor:</strong> {{ $proyecto->savend->descrip ?? 'N/A' }}</p>
                    <p><strong>Estatus:</strong> <span class="badge" style="background: {{ $proyecto->estatusPre->color ?? '#e9e9e9' }}">{{ $proyecto->estatusPre->nombre ?? 'N/A' }}</span></p>
                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($proyecto->fechae)->format('d/m/Y h:i a') }}</p>
                    <p><strong>Total:</strong> {{ number_format($proyecto->mtototal, 2, ',', '.') }}</p>
                </div>

                <!-- Editor de Informe -->
                <form id="formInforme">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="proyectoId" value="{{ $proyecto->id }}">
                    <textarea id="editorInforme">{{ $proyecto->informe ?? '<h3 style="text-align:center">Redacte aquí su informe</h3><p style="text-align:justify">Agregue los detalles del proyecto...</p>' }}</textarea>

                    <div class="mt-3">
                        <button type="button" id="guardarInforme" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Informe
                        </button>
                        <a href="{{ route('proyectos.informe.pdf', $proyecto->id) }}" class="btn btn-danger float-center" target="_blank">
                            <i class="far fa-file-pdf"></i> Descargar Informe
                        </a>
                        <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
                            <i class="far fa-times-circle"></i> Volver
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- CKEditor -->
    <script src="{{ asset('assets/adminty/assets/pages/ckeditor/ckeditor.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let editorInstance;

        $(function() {
            // Inicializar CKEditor
            editorInstance = CKEDITOR.replace('editorInforme', {
                height: 300
            });

            // Guardar informe
            $('#guardarInforme').on('click', function() {
                let contenido = editorInstance.getData();
                let proyectoId = $('#proyectoId').val();

                Swal.fire({
                    title: 'Guardando informe...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: '/proyectos/' + proyectoId + '/informe',
                    type: 'PUT',
                    data: {
                        informe: contenido,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Informe actualizado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo guardar el informe.', 'error');
                    }
                });
            });
        });
    </script>
@endsection
