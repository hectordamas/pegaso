@extends('layouts.admin')
@section('metadata')
    <title>Registrar Usuario - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<form method="POST" action="{{ url('users/store') }}" enctype="multipart/formdata" class="row">
    @csrf

    <div class="col-md-6">
        <div class="card card-primary small">
            <div class="card-header">
                <div class="card-text">
                    <h5>Registro y Configuración de Usuarios</h5>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label">Tipo Doc.</label>
                            <div class="input-group">
                                <span class="input-group-text bg-inverse text-light">
                                    <i class="fas fa-tty"></i>
                                </span>
                                <select name="codtipodoc" class="form-control" id="codtipodoc" required="">
                                    <option value="" selected=""></option>
                                    <option value="1">V-</option>
                                    <option value="2">E-</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label">Número Documento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-inverse text-light">
                                    <i class="fas fa-address-card"></i>
                                </span>
                                <input minlength="6" maxlength="8" type="number" class="form-control" id="documento" name="documento" value="" onkeyup="this.value=this.value.toUpperCase();" required="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label">Nombres</label>
                            <div class="input-group">
                                <span class="input-group-text bg-inverse text-light"><i class="fas fa-user-alt"></i></span>
                                <input name="nombre" type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();" required="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label">Número Telefónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-inverse text-light"><i class="fas fa-phone"></i></span>
                                <input name="telefono" type="text" class="form-control" id="telefonocel" data-inputmask='"mask": "(9999) 999-9999"' data-mask inputmode="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-inverse text-light"><i class="fas fa-envelope"></i></span>
                                <input name="email" id="email" type="email" class="form-control" onkeyup="this.value=this.value.toLowerCase();" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="fw-bold mb-2" for="role">Rol Dashboard</label>
                        <div class="input-group">
                            <span class="input-group-text bg-inverse text-light"><i class="fas fa-user-tag"></i></span>
                            <select name="role" id="role" class="form-control">
                                @foreach (['Analista', 'Directiva', 'Gerencia'] as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <td class="text-left">
                                    <i class="fas fa-user-tie"></i> Consultor
                                </td>
                                <td>
                                    <input type="checkbox" name="consultor">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left">
                                    <i class="fas fa-user-check"></i> Master
                                </td>
                                <td>
                                    <input type="checkbox" name="master">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Permisos</h5>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-12">
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
                                        <input type="checkbox" name="codmenu[]" value="{{ $menu->codmenu }}">
                                    </td>
                                    <td>
                                        <input type="hidden" name="registra[{{ $menu->codmenu }}]" value="0"> 
                                        <input type="checkbox" name="registra[{{ $menu->codmenu }}]" value="1">
                                    </td>
                                    <td>
                                        <input type="hidden" name="vertodo[{{ $menu->codmenu }}]" value="0">
                                        <input type="checkbox" name="vertodo[{{ $menu->codmenu }}]" value="1">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 text-right">
                        <button id="btn-limpiar" type="reset" class="btn btn-secondary shadow"><i class="fas fa-trash-alt"></i> Limpiar</button>

                        <button id="btn-registrar" type="submit" class="btn btn-success shadow"><i class="fas fa-sign-in-alt"></i> Registrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection