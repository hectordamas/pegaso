@extends('layouts.admin')

@section('metadata')
    <title>Comisiones Cobranzas - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')
    <style>
        .responsable-row:hover {
            cursor: pointer;
        }
    </style>
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
                <div class="modal-body">
                    <form action="{{ url('addComisionesCobranzasInfo') }}" method="POST" id="formInfo" class="row">
                        @csrf
                        <input type="hidden" name="pago_id" value="">
                        <div class="col-md-6 form-group">
                            <label for="">
                                Fecha de Cobro
                            </label>
                            <input type="date" class="form-control" name="fechaDeCobro" class="form-control"
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">
                                Origen
                            </label>
                            <select name="origen" class="form-control">
                                <option value="">Selecciona un Elemento</option>
                                @foreach ($origenes as $origen)
                                    <option value="{{ $origen->id }}">{{ $origen->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">
                                Factura
                            </label>
                            <input type="text" class="form-control" name="factura">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">
                                Fecha de Factura
                            </label>
                            <input type="date" class="form-control" name="fechaDeFactura" class="form-control"
                                value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addResponsable" tabindex="-1" aria-labelledby="addResponsableLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="addResponsableLabel">Crear Responsable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ url('createResponsable') }}" method="POST" id="formResponsable" class="row g-3">
                        @csrf

                        <div class="col-md-8">
                            <label for="nombre" class="form-label">Nombre del Responsable</label>
                            <input type="text" name="name" id="nombre" class="form-control" required
                                placeholder="Ejemplo: Juan Pérez">
                        </div>

                        <div class="col-md-4">
                            <label for="comision" class="form-label">Comisión (%)</label>
                            <input type="number" name="comision" id="comision" class="form-control" required
                                min="0" max="100" step="0.01" placeholder="Ej: 5.00">
                        </div>

                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editResponsableModal" tabindex="-1" aria-labelledby="editResponsableLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="editResponsableLabel">Editar Responsable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <form id="formEditResponsable">
                        @csrf
                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Comisión (%)</label>
                            <input type="number" name="comision" id="edit_comision" class="form-control" required
                                min="0" max="100" step="0.01">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Reporte de Comisiones 💰</h5>
                    <span>Filtra y analiza las comisiones del equipo de cobranzas</span>
                </div>
                <div class="card-block">
                    <form class="row" action="{{ url('comisiones-cobranzas') }}">

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
                    <h5>Comisiones Cobranzas</h5>

                    <div>
                        <a href="javascript:void(0)" class="btn btn-success shadow rounded" id="addResponsableButton">
                            <i class="fas fa-user-alt"></i> Agregar Responsable
                        </a>

                    </div>
                </div>
                <div class="card-block">
                    <table class="table table-striped" id="ComisionesCobranzas">
                        <thead class="table-dark">
                            <th>#</th>
                            <th>
                                <p>Fecha Cobro</p>
                            </th>
                            <th>Cliente</th>
                            <th>Origen</th>
                            <th>Presupuesto</th>
                            <th>
                                <p>Fecha de presupuesto</p>
                            </th>
                            <th>Factura</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>
                                <p>Fecha de Pago</p>
                            </th>
                            <th>
                                <p>Forma de Pago</p>
                            </th>
                            <th>
                                <p>Monto Pagado (USD)</p>
                            </th>
                            <th>Tasa</th>
                            <th>
                                <p>Monto Pagado</p>
                            </th>
                            <th>% Cobrado</th>
                            <th>Saldo Pendiente</th>
                            <th>Saldo Pendiente %</th>
                            <th>Check Admin</th>
                            <th>Check Gerencia</th>
                            <th>Comentarios</th>
                            <th>Acciones</th>
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
            <div id="ResumenComisiones"></div>
        </div>
        <div class="col-xl-6">
            <div id="ResumenOrigenes"></div>
        </div>
    </div>



    <!-- Modal para mostrar Comprobante -->
    <div class="modal fade" id="comprobanteModal" tabindex="-1" aria-labelledby="comprobanteModalLabel"
        aria-hidden="true">
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
        function getComisionesData() {
            if ($.fn.DataTable.isDataTable('#ComisionesCobranzas')) {
                $('#ComisionesCobranzas').dataTable().fnClearTable();
                $('#ComisionesCobranzas').dataTable().fnDestroy();
            }

            $('#ComisionesCobranzas').DataTable({
                order: [
                    [0, 'desc']
                ],
                "bDeferRender": true,
                "bProcessing": true,
                "sAjaxSource": "{{ route('comisiones.getComisionesData') }}",
                "fnServerData": function(sSource, aoData, fnCallback) {
                    $.getJSON(sSource, aoData, function(json) {
                        fnCallback(json)

                        $('#ResumenComisiones').html(json.html_responsables);
                        $('#ResumenOrigenes').html(json.html_origenes);

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

                // ✅ Resetear correctamente el formulario
                $('#formInfo')[0].reset();

                // ✅ (opcional) Si quieres limpiar selects con select2, hazlo también
                // $('#formInfo select').val('').trigger('change');

                // 🔥 Traer la data del pago vía AJAX
                $.ajax({
                    url: '/getPagoInfo/' + id, // <-- Ajusta la ruta según tu backend
                    type: 'GET',
                    success: function(data) {
                        // data debería venir con la info del pago: 
                        // { fechaDeCobro, origen_id, factura, fechaDeFactura, ... }

                        // ✅ Llenar los campos con la nueva data
                        $('#formInfo [name="fechaDeCobro"]').val(data.fechaDeCobro);
                        $('#formInfo [name="origen"]').val(data.origen_id);
                        $('#formInfo [name="factura"]').val(data.factura);
                        $('#formInfo [name="fechaDeFactura"]').val(data.fechaDeFactura);

                        // ✅ Si tienes un input oculto para el id del pago, actualízalo también
                        $('#formInfo [name="pago_id"]').val(id);

                        // ✅ Mostrar modal
                        $('#modalInfo').modal('show');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Error al cargar la información del pago.');
                    }
                });
            });

            $(document).on('click', '#addResponsableButton', function() {
                $('#formResponsable')[0].reset();
                $('#addResponsable').modal('show');

            })


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

            $(document).on('submit', '#formResponsable', function(e) {
                e.preventDefault();

                let form = $(this);
                let data = form.serialize();

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: data,
                    beforeSend: function() {
                        form.find('button[type="submit"]').prop('disabled', true).text(
                            'Guardando...');
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Responsable creado',
                            text: res.message || 'El responsable se creó correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#addResponsable').modal('hide');
                        form[0].reset();

                        // Si tienes una tabla o lista de responsables, puedes actualizarla aquí
                        // loadResponsables();
                        getComisionesData();

                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'No se pudo crear el responsable'
                        });
                    },
                    complete: function() {
                        form.find('button[type="submit"]').prop('disabled', false).text(
                            'Guardar');
                    }
                });
            });

            $(document).on('click', '.responsable-row', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let comision = $(this).data('porcentaje');

                // Rellenar el formulario
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_comision').val(comision);

                // Mostrar modal
                $('#editResponsableModal').modal('show');
            });

            $(document).on('submit', '#formEditResponsable', function(e) {
                e.preventDefault();
                let form = $(this);

                $.ajax({
                    url: "{{ url('updateResponsable') }}",
                    type: "POST",
                    data: form.serialize(),
                    beforeSend: function() {
                        form.find('button[type="submit"]').prop('disabled', true).text(
                            'Guardando...');
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizado',
                            text: res.message ||
                                'Responsable actualizado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#editResponsableModal').modal('hide');
                        // Recargar el resumen sin recargar toda la página
                        getComisionesData();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Error al actualizar el responsable'
                        });
                    },
                    complete: function() {
                        form.find('button[type="submit"]').prop('disabled', false).text(
                            'Guardar Cambios');
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
    </script>

    <script>
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

            $.post(`/comisiones/detallecxc/${id}/check`, data, function(res) {
                if (res.success) {
                    console.log('Guardado con éxito');
                }
            });
        });
    </script>
@endsection
