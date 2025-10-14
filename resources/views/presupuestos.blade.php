@extends('layouts.admin')

@section('metadata')
    <title>Presupuestos - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')
    <style>
        .status-card {
            cursor: pointer;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .status-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .status-card:active {
            transform: scale(0.95);
        }
    </style>
@endsection

@section('content')
    <input type="hidden" value="{{ $client ?? '' }}" id="client">

    <div class="row">
        @php
            $estatusCollection = [
                [
                    'id' => 1,
                    'name' => 'Pendientes',
                    'count' => 0,
                    'color' => 'warning',
                    'icon' => 'fas fa-hourglass-half',
                ],
                ['id' => 2, 'name' => 'Aprobados', 'count' => 0, 'color' => 'success', 'icon' => 'fas fa-check-circle'],
                //['id' => 3, 'name' => 'Proyectos', 'count' => 0, 'color' => 'primary', 'icon' => 'fas fa-project-diagram'],
                [
                    'id' => 4,
                    'name' => 'Completados',
                    'count' => 0,
                    'color' => 'dark',
                    'icon' => 'fas fa-clipboard-check',
                ],
                ['id' => 5, 'name' => 'Rechazados', 'count' => 0, 'color' => 'danger', 'icon' => 'fas fa-times-circle'],
                [
                    'id' => 6,
                    'name' => 'Descartados',
                    'count' => 0,
                    'color' => 'secondary',
                    'icon' => 'fas fa-trash-alt',
                ],
            ];
        @endphp

        {{-- Consultar Presupuestos por Estatus --}}

        @foreach ($estatusCollection as $status)
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card text-white bg-{{ $status['color'] }} shadow status-card getPresupuestosByStatus"
                    data-id="{{ $status['id'] }}">
                    <div class="card-body text-center">
                        <i class="{{ $status['icon'] }} fa-3x mb-2"></i>
                        <h5 class="mt-2">{{ $status['name'] }}</h5>
                        <h4 class="fw-bold count{{ $status['id'] }}">0</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Consultar Presupuestos por filtros --}}

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Consultar Presupuestos</h5>
                    <span>💸 Completa los campos para filtrar y consultar Presupuestos.</span>
                </div>
                <div class="card-block">
                    <div class="row" id="presupuestoCreateForm">
                        <div class="col-md-3 form-group">
                            <label for="from">Desde</label>
                            <input type="date" class="form-control" name="from" id="from">
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="until">Hasta</label>
                            <input type="date" class="form-control" name="until" id="until">
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="codvend">Vendedor</label>
                            <select name="codvend" id="codvend" class="form-control">
                                <option selected value="">Elige una Opción</option>
                                @foreach ($vendedores as $vendedor)
                                    <option value="{{ $vendedor->codvend }}">{{ $vendedor->descrip }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary mt-3" id="consultarPresupuestos"><i
                                    class="fas fa-search"></i> Consultar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Presupuestos</h5>
                    <span>📋 Reporte / Historial de Presupuestos.</span>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="dt-responsive table-responsive">
                            <table id="presupuestos-table" class="table table-striped table-bordered nowrap table-small">
                                <thead class="table-dark">
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Días</th>
                                        <th>Documento</th>
                                        <th>Cliente</th>
                                        <th>Exento</th>
                                        <th>Base Imponible</th>
                                        <th>IVA</th>
                                        <th>Factor</th>
                                        <th>Total Bs.</th>
                                        <th>Total USD.</th>
                                        <th>Vendedor</th>
                                        <th>Estatus</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="presupuestosTbody">
                                    {{-- @include('presupuestos.tr-index') --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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


    <!-- Modal Cambiar Estatus de Presupuesto -->
    <div class="modal fade PresupuestoModalEdit" tabindex="-1" id="PresupuestoModalEdit"
        aria-labelledby="PresupuestoModalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="PresupuestoModalEditLabel">Cambiar Estatus de Presupuesto #<span
                            class="presupuestoId"></span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="presupuestoUpdateform" method="POST">
                        @csrf

                        <input type="hidden" name="presupuestoId" id="presupuestoId">

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="control-label">Estatus</label>
                                <select name="codestatus" class="form-control" id="estatusPresupuestoId" required>
                                    @foreach ($estatus as $e)
                                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group abonado-container d-none">
                                <label for="" class="control-label">Monto Abonado</label>
                                <input type="text" class="form-control montopcd" id="abono" name="abono"
                                    onkeyup="convertirmonto(this.form)" required>
                            </div>

                            <div class="col-md-6 form-group abonado-container d-none">
                                <label for="fechaDePago">Fecha de Pago</label>
                                <input type="date" class="form-control" name="fechaDePago" id="fechaDePago"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 form-group abonado-container d-none">
                                <label for="" class="control-label">Banco</label>
                                <select name="bank_id" id="bank_id" class="form-control" required>
                                    <option value="">Selecciona un Banco</option>
                                    @foreach ($banks as $b)
                                        <option value="{{ $b->id }}">{{ $b->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group abonado-container d-none">
                                <label for="control-label">Subir Comprobante</label>
                                <input type="file" id="file" name="file" accept="image/*,application/pdf"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6 form-group abonado-container d-none">
                                <label for="" class="control-label">Observacion</label>
                                <textarea name="observacion" id="observacion" class="form-control"></textarea>
                            </div>

                            <div class="col-sm-6 form-group abonado-container d-none">
                                <label class="control-label fw-bold d-block mb-2">Aplica Comisión</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="aplicaComision"
                                        name="aplicaComision" value="1">
                                    <label class="form-check-label" for="aplicaComision">Sí</label>
                                </div>
                            </div>

                            <div class="col-md-6 form-group d-none" id="razon-container">
                                <label for="" class="control-label" id="razonLabel">Motivo</label>
                                <textarea name="razon" id="razon" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="far fa-times-circle"></i> Cerrar
                            </button>
                            <button id="presupuestoUpdateStatusButton" class="btn btn-success float-center">
                                <i class="fas fa-sync"></i> Actualizar Estatus
                            </button>
                        </div>
                    </div> <!-- Cierre del formulario -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function convertirmonto(input) {
            $(".montopcd").on({
                "focus": function(event) {
                    $(event.target).select();
                },
                "keyup": function(event) {
                    $(event.target).val(function(index, value) {
                        return value.replace(/\D/g, "")
                            .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                            .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                    });
                }
            });
        }

        var presupuestosTable;

        $(function() {
            getPresupuestosData();
        });

        function getPresupuestosData(updated) {
            const exp = $('#exp').val();

            $("#loadingSpinner").css("display", "flex");

            if ($.fn.DataTable.isDataTable('#presupuestos-table')) {
                $('#presupuestos-table').dataTable().fnClearTable();
                $('#presupuestos-table').dataTable().fnDestroy();
            }

            let id = $(this).data('id')
            let from = $('#from').val();
            let until = $('#until').val();
            let codvend = $('#codvend').val();
            let codestatus = id || $('#codestatus').val(); // this ahora está correctamente referenciado
            let client = $('#client').val();

            $('#presupuestos-table').DataTable({
                columns: [{
                        visible: false
                    }, // columna 0: fecha cruda (oculta, para orden)
                    null, // columna 1: ID
                    {
                        orderData: [0]
                    }, // columna 2: fecha formateada, ordena según la columna 0
                    null, // columna 3: días
                    null, // columna 4: número doc
                    null, // columna 5: descripción
                    null, // columna 6: exento
                    null, // columna 7: gravable
                    null, // columna 8: impuesto
                    null, // columna 9: factor
                    null, // columna 10: total
                    null, // columna 11: total / factor
                    null, // columna 12: vendedor
                    null, // columna 13: estatus
                    null // columna 14: acciones
                ],
                order: [
                    [0, 'desc']
                ],
                "bDeferRender": true,
                "bProcessing": true,
                "sAjaxSource": "{{ route('presupuestos.data') }}",
                "fnServerData": function(sSource, aoData, fnCallback) {
                    $.getJSON(sSource, aoData, function(json) {
                        fnCallback(json)

                        $("#loadingSpinner").css("display", "none");

                        if (!id) {
                            $('.count1').html(json.pendientes);
                            $('.count2').html(json.aprobados);
                            $('.count3').html(json.proyectos);
                            $('.count4').html(json.completados);
                            $('.count5').html(json.rechazados);
                            $('.count6').html(json.descartados);
                        }

                        if (updated) {
                            Swal.fire({
                                text: "Estatus actualizado con éxito!",
                                icon: "success",
                            });
                        }
                    });
                },
                "bPaginate": true,
                "sPaginationType": "full_numbers",
                "iDisplayLength": 20,
                "fnServerParams": function(aoData) {
                    aoData.push({
                        "name": "from",
                        "value": from
                    });
                    aoData.push({
                        "name": "until",
                        "value": until
                    });
                    aoData.push({
                        "name": "codvend",
                        "value": codvend
                    });
                    aoData.push({
                        "name": "codestatus",
                        "value": codestatus
                    });
                    aoData.push({
                        "name": "client",
                        "value": client
                    });
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        text: 'Copiar',
                        action: protegerExportacion($.fn.dataTable.ext.buttons.copyHtml5.action)
                    },
                    {
                        extend: 'csv',
                        text: 'Exportar CSV',
                        action: protegerExportacion($.fn.dataTable.ext.buttons.csvHtml5.action)
                    },
                    {
                        extend: 'excel',
                        text: 'Exportar Excel',
                        action: protegerExportacion($.fn.dataTable.ext.buttons.excelHtml5.action)
                    },
                    {
                        extend: 'pdf',
                        text: 'Exportar PDF',
                        action: protegerExportacion($.fn.dataTable.ext.buttons.pdfHtml5.action)
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        action: protegerExportacion($.fn.dataTable.ext.buttons.print.action)
                    }
                ],
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

        $('.getPresupuestosByStatus').on('click', function() {
            getPresupuestosData.call(this);
        });

        $('#consultarPresupuestos').on('click', function() {
            getPresupuestosData();
        });


        $('#presupuestoUpdateStatusButton').on('click', function() {
            var codestatus = $('#estatusPresupuestoId').val();
            var presupuestoId = $('#presupuestoId').val();
            var razon = $('#razon').val();
            var abono = $('#abono').val();
            var observacion = $('#observacion').val();
            var fileInput = $("#file")[0].files[0];
            var fechaDePago = $('#fechaDePago').val();
            var aplicaComision = $('#aplicaComision').is(':checked') ? 1 : 0;

            var bankId = $('#bank_id').val();
            if (fileInput) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    console.log(e.target.result); // Verifica el encabezado

                    sendPresupuestoUpdate(e.target.result);
                };
                reader.readAsDataURL(fileInput);
            } else {
                sendPresupuestoUpdate(null); // No se seleccionó archivo
            }

            function sendPresupuestoUpdate(fileBase64) {
                $.ajax({
                    url: '{{ route('presupuestos.update') }}',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        codestatus: codestatus,
                        presupuestoId: presupuestoId,
                        abono: abono,
                        razon: razon,
                        fechaDePago: fechaDePago,
                        file: fileBase64,
                        observacion: observacion,
                        bank_id: bankId,
                        aplicaComision: aplicaComision
                    },
                    success: function(response) {
                        getPresupuestosData(response.success);
                        $("#PresupuestoModalEdit").modal("hide");
                    },
                    error: function(response) {
                        alert('Error de Conexión');
                        console.log(response);
                        $("#loadingSpinner").hide();
                    }
                });
            }
        });

        $('#PresupuestoModalEdit').on('show.bs.modal', function() {
            $('#razon').val('');
            $('#abono').val('');
            $('#file').val('');
        });

        $("#estatusPresupuestoId").on('change', function() {
            var codestatus = $(this).val();

            $('#razon').val('');
            if (codestatus == 5 || codestatus == 6) {
                $('#razon-container').removeClass('d-none'); // Muestra el campo
                codestatus == 5 ? $('#razonLabel').html('Razón del Rechazo:') : $('#razonLabel').html(
                    'Razón del Descarte:');
            } else {
                $('#razon-container').addClass('d-none'); // Oculta el campo si no es 5 o 6
            }

            $('#abono').val('');
            $('#file').val('');
            if (codestatus == 3 || codestatus == 11 || codestatus == 14) {
                $('.abonado-container').removeClass('d-none'); // Muestra el campo
            } else {
                $('.abonado-container').addClass('d-none'); // Oculta el campo si no es 5 o 6
            }
        });
    </script>
@endsection
