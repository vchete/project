@extends('layouts.app-auth')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="form-horizontal">
        @csrf

        <div class="form-group">
            <label for="email" class="col-12 text-muted">Email</label>

            <div class="col-sm-12">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group ">
            <label for="password" class="text-muted col-12">Contraseña</label>

            <div class="col-md-12">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label text-muted" for="remember">Recordarme</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary btn-block">Iniciar</button>
                {{-- <br>
                <hr>
                @if (Route::has('password.request'))
                    <a class="btn btn-link text-right" href="{{ route('password.request') }}">Reiniciar Contraseña</a>
                @endif --}}
            </div>
        </div>
    </form>
</div>
@endsection
