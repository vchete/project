@extends('layouts.app-auth')

@section('content')
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<div class="text-center">
    <i class="fas fa-user-circle fa-4x text-dark"></i>
</div>
<h5 class="text-center">Reiniciar contraseña</h5>
<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="form-group">
        <label for="username">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mt-4 mb-4">
        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Enviar link</button>
    </div>
</form>
@endsection
