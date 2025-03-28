@extends('layouts.admin')
@section('metadata')
<title>Inicio - {{ env("APP_NAME")}}</title>
@endsection

@section('styles')
    <!-- Calender css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/bower_components/fullcalendar/dist/fullcalendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/bower_components/fullcalendar/dist/fullcalendar.print.css') }}" media='print'>
@endsection

@section('content')

    @if(Auth::user()->role == 'Directiva')
        @include('dashboard.directiva')
    @elseif(Auth::user()->role == 'Gerencia')
        @include('dashboard.gerencia')
    @else
        @include('dashboard.analista')
    @endif

@endsection

@section('scripts')
<!--<script type="text/javascript" src="{{ asset('assets/adminty/assets/pages/dashboard/crm-dashboard.min.js') }}"></script>-->
<script type="text/javascript" src="{{ asset("assets/adminty/bower_components/chart.js/dist/Chart.js") }}"></script>

<script type="text/javascript" src="{{ asset('assets/adminty/bower_components/moment/min/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/adminty/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/locale/es.js'></script>

<script>
    $(document).ready(function(){

        // Chart de cuentas por cobrar
        var cxcData = @json($saldosPorCliente);
        var cxcColors = @json($cxcColors);
        if($('#cxcChart').length){
            new Chart(document.getElementById("cxcChart"), {
                type: 'doughnut',
                data: {
                    labels: cxcData.map(item => item.cliente), // Nombres de clientes
                    datasets: [{
                        data: cxcData.map(item => item.saldo), // Saldos
                        backgroundColor: cxcColors,
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    tooltips: {
                      callbacks: {
                        label: function (tooltipItem, data) {
                            return (data['datasets'][0]['data'][tooltipItem['index']]).toLocaleString('en-US', {
                              style: 'currency',
                              currency: 'USD',
                            })
                        }
                      }
                    }
                }
            });
        }

        //Chart Proyectos
        if($('#proyectosEstatusChart').length){
            var ctx = document.getElementById('proyectosEstatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: @json($estatusProyectos),  // Etiquetas (Estatus)
                    datasets: [{
                        axis: 'y',
                        label: 'Cantidad',
                        data: @json($cantidadesPorProyectos),  // Cantidades de proyectos por cada estatus
                        backgroundColor: [
                          'rgba(255, 99, 132, 0.8)',
                          'rgba(255, 159, 64, 0.8)',
                          'rgba(255, 205, 86, 0.8)',
                          'rgba(75, 192, 192, 0.8)',
                          'rgba(54, 162, 235, 0.8)',
                          'rgba(153, 102, 255, 0.8)',
                          'rgba(201, 203, 207, 0.8)'
                        ],
                        borderColor: [
                          'rgb(255, 99, 132)',
                          'rgb(255, 159, 64)',
                          'rgb(255, 205, 86)',
                          'rgb(75, 192, 192)',
                          'rgb(54, 162, 235)',
                          'rgb(153, 102, 255)',
                          'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    legend: {
                        display: false
                    },
                    responsive: true,  // Hace que el gráfico se ajuste dinámicamente al contenedor
                    maintainAspectRatio: false,  // Permite que el gráfico cambie de tamaño sin mantener la proporción original
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }

            });
        }

        // Chart Atención Clientes
        var atencionClientesEstatus = @json($atencionClientesEstatus);
        var atencionClientesCantidad = @json($atencionClientesCantidad);
        if($("#AtencionChart").length){
            new Chart($("#AtencionChart"), {
                type: 'pie',
                data: {
                    labels: atencionClientesEstatus, // Estatus
                    datasets: [{
                        data: atencionClientesCantidad, // Cantidades
                        backgroundColor: [
                            "#f39c12",
                            "#2ecc71",
                            "#3498db", 
                        ],
                    }]
                },
                options: {
                    legend: {
                        position: "bottom",
                    }
                }
            });
        }

        //Calendario
        if($('#agendaSemanal').length){
            $('#agendaSemanal').fullCalendar({
	            header: {
	                left: 'prev,next today',
	                center: 'title',
	                right: 'listWeek'
	            },
                defaultView: 'listWeek',
                forceEventDuration: true,
                eventBackgroundColor: '#3498db',
                locale: 'es',
	            defaultDate: '{{ date("Y-m-d") }}',
	            navLinks: true, // can click day/week names to navigate views
	            events: @json($eventos),
                eventClick: function(event) {
                    // Llenar el modal con la información del evento
                    $('#eventId').text(event.id);
                    $('#eventIdInput').val(event.id)
                    $('.modalTitle').text(event.title ?? 'No Registrado');
                    $('#modalStart').text(event.start.format('YYYY-MM-DD HH:mm A'));
                    $('#modalClient').text(event.cliente ?? 'No Registrado');
                    $('#modalConsultor').text(event.consultor ?? 'No Registrado');
                    $('#modalDescription').text(event.description ?? 'No Registrado');
                    $('#modalEventType').text(event.eventType ?? 'No Registrado');
                    $('#modalInteractionType').text(event.interationType ?? 'No Registrado');
                    // Mostrar el modal
                    $('#showEventModal').modal('show');
                },
	        });
        }

        //Entrada equipos
        if($('#entradaEquipos').length){
            var ctx = document.getElementById('entradaEquipos').getContext('2d');

            new Chart(ctx, {
                type: 'horizontalBar', // Usa 'bar' y ajusta indexAxis en options
                data: {
                    labels: @json($entradaEquiposEstatus),  
                    datasets: [{
                        label: 'Cantidad',
                        data: @json($entradaEquiposCantidad),  
                        backgroundColor: 'rgba(155, 89, 182, 0.7)', // Color con transparencia
                        borderColor: 'rgba(155, 89, 182, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,  
                    maintainAspectRatio: false,  
                    scales: {
                        x: {
                            beginAtZero: false
                        },
                    },
                    legend: {
                        display: false
                    }
                }
            });
        }

    })
</script>
@endsection