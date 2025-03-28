@extends('layouts.auth')
@section('metadata')
<title>Restablece tu Contraseña - {{ env('APP_NAME') }}</title>
@endsection
@section('content')
<div class="login-area login-bg">
    <div class="container">
        <div class="login-box ptb--80 rounded">
            
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="login-form-head">
                    <img src="{{ asset('assets/strdash/images/pegasus.png') }}" width="100" alt="" class="mb-3" srcset="">

                    <p>Escribe tu correo Electrónico y tu nueva Contraseña.</p>

                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                </div>
                <div class="login-form-body">
                    <div class="form-gp">
                        <label for="email">Correo Electrónico</label>

                        <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <i class="ti-email"></i>

                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-gp">
                        <label for="password" class="@error('password') is-invalid @enderror" >Contraseña</label>

                        <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        <i class="ti-lock"></i>

                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-gp">
                        <label for="password-confirm">Confirmar Contraseña</label>

                        <input id="password-confirm" type="password" class="@error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                        <i class="ti-lock"></i>

                    </div>

                    <div class="submit-btn-area">
                        <button id="form_submit" type="submit">Restablecer Contraseña <i class="ti-arrow-right"></i></button>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>

@endsection
