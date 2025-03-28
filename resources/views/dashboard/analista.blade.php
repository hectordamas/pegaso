<div class="row">
    @php
        $themesEntregas = [11 => 'bg-c-yellow', 12 => 'bg-c-pink', 13 => 'bg-c-blue'];
        $textEntregas = [11 => 'text-c-yellow', 12 => 'text-c-pink', 13 => 'text-c-blue'];
        $iconEntregas = [11 => 'fa-shopping-cart', 12 => 'fa-spinner', 13 => 'fa-box-open'];
    @endphp
    @foreach($entregas as $index => $en)
    <div class="col-xl-4 col-md-6">
        <div class="card {{ $themesEntregas[$en->codestatus] }} text-white shadow-sm">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5"><strong>{{ucwords(strtolower($en->estatusPre->nombre))}}</strong> <small>(Entregas)</small></p>
                        <h4 class="m-b-0">{{$en->cantidad}}</h4>
                    </div>
                    <div class="col col-auto text-end">
                        <i class="fas {{ $iconEntregas[$en->codestatus] }} f-50 {{ $textEntregas[$en->codestatus] }}" style="filter: brightness(0.9);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>📌 Proyectos</h5>
                <span>Cantidad de Proyectos en proceso, ejecutados, completados y en Control de Calidad</span>
            </div>
            <div class="card-block">
                <canvas id="proyectosEstatusChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>🤝 Atención a Clientes</h5>
                <span>Cantidad de solicitudes atendidas por estatus</span>

            </div>
            <div class="card-block">
                <canvas id="AtencionChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>📅 Programación de Eventos</h5>
                <span class="text-muted">Eventos y actividades de la semana</span>
            </div>
            <div class="card-block pb-3">
                <div id="agendaSemanal"></div>
            </div>
        </div>
    </div>


</div>

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