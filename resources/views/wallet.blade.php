@extends('layouts.admin')
@section('metadata')
<title>Wallet - {{ env('APP_NAME') }}</title>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5>💳 Consultar Wallet</h5>
                    <span>Seleccione un wallet para ver su saldo</span>
                </div>
                <button type="button" class="btn btn-success rounded shadow" data-bs-toggle="modal" data-bs-target="#WalletRegistroModalCreate">
                    <i class="fas fa-hand-holding-usd"></i> Registrar Operación
                </button>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label fw-bold mb-2">Wallet</label>
                        <div class="input-group">
                            <span class="input-group-text bg-inverse text-light">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <select name="cmbcodwallet" class="form-control" id="cmbcodwallet" required>
                                <option value="">Seleccionar elemento</option>
                                @foreach($wallet as $w)
                                    <option  value="{{$w->codwallet}}"><b>{{$w->nombre}}<b></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Listado de Registros</h5>
            </div>
            <div class="card-block dt-responsive table-responsive">
                <table id="wallet-table" class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <td>#</td>
                            <td>Fecha</td>
                            <td>Fecha de Pago</td>
                            <td>Descripción</td>
                            <td>Operación</td>
                            <td>Moneda</td>
                            <td>Tipo</td>
                            <td>Monto</td>
                            <td>Acción</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $(function(){
        getWalletData();
    });

    function getWalletData() {
        $("#loadingSpinner").css("display", "flex"); 

        if ($.fn.DataTable.isDataTable('#wallet-table')) {
            $('#wallet-table').dataTable().fnClearTable();
            $('#wallet-table').dataTable().fnDestroy();
        }

        let codwallet = $('#codwallet').val();

        $('#wallet-table').DataTable({
            "bDeferRender": true,
            "bProcessing": true,
            "sAjaxSource": "{{ route('getWalletData') }}",
            "fnServerData": function (sSource, aoData, fnCallback ) {                    
                $.getJSON(sSource, aoData, function (json) { 
                    fnCallback(json)
                    $("#loadingSpinner").css("display", "none"); 
                });
            },
            "bPaginate": true,
            "sPaginationType":"full_numbers",
            "iDisplayLength": 20,
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "codwallet", "value": codwallet } );
            },
            order: [[0, 'desc']],
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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

    $('#cmbcodwallet').on('input', function(){
        getWalletData()
    });
})
</script>
@endsection