@extends('layouts.admin')
@section('metadata')
    <title>Editar Usuario - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Editar Usuario: {{ $user->name }}</h5>
                <span>Modificar Información y permisos de usuarios</span>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h5>Permisos</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 dt-responsive table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Módulo</th>
                                            <th>Permiso</th>
                                            <th>Registrar</th>
                                            <th>Ver Todo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($menus as $menu)
                                        <tr>
                                            <td>{{ $menu->codmenu }}</td>
                                            <td class="text-left">
                                                <i class="{{ $menu->logo_boostrap }}"></i> {{ $menu->nombre }}
                                            </td>
                                            <td>
                                                <!-- Checkbox principal (habilitar el menú) -->
                                                <input 
                                                    type="checkbox" 
                                                    class="checkbox-permiso" 
                                                    data-codmenu="{{ $menu->codmenu }}" 
                                                    data-codusuario="{{ $user->codusuario }}" 
                                                    data-type="menu" 
                                                    {{ in_array($menu->codmenu, $userMenuIds) ? 'checked' : '' }}
                                                >
                                            </td>
                                            <td>
                                                <!-- Checkbox para "registra" -->
                                                <input 
                                                    type="checkbox" 
                                                    class="checkbox-permiso registra{{ $menu->codmenu }}" 
                                                    data-codmenu="{{ $menu->codmenu }}" 
                                                    data-codusuario="{{ $user->codusuario }}" 
                                                    data-type="registra"
                                                    {{ in_array($menu->codmenu, $userRegistraIds) ? 'checked' : '' }}
                                                    {{ !in_array($menu->codmenu, $userMenuIds) ? 'disabled' : '' }}

                                                >
                                            </td>
                                            <td>
                                                <!-- Checkbox para "vertodo" -->
                                                <input 
                                                    type="checkbox" 
                                                    class="checkbox-permiso vertodo{{ $menu->codmenu }}" 
                                                    data-codmenu="{{ $menu->codmenu }}" 
                                                    data-codusuario="{{ $user->codusuario }}" 
                                                    data-type="vertodo"
                                                    {{ in_array($menu->codmenu, $userVerTodoIds) ? 'checked' : '' }}
                                                    {{ !in_array($menu->codmenu, $userMenuIds) ? 'disabled' : '' }}
                                                >

                                            </td>
                                        </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h5>Configuración</h5>
                            </div>
                            <div class="col-md-12 dt-responsive table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td class="text-left">
                                            <i class="fas fa-user-tie"></i> Consultor
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
                                    </tr>
                                    <tr>
                                        <td class="text-left">
                                            <i class="fas fa-user-check"></i> Activo
                                        </td>
                                        <td>
                                            <input 
                                                type="checkbox" 
                                                class="checkbox-config" 
                                                data-codusuario="{{ $user->codusuario }}" 
                                                data-field="inactivo"
                                                {{ $user->inactivo ? '' : 'checked' }}
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">
                                            <i class="fas fa-chalkboard-teacher"></i> Master
                                        </td>
                                        <td>
                                            <input 
                                                type="checkbox" 
                                                class="checkbox-config" 
                                                data-codusuario="{{ $user->codusuario }}" 
                                                data-field="master"
                                                {{ $user->master ? 'checked' : '' }}
                                            >
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <form action="{{ url('setRole') }}" class="row" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="userId">
                                    <div class="form-group col-md-6">
                                        <label class="fw-bold mb-2" for="role">Rol Dashboard</label>
                                        <div class="input-group">
                                            <select name="role" id="role" class="form-control">
                                                @foreach (['Directiva', 'Gerencia', 'Analista'] as $role)
                                                    <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>{{ $role }}</option>
                                                @endforeach
                                            </select>
                                                                                    
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check-double"></i> Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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
    $('.checkbox-permiso').on('change', function(){
        var codusuario = $(this).data('codusuario');
        var codmenu = $(this).data('codmenu');
        var type = $(this).data('type'); // Puede ser "menu", "registra" o "vertodo"
        let check = $(this).is(':checked') ? 1 : 0; // Determinar el nuevo valor

        if (type === "menu") {
            $('.registra' + codmenu + ', .vertodo' + codmenu)
                .prop('disabled', !check); // Si se deshabilita, también lo desmarca
        }

        $.ajax({
            method: 'POST',
            url: '{{ url("setMenu") }}',
            data: {
                _token: '{{ csrf_token() }}',
                codusuario: codusuario,
                codmenu: codmenu,
                type: type,
                check: check
            },
            success: function(res){
                console.log(res.message);
            },
            error: function(err){
                console.log(err);
            }      
        });
    });

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

});
</script>

@endsection