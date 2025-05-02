@extends('layouts.admin')
@section('metadata')
<title>Directorio de Clientes - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h5>👥📞 Directorio de Clientes</h5>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle table-striped" id="clientes-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>RIF</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($saclie as $index => $c)
                                    <tr>
                                        <td>{{ $c->id }}</td>
                                        <td>{{ $c->descrip }}</td>
                                        <td>{{ $c->rif }}</td>
                                        <td>{{ $c->telef }}</td>
                                        <td>{{ $c->email }}</td>
                                        <td>
                                            @if($c->telef)
                                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $c->telef) }}" target="_blank" class="btn btn-sm btn-success" title="WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @endif
                                            @if($c->email)
                                                <a href="mailto:{{ $c->email }}" class="btn btn-sm btn-primary" title="Enviar correo">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                            @endif

                                            <a href="{{ url('clientes/' . $c->id) }}" class="btn btn-success shadow" data-toggle="tooltip" data-placement="top" title="Ver Detalles">
                                                <i class="far fa-address-book"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No hay clientes registrados.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection



