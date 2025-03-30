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
                    <i class="fas fa-dollar-sign"></i> Registrar Operación
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
                                    <option  value="{{$w->codwallet}}">{{$w->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" id="saldoPorMoneda">

    </div>


    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Listado de Registros</h5>
            </div>
            <div class="card-block">
                <div class="row dt-responsive table-responsive">
                    <table id="wallet-table" class="table table-bordered table-striped nowrap table-small">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Fecha de Pago</th>
                                <th>Descripción</th>
                                <th>Operación</th>
                                <th>Moneda</th>
                                <th>Tipo</th>
                                <th>Monto</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Modal para crear una operacion --}}
<div class="modal fade createCxcModal" tabindex="-1" id="WalletRegistroModalCreate" aria-labelledby="WalletRegistroModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form enctype="multipart/form-data" id="registrarOperacion" class="modal-content">
            @csrf
            <div class="modal-header">
                <h6 class="modal-title fw-bold" id="WalletRegistroModalCreateLabel">Registrar Operación</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="control-label fw-bold mb-2">Moneda</label>
                        <div class="input-group">
                            <span class="input-group-text bg-inverse text-light"><i class="fas fa-donate"></i></span>
                            <select name="cmbmoneda" class="form-control" id="cmbmoneda" required>
                            </select>                        
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="control-label fw-bold mb-2">Tipo Moneda</label>
                        <div class="input-group">
                        <span class="input-group-text bg-inverse text-light"><i class="fas fa-hand-holding-usd"></i></span>
                          <select name="cmbtipomoneda" class="form-control" id="cmbtipomoneda" required></select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="control-label fw-bold mb-2">Operación</label>
                        <div class="input-group">
                        <span class="input-group-text bg-inverse text-light"><i class="fas fa-balance-scale"></i></span>
                          <select name="cmboperacion" class="form-control" id="cmboperacion" required>
                            <option selected></option>
                            <option value="1">DEBITO</option>
                            <option value="2">CREDITO</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="control-label fw-bold mb-2">Descripción</label>
                        <div class="input-group">
                            <span class="input-group-text bg-inverse text-light"><i class="fas fa-file-invoice"></i></span>
                          <input name="descripcion" id="descripcion" type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label fw-bold mb-2">Fecha</label>
                        <div class="input-group date" id="desdedate" data-target-input="nearest">
                            <div class="input-group-text bg-inverse text-light"><i class="fa fa-calendar"></i></div>
                          <input type="date" id="fecha" name="fecha" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label fw-bold mb-2">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-inverse text-light"><i class="fas fa-money-bill-alt"></i></span>
                          <input name="monto" id="monto" type="text" class="form-control montopcd" onkeyup="convertirmonto(this.form)" required>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    <i class="far fa-check-square"></i> Registrar
                </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="far fa-times-circle"></i> Cerrar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function convertirmonto(input){
	$(".montopcd").on({
		"focus": function (event) {
			$(event.target).select();
		},
		"keyup": function (event) {
			$(event.target).val(function (index, value ) {
				return value.replace(/\D/g, "")
					.replace(/([0-9])([0-9]{2})$/, '$1,$2')
					.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
			});	   
		}
	});
}

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

        let codwallet = $('#cmbcodwallet').val();

        $('#wallet-table').DataTable({
            "bDeferRender": true,
            "bProcessing": true,
            "sAjaxSource": "{{ route('getWalletData') }}",
            "fnServerData": function (sSource, aoData, fnCallback ) {                    
                $.getJSON(sSource, aoData, function (json) { 
                    fnCallback(json)
                    $('#saldoPorMoneda').html(json.html)
                    $('#cmbmoneda').html(json.selectMoneda)
                    $('#cmbtipomoneda').html("");
                    $("[data-toggle='tooltip']").tooltip();
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

    $('#cmbmoneda').on('input', function(){
        $.ajax({
			url: "{{ url('getTipoMonedas') }}",
            data:{
                'codmoneda': $('#cmbmoneda').val()
            },
			method: 'GET',
			success: function(response) {
				$('#cmbtipomoneda').html(response.selectTipoMoneda);
			},
			error: function(err) {
                console.log(err)
				alert('Hubo un error al cargar el chat.');
			}
		});
    })

    $('#registrarOperacion').on('submit', function(e){
        e.preventDefault();
        $("#loadingSpinner").css("display", "flex"); 

        $.ajax({
            url: "{{ url('wallet/store') }}",
            method: 'POST',
            data: {
				_token: '{{ csrf_token() }}',  // CSRF Token para seguridad
                codwallet: $('#cmbcodwallet').val(),
                codmoneda: $('#cmbmoneda').val(),
                codtipomoneda: $('#cmbtipomoneda').val(),
                codoperacion: $('#cmboperacion').val(),
                descripcion: $('#descripcion').val(),
                fecha: $('#fecha').val(),
                monto: $('#monto').val(),
            },
            success: function(response) {
                getWalletData()		
                $('#WalletRegistroModalCreate').modal('hide');
                $('#descripcion').val('')
                $('#monto').val('')
            },
			error: function(err) {
                console.log(err)
                $('#WalletRegistroModalCreate').modal('hide');
				alert(`Hubo un error en el servidor: ${err}.`);
			}
        })
    })
})
</script>
@endsection