var tableApp;

function protegerExportacion(originalAction) {
	const claveExportacion = $('#exp').val();

	return function (e, dt, node, config) {
		const clave = prompt("Introduce la clave para exportar:");
		if (clave === claveExportacion) {
			originalAction.call(this, e, dt, node, config);
		} else {
			alert("Clave incorrecta. Exportación cancelada.");
		}
	};
}

function initializeDataTable() {
	if($('#atencion-clientes-table').length || $('#entrada-equipos-table').length || $('#comunicaciones-table').length || $('#visita-table').length || $('#cxc-report-table').length || $('#users-table').length || $('#cuadre-table').length || $('#clientes-table').length || $('#alicencias-table').length){
		tableApp = $('#atencion-clientes-table, #entrada-equipos-table, #comunicaciones-table, #visita-table, #cxc-report-table, #users-table, #cuadre-table, #clientes-table, #alicencias-table').DataTable({
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
			buttons: [
				{
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
			}
		});
	}

		if($('#licencias-table').length){
		tableApp = $('#licencias-table').DataTable({
			deferRender: true, // Solo renderiza lo visible
			order: [[0, 'desc']],
			responsive: true,
			lengthChange: false,
			autoWidth: false,
			lengthMenu: [
				[50, 100, 150, -1],
				[50, 100, 150, 'Todos']
			],
			dom: 'Bfrtip',
			buttons: [
				{
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
			},
			columnDefs: [
        	    {
        	        targets: [8, 9], // Índices de columnas 'Pagada' y 'Activada'
        	        orderable: true,
        	        render: function (data, type, row, meta) {
        	            if (type === 'sort') {
        	                // Extraer el valor del checkbox
        	                let el = $('<div>').html(data).find('input');
        	                return el.prop('checked') ? 1 : 0;
        	            }
        	            return data;
        	        }
        	    }
        	]
		});
	}
}

$(document).ready(function(){

	$('label, .modal-title').addClass('fw-bold');
	$('label').addClass('mb-1');

	//--------------------------Atencion al Cliente------------------------------------------------------------

    $("#direccionconex, #telefonoComunicaciones, #telefonocel").inputmask(); // Aplica el formato IP automáticamente

    $(function () {
        $("[data-toggle='tooltip']").tooltip(); //Tooltip
    });

    initializeDataTable();
	
	$('.js-select2').select2({
		//theme: 'classic', // Mantiene el estilo Classic
		width: '100%', // Ocupar todo el ancho disponible
	});

	
	//Modal Crear Soporte
	$('#SoporteModalCreate').on('shown.bs.modal', function () {
		$('#codclie').select2({
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#SoporteModalCreate')
		});

		$('#codconsultor').select2({
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#SoporteModalCreate')
		});
	})

	//Modal Actualizacion de licencias
	$('#ActualizacionModalCreate').on('shown.bs.modal', function () {
		$('#codclie').select2({
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#ActualizacionModalCreate')
		});
	})
	

	//Modal Actualizacion de licencias
	$('#TicketModalCreate').on('shown.bs.modal', function () {
		$('#codclie').select2({
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#TicketModalCreate')
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
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#createEventModal')
		});

		$('#codconsultor').select2({
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#createEventModal')
		});
	})

	//--------------------------------- Entrada equipos  ---------------------------------------
	//Modal Crear Soporte
	$('#EntradaModalCreate').on('shown.bs.modal', function () {
		$('#codclie').select2({
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#EntradaModalCreate')
		});

		$('#codconsultor').select2({
			//theme: 'classic', // Mantiene el estilo Classic
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
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#VisitaModalCreate')
		});

		$('#codconsultor').select2({
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#VisitaModalCreate')
		});

		$('#acompanantes').select2({
			//theme: 'classic', // Mantiene el estilo Classic
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

		$('#codclie').select2({
			//theme: 'classic', // Mantiene el estilo Classic
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#ComunicacionesModalCreate')
		});
	})


	//--------------------------------- Cuentas por cobrar  ---------------------------------------
	//Modal Crear Soporte
	$('#createCxcModal').on('shown.bs.modal', function () {
		$('#clienteSaint').select2({
			//theme: 'classic', // Mantiene el estilo Classic
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


	//Modal Licencias
	$('#LicenciasModalCreate').on('shown.bs.modal', function () {
		$('#codclie').select2({
			width: '100%', // Ocupar todo el ancho disponible
			dropdownParent: $('#LicenciasModalCreate')
		});
	})
	
})

