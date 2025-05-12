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
@if(Auth::user()->role == 'Analista')
<div class="row">
    @foreach($menus as $menu)
    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-block text-center">
                <i class="{{ $menu->logo_boostrap }} text-c-lite-green d-block f-40"></i>
                <h4 class="m-t-20 text-c-light-green mb-3">{{ $menu->nombre }}</h4>
                <a href="{{ url($menu->ruta) }}" class="btn btn-primary btn-sm btn-round shadow">Ingresar</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@else
    <div class="row">
    <div class="col-md-6">
        <h6 class="fw-bold"><i class="far fa-smile-beam"></i> Bienvenido a {{ env('APP_NAME') }}, {{ auth()->user()->name }}!</h6>
        <p><i class="far fa-clock"></i> <span class="fw-bold">Fecha:</span> {{date('d-m-Y h:i A')}}</p>
    </div>
    <div class="col-xl-6 col-md-12 form-group text-right">
        <div class="btn-group">
            <a href="{{ url('home?type=anio') }}" type="button" class="btn btn-inverse btn-round {{ $type == 'anio' ? '' : 'btn-outline-success' }}">
                <i class="fas fa-check-circle"></i> Año
            </a>
            <a href="{{ url('home?type=mes') }}"  type="button" class="btn btn-inverse btn-round {{ $type == 'mes' ? '' : 'btn-outline-success' }}">
                <i class="fas fa-check-circle"></i> Mes
            </a>
            <a href="{{ url('home?type=dia') }}"  type="button" class="btn btn-inverse btn-round {{ $type == 'dia' ? '' : 'btn-outline-success' }}">
                <i class="fas fa-check-circle"></i> Día
            </a>
        </div>
    </div>
    </div>

    <div class="row">
    @if(Auth::user()->role == 'Directiva')
    <div class="col-xl-3">
        <div class="card bg-c-green text-white shadow">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5 fw-bold">Saldo Por Cobrar</p>
                        <h4 class="m-b-0">${{ number_format($saldoPorCobrar, 2, ',', '.') }}</h4>
                    </div>
                    <div class="col col-auto text-end">
                        <i class="fas fa-comments-dollar f-50 text-c-green" style="filter: brightness(0.9);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-xl-{{ Auth::user()->role == 'Directiva' ? '9' : '12' }}">
        @php
            $themesEntregas = [11 => 'bg-c-yellow', 12 => 'bg-c-pink', 13 => 'bg-c-blue'];
            $textEntregas = [11 => 'text-c-yellow', 12 => 'text-c-pink', 13 => 'text-c-blue'];
            $iconEntregas = [11 => 'fa-shopping-cart', 12 => 'fa-spinner', 13 => 'fa-box-open'];
        @endphp

        <div class="row">

            {{-- Entregados sobre comprados --}}
            <div class="col-xl-4 col-md-6">
                <div class="card {{ $themesEntregas[11] }} text-white shadow">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col">
                                <p class="m-b-5"><strong>Entregados</strong> <small>(Entregas)</small></p>
                                <h4 class="m-b-0">{{$entregasEntregado}} / {{$entregasComprado}}</h4>
                            </div>
                            <div class="col col-auto text-end">
                                <i class="fas {{ $iconEntregas[13] }} f-50 {{ $textEntregas[11] }}" style="filter: brightness(0.9);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- En Proceso --}}
            <div class="col-xl-4 col-md-6">
                <div class="card {{ $themesEntregas[12] }} text-white shadow">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col">
                                <p class="m-b-5"><strong>En Proceso</strong> <small>(Entregas)</small></p>
                                <h4 class="m-b-0">{{$entregasEnProceso}}</h4>
                            </div>
                            <div class="col col-auto text-end">
                                <i class="fas {{ $iconEntregas[12] }} f-50 {{ $textEntregas[12] }}" style="filter: brightness(0.9);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 col-md-6">
                <div class="card text-white shadow bg-c-blue">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col">
                                <p class="m-b-5">Clientes Atendidos</p>
                                <h4 class="m-b-0">{{ $atencionClientes->sum('cantidad') }}</h4>
                            </div>
                            <div class="col col-auto text-end">
                                <i class="fas fa-life-ring f-50 text-c-blue" style="filter: brightness(0.9);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    </div>


    <div class="row">
    @if(Auth::user()->role == 'Directiva')
    <div class="col-lg-3">
        <div class="card shadow" style="height: 95%">
            <div class="card-header">
                    <h5>💳 Saldo Por Cobrar (Clientes)</h5>
                    <span>Clientes con mayor saldo por cobrar</span>

            </div>
            <div class="card-block">
                <canvas id="cxcChart" width="400" height="360"></canvas>

                <div class="mt-4 text-center small">
                    @foreach($saldosPorCliente as $i => $cliente)
                    <span style="margin-right: 15px;" class="d-block mb-2">
                        <i class="fas fa-circle" style="color: {{ $cxcColors[$i] }};"></i>
                        {{ $cliente['cliente'] }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="col-lg-{{ Auth::user()->role == 'Directiva' ? '6' : '8' }}">
        <div class="card shadow" style="height: 95%">
            <div class="card-header">
                <h5>📌 Proyectos</h5>
                <span>Cantidad de Proyectos en proceso, ejecutados, completados y en Control de Calidad</span>
            </div>
            <div class="card-block">
                <canvas id="proyectosEstatusChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-{{ Auth::user()->role == 'Directiva' ? '3' : '4' }}">
        <div class="card shadow" style="height: 95%">
            <div class="card-header">
                <h5>🤝 Atención a Clientes</h5>
                <span>Cantidad de solicitudes atendidas por estatus</span>

            </div>
            <div class="card-block">
                <canvas id="AtencionChart" width="400" height="550"></canvas>
            </div>
        </div>
    </div>
    </div>


    <div class="row">

    <div class="col-lg-{{ Auth::user()->role == 'Analista' ? '8' : '6' }}">
        <div class="card shadow" style="height: 95%">
            <div class="card-header">
                <h5>📅 Programación de Eventos</h5>
                <span class="text-muted">Eventos y actividades de la semana</span>
            </div>
            <div class="card-block pb-3">
                <div id="agendaSemanal" style="width: 100%;"></div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role != 'Analista')
    <div class="col-lg-6">
        <div class="card shadow" style="height: 95%">
            <div class="card-header">
                <h5>💻 Entrada Equipos</h5>
                <span class="text-muted">Cantidad de equipos ingresados por estatus</span>
            </div>
            <div class="card-block pb-3">
                <canvas id="entradaEquipos" width="400" height="400"></div>
            </div>
        </div>
    </div>
    @endif


    </div>

    @if(Auth::user()->role == 'Gerencia' || Auth::user()->role == 'Directiva')
    <div class="row">
    
    <div class="col-lg-6">
        <div class="card shadow" style="height: 95%">
            <div class="card-header">
                <h5>💲 Ventas Por Ejecutivo</h5>
                <span>Ventas concretadas por cada uno de los Ejecutivos</span>
            </div>
            <div class="card-block">
                <canvas id="ventasPorVendedor" width="400" height="400"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow" style="height: 95%">
            <div class="card-header">
                <h5>❤️‍🩹 Clientes Atendidos Por Consultor</h5>
                <span>Solicitudes Atendidas por consultor</span>
            </div>
            <div class="card-block">
                <canvas id="solicitudesPorConsultor" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
    </div>
    @endif

    <!-- Mostrar Evento Modal -->
    <div class="modal fade" id="showEventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Detalles del Evento #<span id="eventId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Título del Evento:</strong> <br><span class="modalTitle"></span></p>
                    </div>

                    <div class="col-md-12">
                        <p><strong>Fecha del Evento:</strong> <br><span id="modalStart"></span></p>
                    </div>

                    <div class="col-md-12">
                        <p><strong>Cliente / Prospecto:</strong><br> <span id="modalClient"></span></p>
                    </div>

                    <div class="col-md-12">
                        <p><strong>Consultor:</strong> <br> <span id="modalConsultor"></span></p>
                    </div>

                    <div class="col-md-12">
                        <p><strong>Notas del Evento:</strong><br> <span id="modalDescription"></span></p>
                    </div>
                    
                    <div class="col-md-6">
                        <p><strong>Tipo de Evento:</strong> <br><span id="modalEventType"></span></p>
                    </div>

                    <div class="col-md-6">
                        <p><strong>Tipo de Interacción:</strong> <br><span id="modalInteractionType"></span></p>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
    </div>
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
                            console.log(data)
                            return 'Por Cobrar: ' + (data['datasets'][0]['data'][tooltipItem['index']]).toLocaleString('en-US', {
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
                    $('#modalInteractionType').text(event.interactionType ?? 'No Registrado');
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
        
        if($('#ventasPorVendedor').length){
            var ctx = document.getElementById('ventasPorVendedor').getContext('2d');
            console.log([
                @json($ventasVendedorLabels), 
                @json($ventasVendedorTotales),
                @json($cobrosVendedorTotales)
            ]);
            new Chart(ctx, {
                type: 'horizontalBar', // puedes usar horizontalBar en Chart.js v2, pero en v3+ es solo 'bar' con `indexAxis`
                data: {
                    labels: @json($ventasVendedorLabels),
                    datasets: [
                        {
                            label: 'Ventas',
                            data: @json($ventasVendedorTotales),
                            backgroundColor: 'rgba(26, 82, 118, 1)', // Azul oscuro más intenso
                            borderColor: 'rgba(26, 82, 118, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Cobrado',
                            data: @json($cobrosVendedorTotales),
                            backgroundColor: 'rgb(22, 198, 12)', // Verde claro estilo Apple más brillante
                            borderColor: 'rgb(22, 198, 12)',
                            borderWidth: 1
                        }
                    ]

                },
                options: {
                    indexAxis: 'y', // para hacer las barras horizontales
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                maxTicksLimit: 8,
                                callback: function(value, index, values) {
                                    return value.toLocaleString('en-US', {
                                        style: 'currency',
                                        currency: 'USD'
                                    });
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                          label: function (tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var value = dataset.data[tooltipItem.index];
                            return `${dataset.label}: ${value.toLocaleString('en-US', {
                              style: 'currency',
                              currency: 'USD',
                            })}`;
                          }
                        }
                    }
                }
            });
        }

        if($('#solicitudesPorConsultor').length){
            new Chart(document.getElementById('solicitudesPorConsultor').getContext('2d'), {
                type: 'horizontalBar',
                data: {
                    labels: @json($consultoresLabels),
                    datasets: [{
                        label: 'Solicitudes Atendidas',
                        data: @json($consultoresCantidades),
                        backgroundColor: 'rgba(153, 102, 255, 0.7)', // morado pastel
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes:[{
                            beginAtZero: false,
                            ticks: {
                                precision: 0
                            }
                        }] 
                    }
                }
            });
        }
    })
</script>


<script>
    function getHomeData(){

        $.ajax({

            url: "{{ route('getHomeData') }}"

        })

    }

    getHomeData();
</script>
@endsection