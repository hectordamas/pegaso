@extends('layouts.admin')
@section('metadata')
<title>{{ ucwords(strtolower($saclie->descrip)) }} - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')
<style>
    .bg-c-success {
        background-color: #4caf50 !important; /* Verde */
    }

    .bg-c-secondary {
        background-color: #6c757d !important; /* Gris oscuro */
    }

    .bg-c-danger {
        background-color: #e74c3c !important; /* Rojo fuerte */
    }

    .bg-c-primary {
        background-color: #007bff !important; /* Azul primario */
    }

    .bg-c-info {
        background-color: #00bcd4 !important; /* Azul turquesa */
    }

    /* Fondo de iconos */

    .bg-simple-c-success {
        background-color: #4caf50 !important; /* Verde */
    }

    .bg-simple-c-secondary {
        background-color: #6c757d !important; /* Gris claro */
    }

    .bg-simple-c-danger {
        background-color: #e74c3c !important; /* Rojo claro */
    }

    .bg-simple-c-primary {
        background-color: #007bff !important; /* Azul claro */
    }

    .bg-simple-c-info {
        background-color: #00bcd4 !important; /* Azul turquesa */
    }

</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>👥📞 {{ ucwords($saclie->descrip) }}</h5>
                <a href="{{ url('clientes') }}" class="btn btn-dark shadow rounded"><i class="fas fa-mug-hot"></i> Volver a Clientes</a>

            </div>
            <div class="card-block">
                @php
                    $items = [
                        ['title' => 'Solicitudes', 'count' => $saclie->atencionclientes->count(), 'icon' => 'fas fa-headset', 'color' => 'pink', 'url' => "atencionclientes?client={$saclie->codclie}&from=2000-01-01&until=" . date('Y-m-d') . '&all=Y'],
                        ['title' => 'Eventos', 'count' => $saclie->calendario->count(), 'icon' => 'fas fa-calendar-alt', 'color' => 'green', 'url' => "atencionclientes?client={$saclie->codclie}"],
                        ['title' => 'CxC', 'count' => $saclie->cxc()->whereColumn('monto', '>', 'abono')->count(), 'icon' => 'fas fa-file-invoice-dollar', 'color' => 'blue', 'url' => "cuentas-por-cobrar?client={$saclie->codclie}"],
                        ['title' => 'Entrada Equipos', 'count' => $saclie->entradaEquipos->count(), 'icon' => 'fas fa-laptop-medical', 'color' => 'yellow', 'url' => "entradaequipos?client={$saclie->codclie}"],

                        // Nuevos colores
                        ['title' => 'Visitas', 'count' =>  $saclie->visitas->count(), 'icon' => 'fas fa-user-check', 'color' => 'success', 'url' => "visitas?client={$saclie->codclie}&from=2000-01-01&until=" . date('Y-m-d')],
                        ['title' => 'Presupuestos', 'count' =>  $saclie->safact()->where('tipofac', 'F')->where('codestatus', [1, 2, 4, 5, 6])->count(), 'icon' => 'fas fa-money-check-alt', 'color' => 'secondary', 'url' => "presupuestos?client={$saclie->codclie}"],
                        ['title' => 'Proyectos', 'count' => $saclie->safact()->where('tipofac', 'F')->where('codestatus', [3, 7, 8, 9, 10])->count(), 'icon' => 'fas fa-project-diagram', 'color' => 'danger', 'url' => "proyectos?client={$saclie->codclie}"],
                        ['title' => 'Entregas', 'count' => $saclie->safact()->where('tipofac', 'F')->where('codestatus', [11, 12, 13])->count(), 'icon' => 'fas fa-dolly', 'color' => 'primary', 'url' => "entregas-y-suministros?client={$saclie->codclie}"],
                    ];
                @endphp

                
                <div class="row">

                    @foreach($items as $item)
                    <div class="col-md-4 mb-3">
                        <div class="card user-widget-card bg-c-{{ $item['color'] }} status-card shadow">
                            <div class="card-block">
                                <i class="{{ $item['icon'] }} bg-simple-c-{{ $item['color'] }} card1-icon"></i>
                                <h4>{{ $item['count'] }}</h4>
                                <p>{{ $item['title'] }}</p>
                                <a href="{{ url($item['url']) }}" target="_blank" class="more-info fw-bold">Ver Más</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

