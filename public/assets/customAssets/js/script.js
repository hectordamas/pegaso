var tableApp;

function initializeDataTable() {
	if($('#atencion-clientes-table').length || $('#entrada-equipos-table').length || $('#comunicaciones-table').length || $('#visita-table').length || $('#cxc-report-table').length){
		tableApp = $('#atencion-clientes-table, #entrada-equipos-table, #comunicaciones-table, #visita-table, #cxc-report-table').DataTable({
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

$(document).ready(function(){
	//--------------------------Atencion al Cliente------------------------------------------------------------

    $("#direccionconex, #telefonoComunicaciones").inputmask(); // Aplica el formato IP automáticamente

    $(function () {
        $("[data-toggle='tooltip']").tooltip(); //Tooltip
    });

    initializeDataTable();
	
	$('.js-select2').select2({
		theme: 'classic', // Mantiene el estilo Classic
		width: '100%', // Ocupar todo el ancho disponible
	});

	
	//Modal Crear Soporte
	$('#SoporteModalCreate').on('shown.bs.modal', function () {
		$('#codclie').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#SoporteModalCreate')
		});

		$('#codconsultor').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#SoporteModalCreate')
		});
	})


	//Actualizar id al hacer click en modal para actualizar estatus Atencion al cliente
	window.btnUpdateStatus = function(atencionId){
		console.log(atencionId)
		$('#atencionClienteId').val(atencionId); // Asignar el valor al input oculto
	}

	window.btnViewDetails = function(button){
		let modal = document.getElementById("SoporteModalView");
		console.log(button)

		// Llenar el modal con los datos del botón
		modal.querySelector(".modal-body").innerHTML = `
			<p><strong>ID:</strong> ${button.getAttribute("data-id")}</p>
			<p><strong>Fecha:</strong> ${button.getAttribute("data-fecha")}</p>
			<p><strong>Cliente:</strong> ${button.getAttribute("data-descrip")}</p>
			<p><strong>Estatus:</strong> 
				<span class="badge" style="background: ${button.getAttribute("data-color")};">
					${button.getAttribute("data-estatus")}
				</span>
			</p>
			<p><strong>Solicitud:</strong> ${button.getAttribute("data-solicitud")}</p>
			<p><strong>Actividad:</strong> ${button.getAttribute("data-actividad")}</p>
			<p><strong>Consultor:</strong> ${button.getAttribute("data-consultor")}</p>
		`;
	}

	//------------------------------Calendario---------------------------------------------------
	$('#createEventModal').on('shown.bs.modal', function () {
		$('#codclie').select2({
			tags: true, //Crear clientes que aun no existen a modo de leads
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#createEventModal')
		});

		$('#codconsultor').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#createEventModal')
		});
	})

	//--------------------------------- Entrada equipos  ---------------------------------------
	//Modal Crear Soporte
	$('#EntradaModalCreate').on('shown.bs.modal', function () {
		$('#codclie').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#EntradaModalCreate')
		});

		$('#codconsultor').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#EntradaModalCreate')
		});
	})

	window.btnUpdateStatusEntrada = function(entradaId){
		console.log(entradaId)
		$('#entradaEquiposId').val(entradaId); // Asignar el valor al input oculto
		$('.entradaId').html(entradaId);
	}


	///-------------------------------Presupuestos----------------------------------------------

	//Actualizar id al hacer click en modal para actualizar estatus Presupuestos
	window.actualizarPresupuesto = function(presupuestoId){
		$('#presupuestoId').val(presupuestoId); // Asignar el valor al input oculto
		$('.presupuestoId').html(presupuestoId);
	}

	///-------------------------------Proyecto----------------------------------------------

	//Actualizar id al hacer click en modal para actualizar estatus Proyecto
	window.actualizarProyecto = function(proyectoId){
		$('#proyectoId').val(proyectoId); // Asignar el valor al input oculto
		$('.proyectoId').html(proyectoId);
	}


	//--------------------------------- Visitas  ---------------------------------------
	//Modal Crear Soporte
	$('#VisitaModalCreate').on('shown.bs.modal', function () {
		$('#codclie').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#VisitaModalCreate')
		});

		$('#codconsultor').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#VisitaModalCreate')
		});

		$('#acompanantes').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#VisitaModalCreate')
		});
	})

	//'''''''''''''''''''' Comunicaciones ---------------------------------------

	//Modal Crear comunicaciones
	$('#ComunicacionesModalCreate').on('shown.bs.modal', function () {
		$('#menciones').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%',
            dropdownParent: $('#ComunicacionesModalCreate')
        });
	})


	//--------------------------------- Cuentas por cobrar  ---------------------------------------
	//Modal Crear Soporte
	$('#createCxcModal').on('shown.bs.modal', function () {
		$('#clienteSaint').select2({
			theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#createCxcModal')
		});
	})

	///_______________________________Entregas_____________________________________________________
		//Actualizar id al hacer click en modal para actualizar estatus Proyecto
	window.actualizarEntrega = function(entregaId){
		$('#entregaId').val(entregaId); // Asignar el valor al input oculto
		$('.entregaId').html(entregaId);
	}
})

