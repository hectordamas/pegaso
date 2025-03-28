@extends('layouts.auth')
@section("metadata")
<title>Ingresar - {{ env("APP_NAME") }}</title>
@endsection
@section('content')
    <!-- login area start -->
    <div class="login-area login-bg">
        <div class="container">
            <div class="login-box ptb--80 rounded">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="login-form-head">
                        <img src="{{ asset('assets/strdash/images/pegasus.png') }}" width="100" alt="" class="mb-3" srcset="">

                        <p>¡Bienvenido! Inicia Sesión en Pegaso CRM</p>
                    </div>
                    <div class="login-form-body">
                        <div class="form-gp">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email"  name="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email">
                            <i class="ti-email"></i>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-gp">
                            <label for="password">Contraseña</label>
                            <input type="password" id="password" class="@error('password') is-invalid @enderror" name="password" required>
                            <i class="ti-lock"></i>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror                        
                        </div>
                        <div class="row mb-4 rmber-area">
                            <div class="col-5">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">Recordar Información</label>
                                </div>
                            </div>
                            <div class="col-7 text-right">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        Olvidé mi Contraseña
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="submit-btn-area">
                            <button id="form_submit" type="submit">Ingresar <i class="ti-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- login area end -->
@endsection
