@extends('layouts.app-auth')

@section('content')
<div class="text-center">
    <i class="fas fa-user-circle fa-4x text-dark"></i>
</div>
<h5 class="text-center">Login</h5>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="username">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="userpassword">Contraseña</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customControlInline">Recordarme</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 mb-4">
                <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Ingresar</button>
            </div>
            
            <div class="text-md-right mt-3 mt-md-0">
                <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock"></i> ¿Reiniciar contraseña?</a>
            </div>
        </div>
    </div>
</form>
@endsection
