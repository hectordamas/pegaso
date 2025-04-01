@extends('layouts.admin')

@section('metadata')
    <title>Cuentas Por Cobrar - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Cuentas por Cobrar</h5>
                <span>Selecciona una empresa para consultar Cuentas por Cobrar</span>
            </div>
            <div class="card-block">
                <div class="row">

                    <!-- Selección de Empresa -->
                    <div class="col-lg-6 col-md-6 form-group">
                        <label for="cmbcodwallet" class="font-weight-bold mb-2 fw-bold">Empresa</label>
                        <select name="cmbcodwallet" class="form-control" id="cmbcodwallet" required>
                            <option selected>Seleccione una empresa</option>
                            @foreach($wallet as $w)
                                @if($w->cxc)
                                    <option value="{{ $w->codwallet }}">{{ $w->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Saldo Pendiente -->
                    <div class="col-lg-6 col-md-6">
                        <div class="card bg-c-green text-white shadow">
                            <div class="card-block">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <p class="m-b-5 fw-bold">Saldo por Cobrar ($)</p>
                                        <h4 class="m-b-0" id="saldocxc">${{ number_format(0,2,',','.') }}</h4>
                                    </div>
                                    <div class="col col-auto text-end">
                                        <i class="fas fa-hand-holding-usd f-50 text-c-green"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div> <!-- /.row -->

                <div class="row">
                    <div class="col-md-3" align="center">
                        <div class="form-group label-floating">
                            <label class="control-label" onclick="listaShow('#00FF00');" style="font-weight:bold !important;font-size:1em !important;">COBRAR</label>
                            <br><input disabled="" name="color1" id="color1" type="color" value="#00FF00" class="">
                        </div>
                    </div>
                    <div class="col-md-3" align="center">
                        <div class="form-group label-floating">
                            <label class="control-label" onclick="listaShow('#FFA500');" style="font-weight:bold !important;font-size:1em !important;">JULIO FARFAN</label>
                            <br><input disabled="" name="color2" id="color2" type="color" value="#FFA500" class="">
                        </div>
                    </div>
                    <div class="col-md-3" align="center">
                        <div class="form-group label-floating">
                            <label class="control-label" onclick="listaShow('#0000FF');" style="font-weight:bold !important;font-size:1em !important;">DANIEL SOUSA</label>
                            <br><input disabled="" name="color3" id="color3" type="color" value="#0000FF" class="">
                        </div>
                    </div>
                    <div class="col-md-3" align="center">
                        <div class="form-group label-floating">
                            <label class="control-label" onclick="listaShow('#FF0000');" style="font-weight:bold !important;font-size:1em !important;">NO COBRAR</label>
                            <br><input disabled="" name="color4" id="color4" type="color" value="#FF0000" class="">
                        </div>
                    </div>
                </div>

            </div> <!-- /.card-body -->
        </div> <!-- /.card -->
    </div> <!-- /.col-md-12 -->
</div> <!-- /.row -->

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5>Operaciones Diarias</h5>
                    <span>Resultados de consulta de cuentas por cobrar</span>
                </div>
                <button type="button" class="btn btn-primary shadow rounded" data-bs-toggle="modal" data-bs-target="#createCxcModal">
                    <i class="fas fa-coins"></i> Registrar Cuentas por Cobrar
                </button>
            </div>
            <div class="card-block">

                <div class="row" id="searchContainer">
                    <div class="col-md-6 form-group">
                        <input type="text" class="form-control" id="searchCxc" placeholder="Buscar por Código / RIF / Razón Social">
                    </div>
                </div>

                <div id="saldosPorClienteContainer">

                </div>

                <div class="row">
                    <div class="col-md-12" id="cxcContainer">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para crear una cxc Nueva!!!!! --}}
