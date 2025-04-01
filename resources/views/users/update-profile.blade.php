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

                            <input type="text" name="telefonocel" class="form-control" value="{{ $user->telefonocel }}" id="telefonocel" data-inputmask='"mask": "(9999) 999-9999"' data-mask required>
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
            <form action="{{ url('subir-foto') }}" method="POST" enctype="multipart/form-data" class="card-block">
                @csrf
                <div class="row">
                    <div class="col-md-12 form-group">
                        <img id="previewImage" src="{{ $user->photo ?? asset('assets/customAssets/img/user_default.jpg') }}" style="max-width: 100px;" alt="Foto de Perfil">
                    </div>
                    <div class="col-md-12 form-group">
                        <video id="video" width="250" height="250" autoplay style="display:none; object-fit: cover;"></video>
                        <canvas id="canvas" width="250" height="250" style="display:none; object-fit: cover;"></canvas>
    
                        <div class="controller mt-2">
                            <button type="button" id="camini" class="btn btn-primary"><i class="fas fa-video"></i> Activar Cámara</button>
                            <button type="button" id="snap" class="btn btn-info" disabled><i class="fas fa-camera"></i> Capturar</button>
                            <button type="button" id="camstop" class="btn btn-danger"><i class="fas fa-stop-circle"></i> Detener</button>
                        </div>
                    </div>
                </div>
    
    
                <!-- Campo oculto para almacenar la imagen capturada -->
                <input type="hidden" name="captured_photo" id="captured_photo">
    
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-success shadow w-100">
                        <i class="fas fa-file-image"></i> Actualizar Imagen de Perfil
                    </button>
                </div>
            </form>
        </div>
    </div>
    

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let video = document.getElementById("video");
        let canvas = document.getElementById("canvas");
        let snapButton = document.getElementById("snap");
        let camini = document.getElementById("camini");
        let camstop = document.getElementById("camstop");
        let profileInput = document.getElementById("profile");
        let previewImage = document.getElementById("previewImage");
        let capturedPhotoInput = document.getElementById("captured_photo");

        let stream = null;

        // Activar la cámara
        camini.addEventListener("click", async function () {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                video.style.display = "block";
                snapButton.disabled = false;
            } catch (error) {
                alert("Error al acceder a la cámara: " + error);
            }
        });

        // Capturar imagen
        snapButton.addEventListener("click", function () {
            canvas.getContext("2d").drawImage(video, 0, 0, canvas.width, canvas.height);
            let imageData = canvas.toDataURL("image/png"); // Convertir imagen a base64
            capturedPhotoInput.value = imageData; // Guardar en input oculto
            previewImage.src = imageData; // Mostrar imagen capturada
            video.style.display = "none"; // Ocultar video
            canvas.style.display = "block"; // Mostrar captura
            snapButton.disabled = true; // Deshabilitar botón de captura
        });

        // Detener la cámara
        camstop.addEventListener("click", function () {
            if (stream) {
                let tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }
            video.style.display = "none";
            snapButton.disabled = true;
        });

        // Mostrar imagen seleccionada desde el input file
        profileInput.addEventListener("change", function () {
            let file = profileInput.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });

</script>
@endsection