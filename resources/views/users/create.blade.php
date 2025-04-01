@extends('layouts.admin')
@section('metadata')
    <title>Registrar Usuario - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 col-lg-12">
        <div class="card card-primary small">
            <div class="card-header">
                <div class="card-text">
                    <h4 class="card-title">
                        <span style="font-weight:bold;">Registro y Configuración de Usuarios de DS &amp; DS Sistemas 3000, C.A.</span>
                    </h4>
                </div>
            </div>
            <div class="card-body">
                <div class="panel-body">
                    <form id="FormAddUsuario" method="POST" action="https://ds.saintnetweb.info/crearUsuario" enctype="multipart/formdata" novalidate="novalidate">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label">Tipo Doc.</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-tty"></i></span>
                                        </div>
                                        <select name="codtipodoc" class="form-control " id="codtipodoc" onchange="guardarSiglas()" required="">
                                            <option value="" selected=""></option>
                                             
                                                <option value="1">V-</option>
                                             
                                                <option value="2">E-</option>
                                                                                                    </select>
                                        <input type="hidden" id="siglas" name="siglas">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Número Documento</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                                        </div>
                                        <input style="text-align:left;font-weight:bold;" minlength="6" maxlength="8" type="number" class="form-control" id="documento" name="documento" value="" onkeyup="this.value=this.value.toUpperCase();" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Nombres</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-alt"></i></span>
                                        </div>
                                        <input name="nombre" type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Número Telefónico</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input style="text-align:center;font-weight:bold;" name="telefono" id="telefono" type="text" class="form-control" data-inputmask="&quot;mask&quot;: &quot;(9999) 999-9999&quot;" data-mask="" required="" inputmode="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input name="email" id="email" type="email" class="form-control" onkeyup="this.value=this.value.toLowerCase();" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div align="center" style="margin-top:1.5em;">
                                    <button id="btn-registrar" type="submit" class="btn btn-success btn-md float-center"><i class="fas fa-sign-in-alt"></i> Registrar</button>
                                    <button id="btn-limpiar" type="reset" class="btn btn-warning btn-md float-center"><i class="fas fa-trash-alt"></i> Limpiar</button>
                                </div>
                            </div>

                        </div>
                        <input name="base64photo" id="base64photo" type="hidden" value="">
                        <input type="hidden" name="codusuario" value="1">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection