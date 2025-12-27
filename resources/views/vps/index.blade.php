@extends('layouts.admin') {{-- ajusta a tu layout --}}
@section('metadata')
<title>Módulo VPS - {{ env('APP_NAME') }}</title>
@endsection
@section('content')

    <div class="row">

        {{-- Listado de clientes --}}
        @if ($clients->isEmpty())
            <p>No se encontraron clientes</p>
        @else
            <div class="col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Módulo VPS</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="dt-responsive table-responsive">
                            <table id="atencion-clientes-table" class="table table-striped table-bordered nowrap table-small">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Código</th>

                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                        <tr>
                                            <td>{{ $client->descrip }}</td>
                                            <td>{{ $client->codclie }}</td>

                                            <td>
                                                <a href="{{ route('vps.client', $client->codclie) }}" class="btn btn-dark btn-sm">
                                                   <i class="fas fa-server"></i> Servicio VPS
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
