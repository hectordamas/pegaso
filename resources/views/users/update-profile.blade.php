@extends('layouts.admin')
@section('metadata')
<title>Actualizar Perfil - {{ env('APP_NAME') }}</title>
@endsection
@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Editar Perfil</h5>
                <span>🙎‍♂️ 🙎‍♀️ Actualice su Información</span>
            </div>
            <form method="POST" action="{{ url('update-profile/' . Auth::id()) }}" class="card-block">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="documento" class="fw-bold mb-2">Documento</label>
                        <div class="input-group">
                            <span class="input-group-text text-light bg-inverse">
                                <i class="fas fa-id-card"></i>                            
                            </span>

                            <input type="text" name="documento"  class="form-control" value="{{ $user->documento }}" disabled>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="nombre" class="fw-bold mb-2">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text text-light bg-inverse">
                                <i class="fas fa-user"></i>                            
                            </span>

                            <input type="text" name="nombre" class="form-control" value="{{ $user->name }}" disabled>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="fw-bold mb-2">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text text-light bg-inverse">
                                <i class="fas fa-envelope"></i>                            
                            </span>

                            <input type="text" name="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="telefonocel" class="fw-bold mb-2">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text text-light bg-inverse">
                                <i class="fas fa-phone"></i>                            
                            </span>

                            <input type="text" name="telefonocel" class="form-control" value="{{ $user->telefonocel }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Actualizar Perfil
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Privacidad</h5>
                <span>🙎🔒 Actualice su Contraseña</span>
            </div>
            <form method="POST" action="{{ url('update-password/' . Auth::id()) }}" class="card-block">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label for="password" class="fw-bold mb-2">Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text text-light bg-inverse">
                                <i class="fas fa-unlock-alt"></i>
                            </span>

                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="confirm-password" class="fw-bold mb-2">Confirmar Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text text-light bg-inverse">
                                <i class="fas fa-user-lock"></i>                            
                            </span>

                            <input type="password" name="confirm-password" class="form-control" required>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-lock"></i> Actualizar Contraseña
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Foto de Perfil</h5>
                <span>🙎📷 Actualice su Foto de Perfil</span>
            </div>
            <form action="{{ url('') }}" class="card-block">
                @csrf
                <div class="row">
                    <div class="col-md-3 form-group">
                        <img src="{{ $user->photo ?? asset('assets/customAssets/img/user_default.jpg') }}" width="100" alt="">

                    </div>
                    <div class="col-md-9 form-group">

                        <div class="controller">
                            <a href="javascript:void(0)" onclick="javascript:camara();" type="button" id="camini" class="btn btn-primary"><i class="far fa-play-circle"></i></a>
                            <a href="javascript:void(0)" type="button" id="camstop" class="btn btn-danger"><i class="far fa-stop-circle"></i></a>
                            <button href="javascript:void(0)" type="button" id="snap" class="btn btn-info" disabled=""><i class="fas fa-camera"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-image"></i> Actualizar Imagen de Perfil
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection