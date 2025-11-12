@extends('layouts.admin')

@section('metadata')
    <title>Comisiones Proyecto - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    @php
        $meses = collect([
            ['numero' => '01', 'nombre' => 'Enero'],
            ['numero' => '02', 'nombre' => 'Febrero'],
            ['numero' => '03', 'nombre' => 'Marzo'],
            ['numero' => '04', 'nombre' => 'Abril'],
            ['numero' => '05', 'nombre' => 'Mayo'],
            ['numero' => '06', 'nombre' => 'Junio'],
            ['numero' => '07', 'nombre' => 'Julio'],
            ['numero' => '08', 'nombre' => 'Agosto'],
            ['numero' => '09', 'nombre' => 'Septiembre'],
            ['numero' => '10', 'nombre' => 'Octubre'],
            ['numero' => '11', 'nombre' => 'Noviembre'],
            ['numero' => '12', 'nombre' => 'Diciembre'],
        ])->map(fn($o) => (object) $o);
    @endphp
    <!-- Modal Para ver detalles de Presupuesto -->
    <div class="modal fade PresupuestoModalView" tabindex="-1" id="PresupuestoModalView"
        aria-labelledby="PresupuestoModalViewLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="PresupuestoModalViewLabel">Detalles del Presupuesto #<span
                            class="presupuestoId"></span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="productosPresupuesto">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="far fa-times-circle"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Información</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="formEditSafactContainer">

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Reporte de Comisiones 💰</h5>
                    <span>Filtra y analiza las comisiones de la Gerencia de Proyectos</span>
                </div>
                <div class="card-block">
                    <form class="row" action="{{ url('comisiones-proyecto') }}">

                        <div class="col-md-6 form-group">
                            <label for="mes" class="fw-bold mb-2">Selecciona un Mes</label>
                            <select name="mes" id="mes" class="form-control">
                                @foreach ($meses as $m)
                                    <option {{ $m->numero == $mes ? 'selected' : '' }} value="{{ $m->numero }}">
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" id="filtrar">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Comisiones Gerencia de Proyecto</h5>

                </div>
                <div class="card-block">
                    <table class="table table-striped" id="ComisionesProyecto">
                        <thead class="table-dark">
                            <th>#</th>
                            <th>Presupuesto</th>
                            <th>
                                <p>Fecha de presupuesto</p>
                            </th>
                            <th>
                                <p>Fecha Cobro</p>
                            </th>
                            <th>Cliente</th>
                            <th>Base Imponible</th>
                            <th>Total Proyectos</th>
                            <th>Total Soporte</th>
                            <th>Total Cobrado</th>
                            <th>Porcentaje Cobrado</th>
                            <th>Comisión Proyecto</th>
                            <th>Comisión Servicio</th>
                            <th>Total Comisión</th>
                            <th>% Pendiente de Cobro</th>
                            <th>Pendiente de Cobro</th>
                            <th>Check Admin</th>
                            <th>Check Gerencia</th>
                            <th>Comprobantes</th>
                            <th>Detalles</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div id="tablaDeComisiones"></div>
        </div>
    </div>

    <!-- Offcanvas archivos -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filesOffCanvas" aria-labelledby="filesOffCanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Comprobantes Adjuntos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" id="comprobantesAdjuntosSafact">
            <p class="text-center">Cargando...</p>
        </div>
    </div>

    <!-- Modal para mostrar Comprobante -->
    <div class="modal fade" id="comprobanteModal" tabindex="-1" aria-labelledby="comprobanteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="comprobanteModalLabel">Comprobante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Imagen -->
                    <img id="comprobanteImg" src="" alt="Comprobante" class="img-fluid d-none" />

                    <!-- PDF -->
                    <iframe id="comprobantePdf" allow="fullscreen" src="" style="width: 100%; height: 80vh;"
                        frameborder="0" class="d-none"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        //Comprobantes
        function getComisionesData() {
            if ($.fn.DataTable.isDataTable('#ComisionesProyecto')) {
                $('#ComisionesProyecto').dataTable().fnClearTable();
                $('#ComisionesProyecto').dataTable().fnDestroy();
            }

            $('#ComisionesProyecto').DataTable({
                order: [
                    [0, 'desc']
                ],
                "bDeferRender": true,
                "bProcessing": true,
                "sAjaxSource": "{{ route('comisionesProyecto.getComisionesData') }}",
                "fnServerData": function(sSource, aoData, fnCallback) {
                    $.getJSON(sSource, aoData, function(json) {
                        fnCallback(json)
                        if (json.tablaDeComisiones) {
                            $('#tablaDeComisiones').html(json.tablaDeComisiones)
                        }
                    });
                },
                "bPaginate": true,
                "sPaginationType": "full_numbers",
                "iDisplayLength": 20,
                "fnServerParams": function(aoData) {
                    var mes = $('#mes').val();
                    aoData.push({
                        "name": "mes",
                        "value": mes
                    });
                },
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                pageLength: 20,
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                }
            });
        }

        //Comprobantes
        function cargarComprobantes(safactId) {
            $('#comprobantesAdjuntosSafact').html('<p class="text-center">Cargando...</p>');
            $('#filesOffCanvas').offcanvas('show');

            $.get(`/comisiones/safact/${safactId}/comprobantes`, function(data) {
                $('#comprobantesAdjuntosSafact').html(data.html);
            });
        }

        function openBase64Image(base64) {
            const mime = base64.substring(5, base64.indexOf(';'));

            const img = document.getElementById('comprobanteImg');
            const pdf = document.getElementById('comprobantePdf');

            if (mime === 'application/pdf') {
                // Mostrar PDF
                img.classList.add('d-none');
                pdf.classList.remove('d-none');
                pdf.src = base64;
            } else {
                // Mostrar imagen
                pdf.classList.add('d-none');
                img.classList.remove('d-none');
                img.src = base64;
            }

            $('#comprobanteModal').modal('show');
        }

        $(document).ready(function() {
            getComisionesData();

            $(document).on('click', '.addInfo', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: '/comisionesSoporte/getSafactInfo/' + id,
                    type: 'GET',
                    success: function(data) {

                        $('#formEditSafactContainer').html(data.html);
                        $('#modalInfo').modal('show');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Error al cargar la información del pago.');
                    }
                });
            });


            $(document).on('submit', '#formInfo', function(e) {
                e.preventDefault(); // Evita que recargue la página

                let form = $(this);
                let formData = form.serialize(); // Serializa todos los inputs (incluye el token CSRF)

                $.ajax({
                    url: form.attr('action'), // Usa la URL del formulario
                    type: form.attr('method'), // Normalmente POST
                    data: formData,
                    beforeSend: function() {
                        // Opcional: desactivar el botón de envío
                        form.find('button[type="submit"]').prop('disabled', true).text(
                            'Guardando...');
                    },
                    success: function(response) {
                        // ✅ Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Guardado correctamente',
                            text: response.message ||
                                'Los datos fueron guardados exitosamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // ✅ Cierra el modal si todo fue bien
                        $('#modalInfo').modal('hide');

                        // ✅ Limpia el formulario si deseas
                        form[0].reset();

                        getComisionesData();
                    },
                    error: function(xhr) {
                        // ❌ Mostrar errores
                        let msg = 'Ocurrió un error al guardar la información.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    },
                    complete: function() {
                        // Reactiva el botón
                        form.find('button[type="submit"]').prop('disabled', false).text(
                            'Guardar');
                    }
                });
            });

        })

        // Ver Detalles
        window.presupuestoDetalles = function(presupuestoId) {
            $("#loadingSpinner").css("display", "flex");

            $.ajax({
                url: '{{ url('presupuestos/ver-detalles') }}' + '/' +
                    presupuestoId, // Asegúrate de que esta ruta sea correcta
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' // CSRF Token para seguridad
                },
                success: function(response) {
                    $('.presupuestoId').html(presupuestoId);
                    $("#PresupuestoModalView").modal("show")
                    $('#productosPresupuesto').html(response.items);
                    $("#loadingSpinner").css("display", "none");

                },
                error: function(response) {
                    alert('Error de Conexión')
                    $("#loadingSpinner").css("display", "none");
                }
            });
        }

        $(document).on('change', '.check-admin, .check-manager', function() {
            let id = $(this).data('id');
            let isAdmin = $(this).hasClass('check-admin');
            let checked = $(this).is(':checked') ? 1 : 0;

            let data = {
                _token: '{{ csrf_token() }}',
            };

            if (isAdmin) {
                data.check_admin = checked;
            } else {
                data.check_manager = checked;
            }

            $.post(`/comisionesProyecto/safact/${id}/check`, data, function(res) {
                if (res.success) {
                    console.log('Guardado con éxito');
                }
            });
        });
    </script>
@endsection
