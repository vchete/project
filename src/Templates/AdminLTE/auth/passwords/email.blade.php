@extends('layouts.app-auth')

@section('content')
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="form-group">
        <label for="email" class="col-12 text-muted">{{ __('E-Mail Address') }}</label>

        <div class="col-12">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-block">Enviar link</button>
        </div>
    </div>
</form>
@endsection
