@extends('layouts.admin')
@section('metadata')
    <title>Lista de Usuarios - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5>Lista de Usuarios</h5>
                    <span>Gestión de Usuarios y Asignación de Permisos</span>
                </div>

                <a href="{{ url('users/create') }}" class="btn btn-success rounded shadow">
                    <i class="fas fa-user"></i> Registrar Usuario
                </a>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" id="users-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Documento</th>
                                    <th>Nombre</th>
                                    <th>E-Mail</th>
                                    <th>Teléfono</th>
                                    <th>Activo</th>
                                    <th>Consultor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->fecha)->format('d-m-Y') }}</td>
                                    <td>V-{{ $user->documento }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->telefonocel }}</td>
                                    <td>
                                        <input 
                                            type="checkbox" 
                                            class="checkbox-config" 
                                            data-codusuario="{{ $user->codusuario }}" 
                                            data-field="inactivo"
                                            {{ $user->inactivo ? '' : 'checked' }}
                                        >                                    
                                    </td>
                                    <td>
                                        <input 
                                            type="checkbox" 
                                            class="checkbox-config" 
                                            data-codusuario="{{ $user->codusuario }}" 
                                            data-field="consultor"
                                            {{ $user->consultor->inactivo ? '' : 'checked' }}
                                        >
                                    </td>
                                    <td>
                                        <a href='{{ url("users/{$user->id}/edit") }}' class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Modificar Usuario">
                                            <i class="far fa-edit"></i>                                        
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
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('.checkbox-config').on('change', function(){
            let codusuario = $(this).data('codusuario'); // ID del usuario
            let fieldName = $(this).data('field'); // Nombre del campo
            let check = $(this).is(':checked') ? 1 : 0; // Determinar el nuevo valor

            $.ajax({
                method: 'POST',
                url: '{{ url("setUserConfig") }}', // Ruta para actualizar la configuración
                data: {
                    _token: '{{ csrf_token() }}',
                    codusuario: codusuario,
                    field: fieldName, 
                    value: check
                },
                success: function(res){
                    console.log("Configuración actualizada:", res);
                },
                error: function(err){
                    console.error("Error al actualizar:", err);
                }
            });
        });
    })
</script>
@endsection