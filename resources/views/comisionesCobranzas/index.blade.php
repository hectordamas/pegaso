@extends('layouts.admin')

@section('metadata')
    <title>Comisiones Cobranzas - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
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
                        <div class="col-md-6 form-group"></div>
                        <div class="col-md-6 form-group"></div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveInfo">Guardar Información</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Comisiones Cobranzas</h5>

                    <div>
                        <a href="javascript:void(0)" class="btn btn-success shadow rounded" id="addResponsable">
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

        $(document).ready(function() {
            getComisionesData()

            $(document).on('click', '.addInfo', function() {
                var id = $(this).data('id')
                $('#modalInfo').modal('show')
            })
        })
    </script>

    <script>
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
    </script>
@endsection