<div class="modal fade createCxcModal" tabindex="-1" id="createCxcModal" aria-labelledby="createCxcModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form enctype="multipart/form-data" id="formReg" class="modal-content">
            @csrf
            <div class="modal-header">
                <h6 class="modal-title fw-bold" id="createCxcModalLabel">Registrar Cuenta por Cobrar</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Selección de Empresa -->
                    <div class="form-group col-md-6">
                        <label for="empresa" class="font-weight-bold mb-2 fw-bold">Empresa</label>
                        <select name="empresa" class="form-control" id="empresa" required>
                            <option selected>Seleccione una empresa</option>
                            @foreach($wallet as $w)
                                @if($w->cxc)
                                    <option value="{{ $w->codwallet }}">{{ $w->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="clienteSaint" class="fw-bold">Cliente Saint</label>
                        <select name="codclie" required id="clienteSaint" class="form-control">
                            <option value="">Elija una Opción</option>
                            @foreach($saclie as $item)
                                <option value="{{ $item->codclie }}">{{ $item->descrip }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="fw-bold">Fecha</label>
                        <input type="date" id="fechacxc" class="form-control" name="fecha" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label fw-bold">Monto</label>
                        <input name="monto" id="montocxc" type="text" class="montopcd form-control" onkeyup="convertirmonto(this.form)" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="" class="control-label fw-bold">Descripción</label>
                        <input type="text" id="descripcioncxc" class="form-control" name="description">
                    </div> 
                    <div class="form-group col-md-6">
                        <label for="" class="control-label fw-bold">Responsable</label>
                        <input type="text" id="responsablecxc" readonly class="form-control" name="responsable" value="{{ Auth::user()->name }}">
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


<!-- Modal Para ver detalles de cxc por cliente -->
<div class="modal fade CxcDetailsModalView" tabindex="-1" id="CxcDetailsModalView" aria-labelledby="CxcDetailsModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="CxcDetailsModalViewLabel">Detalles del Cliente: <span class="codclie badge badge-danger"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="cxcDetailsTableContainer">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="far fa-times-circle"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Para Adjuntar un abono -->
<div class="modal fade AbonoModalView" tabindex="-1" id="AbonoModalView" aria-labelledby="AbonoModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form enctype="multipart/form-data" id="formRegAbono" class="modal-content">
            @csrf
            <div class="modal-header">
                <h6 class="modal-title" id="AbonoModalViewLabel">Cuenta por Cobrar #<span class="codcxc"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input id="inputcxc" type="hidden">

                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label class="control-label fw-bold">Forma de Pago</label>
                        <select class="form-control" name="cmbcodtipomonedaabono" id="cmbcodtipomonedaabono" required>
                            <option value="" selected></option>
                            @foreach($tipomoneda as $tm)
                                <option value="{{$tm->codtipomoneda}}">{{$tm->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="control-label fw-bold">Monto</label>
                        <input name="montoabono" id="montoabono" type="text" class="montopcd form-control" onkeyup="convertirmonto(this.form)" required>
                    </div>

                    <div class="col-sm-12 form-group">
                        <label for="" class="control-label fw-bold mb-2">Adjuntar Comprobante</label>
                        <input type="file" id="file" name="file" accept="image/*" class="form-control" required>
                    </div>

                    <div class="col-md-12 form-group">
                        <label for="" class="control-label fw-bold">Descripción</label>
                        <textarea required class="form-control" id="descripcionabono" name="descripcionabono" rows="2" onKeyUp="this.value=this.value.toUpperCase();"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    <i class="far fa-check-square"></i> Guardar
                </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="far fa-times-circle"></i> Cerrar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal abono details view--}}
<div class="modal fade AbonoDetailsModalView" tabindex="-1" id="AbonoDetailsModalView" aria-labelledby="AbonoDetailsModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h6 class="modal-title" id="AbonoDetailsModalViewLabel">Abonos | Cuenta por Cobrar #<span class="codcxc"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="AbonoDetailsModalContainer">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="far fa-times-circle"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openBase64Image(base64) {
        let newWindow = window.open('about:blank');
        newWindow.document.write(`
            <html>
            <head>
                <title>Comprobante</title>
                <style>
                    body { 
                        background-color: black; 
                        display: flex; 
                        justify-content: center; 
                        align-items: center; 
                        height: 100vh; 
                        margin: 0;
                    }
                    img { 
                        max-width: 90vw; 
                        max-height: 90vh; 
                        border-radius: 10px; 
                    }
                </style>
            </head>
            <body>
                <img src="${base64}" />
            </body>
            </html>
        `);
    }
</script>
    

<script>
    var cxcTable;
    var cxcDetailsTable;

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


    function initializeCxcTable(){
        if ($.fn.DataTable.isDataTable('#cxc-table')) {
            $('#cxc-table').dataTable().fnClearTable();
			$('#cxc-table').dataTable().fnDestroy();
        }

        if($('#cxc-table').length){
            cxcTable = $('#cxc-table').DataTable({
		    	deferRender: true, // Solo renderiza lo visible
		    	order: [[0, 'desc']],
		    	responsive: true,
		    	lengthChange: false,
		    	autoWidth: false,
		    	lengthMenu: [
		    		[10, 50, 100, 150, -1],
		    		[10, 50, 100, 150, 'Todos']
		    	],
		    	dom: 'Bfrtip',
		    	buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
		    	language: {
		    		sProcessing: "Procesando...",
		    		sLengthMenu: "Mostrar _MENU_ registros",
		    		sZeroRecords: "No se encontraron resultados",
		    		sEmptyTable: "Ningún dato disponible en esta tabla",
		    		sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		    		sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
		    		sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
		    		sSearch: "Buscar:",
		    		oPaginate: {
		    			sFirst: "Primero",
		    			sLast: "Último",
		    			sNext: "Siguiente",
		    			sPrevious: "Anterior"
		    		},
		    		buttons: {
		    			pageLength: {
		    				_: "Ver %d Registros",
		    				'-1': "Todos"
		    			},
		    			colvis: "Columnas",
		    			copy: "Copiar",
		    			print: "Imprimir"
		    		}
		    	}
		    });
	    }
    }


    function initializeCxcDetailsTable(){
        if ($.fn.DataTable.isDataTable('#cxcDetails-table')) {
            $('#cxcDetails-table').dataTable().fnClearTable();
			$('#cxcDetails-table').dataTable().fnDestroy();
        }

        if($('#cxcDetails-table').length){
            cxcTable = $('#cxcDetails-table').DataTable({
		    	deferRender: true, // Solo renderiza lo visible
		    	order: [[0, 'desc']],
		    	responsive: true,
		    	lengthChange: false,
		    	autoWidth: false,
		    	lengthMenu: [
		    		[10, 50, 100, 150, -1],
		    		[10, 50, 100, 150, 'Todos']
		    	],
		    	dom: 'Bfrtip',
		    	buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
		    	language: {
		    		sProcessing: "Procesando...",
		    		sLengthMenu: "Mostrar _MENU_ registros",
		    		sZeroRecords: "No se encontraron resultados",
		    		sEmptyTable: "Ningún dato disponible en esta tabla",
		    		sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		    		sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
		    		sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
		    		sSearch: "Buscar:",
		    		oPaginate: {
		    			sFirst: "Primero",
		    			sLast: "Último",
		    			sNext: "Siguiente",
		    			sPrevious: "Anterior"
		    		},
		    		buttons: {
		    			pageLength: {
		    				_: "Ver %d Registros",
		    				'-1': "Todos"
		    			},
		    			colvis: "Columnas",
		    			copy: "Copiar",
		    			print: "Imprimir"
		    		}
		    	}
		    });
	    }
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Captura el evento de entrada del usuario
        document.getElementById("searchCxc").addEventListener("input", function () {
            let searchValue = this.value.toLowerCase().trim(); // Convertir a minúsculas y quitar espacios
            let cards = document.querySelectorAll(".cxc-item"); // Seleccionar todas las tarjetas
        
            // Si el campo de búsqueda está vacío, mostramos todas las tarjetas
            if (searchValue === "") {
                cards.forEach(card => {
                    card.style.display = ""; // Muestra todas las tarjetas
                });
                return;
            }
        
            // Filtrar las tarjetas basándonos en el texto de búsqueda
            cards.forEach(card => {
                // Obtener los valores de la tarjeta para comparación
                let codclie = card.querySelector("label.badge").textContent.toLowerCase(); // Obtener código cliente
                let cliente = card.querySelector("p.clienteText").textContent.toLowerCase(); // Obtener saldo
            
                // Verificar si el valor de búsqueda está contenido en el código de cliente o saldo
                if (codclie.includes(searchValue) || cliente.includes(searchValue)) {
                    card.style.display = ""; // Mostrar tarjeta si coincide
                } else {
                    card.style.display = "none"; // Ocultar tarjeta si no coincide
                }
            });
        });
    });


    $(document).ready(function(){
        //$('#createCxcModal').modal('show');

        function consultaDeCxc(eliminado) {
            var codwallet = $('#cmbcodwallet').val();
            $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner

            $.ajax({
				data:{
                    "codwallet": codwallet
                },
				url: "{{ route('cxc.balance') }}",
				type: 'get',
				dataType: 'json',
				success: function (response) {
                    $("#loadingSpinner").css("display", "none"); // Mostrar el spinner

					$('#saldocxc').html('$ 0,00');
					$('#saldocxc').html('$ '+ response.saldo);
                    $('#saldosPorClienteContainer').html(response.saldosPorClienteHtml)
                    $('#cxcContainer').html(response.cxcHtml)
                    initializeCxcTable()
                    $("[data-toggle='tooltip']").tooltip();
                    if(eliminado){
                        Swal.fire(
                            "Eliminado",
                            "El registro ha sido eliminado correctamente.",
                            "success"
                        );
                    }
				},
				error: function(mensaje) {
                    $("#loadingSpinner").css("display", "none"); // Mostrar el spinner
                    console.log(mensaje);
				},
			});
        }

        $('#cmbcodwallet').on('input', function(){ 
            consultaDeCxc()
        })

        window.modalClienteCxc = function(codclie) {
            $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner
            $.ajax({
				data:{
                    "codclie": codclie
                },
				url: "{{ route('cxc.getDetailsByClient') }}",
				type: 'get',
				dataType: 'json',
				success: function (response) {
                    $("#loadingSpinner").css("display", "none");
                    $("#CxcDetailsModalView").modal('show')
                    $('.codclie').html(response?.saclie?.descrip)
                    $('#cxcDetailsTableContainer').html(response.cxcDetails);
                    initializeCxcDetailsTable()
				},
				error: function(err) {
                    console.log(err);
				},
			});
        }

        $("#formReg").submit(function(e){
			e.preventDefault();
			var codclie = $('#clienteSaint').val();
			var codwallet = $('#empresa').val();
			var codmoneda = 2;//$('#cmbmoneda').val();
			var codtipomoneda = 4;//$('#cmbtipomoneda').val();
			var fecha = $('#fechacxc').val();
			var monto = $('#montocxc').val();
			var observacion	= $('#descripcioncxc').val();
				
			$.ajax({
				url: "{{ route('registrarCxcWallet') }}",
				type: 'post',
				dataType: 'json',
				async: true,
                data: {
                    "_token": "{{ csrf_token() }}", // Agrega el token CSRF aquí
			    	"codclie": codclie,
			    	"codwallet": codwallet,
			    	"codmoneda": codmoneda,
			    	"codtipomoneda": codtipomoneda,
			    	"fecha": fecha,
			    	"monto" : monto,
			    	"observacion": observacion
			    },
				success: function (response) {
					if(response.success){
						consultaDeCxc();

			            $('#empresa').val('');
			            $('#fechacxc').val("{{ date('Y-m-d') }}");
			            $('#montocxc').val('');
			            $('#descripcioncxc').val('');
                        $('#createCxcModal').modal('hide');

                        Swal.fire({
    		    		  icon: 'success',
    		    		  text: 'Cuenta por Cobrar registrado con exito!',
    		    		  showConfirmButton: true
    		    		});

					}
				},
				error: function(mensaje) {
					console.log(mensaje);
				},
			});
							
		});

        $("#formRegAbono").submit(function( event ){
            event.preventDefault();

            $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner
				
			var id = $('#inputcxc').val();
			var montodeuda = $('#montodeuda_'+id).val();	
			var montoabono = $('#montoabono').val();
			var cmbcodtipomonedaabono = $('#cmbcodtipomonedaabono').val();
			var descripcionabono = $('#descripcionabono').val();
			var codwallet = $('#cmbcodwallet').val();
			var datos='';
			
			let deudaf = parseFloat(montodeuda).toFixed(2);

            var fileInput = $("#file")[0].files[0]; // Obtener archivo

            let reader = new FileReader();
            reader.readAsDataURL(fileInput);
            reader.onload = function () {
                let base64File = reader.result; // Mantener el prefijo Base64
                $.ajax({
			    	data: {
                        _token: '{{ csrf_token() }}',
			    	    "codwallet": codwallet,
			    	    "codcxc": id,
			    	    "montodeuda": deudaf,
			    	    "montoabono": montoabono,
			    	    "cmbcodtipomonedaabono": cmbcodtipomonedaabono,
			    	    "descripcionabono": descripcionabono,
                        "file": base64File // Se envía el archivo con prefijo
			        },
			    	url: "{{ url('registrarCxcAbono') }}",
			    	type: 'post',
			    	dataType: 'json',
			    	async:true,
			    	success: function (response) {
                        $('#AbonoModalView').modal('hide');
                        $("#loadingSpinner").css("display", "none"); // Mostrar el spinner
			    		console.log(response);
			    		if(response.success == false){
			    			Swal.fire({
    		    			  icon: 'error',
    		    			  title: 'El monto a pagar no puede ser mayor a la deuda.',
    		    			  showConfirmButton: true
    		    			});
                        
			    		}else{
			    			Swal.fire({
    		    			  icon: 'success',
    		    			  title: 'Pago registrado con exito !!!',
    		    			  showConfirmButton: true
    		    			});
			    			consultaDeCxc();
			    		}
                    
			    	},
			    	error: function(mensaje) {
			    		console.log(mensaje);
			    	},
			    });	
            }			
		});

        $('#AbonoModalView').on('shown.bs.modal', function () {
            $('#saldodeuda').html('');		
			$('#nrocxc').html('');
			$('#montoabono').val('');
			$('#descripcionabono').val('');
			$('#cmbcodtipomonedaabono').val('');

			$('#file').val('');
	    })

        window.setCxCCode = function(codcxc){
            $('.codcxc').html(codcxc);
            $('#inputcxc').val(codcxc);
        }

        window.getAbonosDetails = function(codcxc){
            $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner
            $.ajax({
				data:{
                    "codcxc": codcxc
                },
				url: "{{ route('cxc.getAbonosDetails') }}",
				type: 'get',
				dataType: 'json',
				success: function (response) {
                    $("#loadingSpinner").css("display", "none");
                    $("#AbonoDetailsModalView").modal('show')
                    $('#AbonoDetailsModalContainer').html(response.html);
				},
				error: function(err) {
                    console.log(err);
				},
			});
        }

        window.aceptarEliminar = function(codcxc) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Para confirmar, introduce tu clave de seguridad.",
                icon: "warning",
                input: "password",
                inputPlaceholder: "Introduce tu clave de seguridad",
                inputAttributes: {
                    autocomplete: "new-password", // Evita que el navegador lo llene automáticamente
                    autocorrect: "off",
                    spellcheck: false,
                    required: true
                },
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
                preConfirm: (password) => {
                    if (!password) {
                        Swal.showValidationMessage("La clave de seguridad es obligatoria");
                    }
                    return password;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    if(result.value !== 'dase995**--'){
                        return Swal.fire(
                            "Error",
                            "Contraseña inválida.",
                            "error"
                        );
                    }

                    $("#loadingSpinner").css("display", "flex"); // Mostrar el spinner

                    $.ajax({
                        url: "{{ url('cxc/eliminar') }}/" + codcxc,
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success) {
                                consultaDeCxc(true)
                            } else {
                                Swal.fire("Error", response.message || "No se pudo eliminar la cuenta por cobrar.", "error");
                            }
                        },
                        error: function(err) {
                            console.log(err);
                            Swal.fire("Error", "Ocurrió un problema en la eliminación.", "error");
                        }
                    });
                }
            });
        }

        window.updateColor = function(codcxc){
            $.ajax({
                url: "{{ url('cxc/updateColor') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    codcxc: codcxc,
                    color: $('#codcxc_' + codcxc).val(),
                },
                success: function(response) {
                    if (response.success) {
                        consultaDeCxc()
                    } else {
                        Swal.fire("Error", response.message || "No se pudo eliminar la cuenta por cobrar.", "error");
                    }
                },
                error: function(err) {
                    console.log(err);
                    Swal.fire("Error", "Ocurrió un problema en la eliminación.", "error");
                }
            });
        }

    })
</script>
@endsection
