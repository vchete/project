@extends('layouts.app')

@section('content')
<div class="card">
    @php
        $action = $data ? route('admin.usuarios.update', Crypt::encrypt($data->usuario_id)) : route('admin.usuarios.store');
    @endphp
    <div class="card-body">
        <form method="POST" action="{{ $action }}" class="form-online" id="frmCrud" enctype="multipart/form-data">
            @if ($data)
                @method('PUT')
            @endif
            @csrf				
                                                                    
            <div class="row">
                <div class="col-sm-6">
                    <label for="nombres" class="col-form-label">Nombres</label>
                    <div class="form-group">
                        <input type="text" name="nombres" class="form-control "  value="{{ $data ? $data->nombres : '' }}">
                        @if ($errors->has('nombres'))
							<span id="name-error" class="error text-danger">{{ $errors->first('nombres') }}</span>
						@endif
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <label for="apellidos" class="col-form-label">Apellidos</label>
                    <div class="form-group">
                        <input type="text" name="apellidos" class="form-control "  value="{{ $data ? $data->apellidos : '' }}">
                        @if ($errors->has('apellidos'))
							<span id="name-error" class="error text-danger">{{ $errors->first('apellidos') }}</span>
						@endif
                    </div>
                </div>
            </div>                                                            
            <div class="row">
                <div class="col-sm-6">
                    <label for="email" class="col-form-label">Email</label>
                    <div class="form-group">
                        <input type="text" name="email" class="form-control "  value="{{ $data ? $data->email : '' }}">
                        @if ($errors->has('email'))
							<span id="name-error" class="error text-danger">{{ $errors->first('email') }}</span>
						@endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <br>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="activo" {{ $data ? ($data->activo == 1 ? 'checked' : '') : '' }}>
                                Activo
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="">Roles</label>
                    <div class="card">
                        <div class="card-body" style="padding:10px!important;">
                            <div class="row">
                                @foreach ($roles as $r)
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <br>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="roles[{{ $r->rol_id }}]" {{ $data ? ($data->roles->where('rol_id', $r->rol_id)->first() ? 'checked' : '') : '' }}>
                                                {{ $r->nombre }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="password" class="col-form-label">Contraseña</label>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="password" name="newPassword" placeholder="Contraseña" class="form-control">
                                    @if ($errors->has('newPassword'))
                                        <span id="name-error" class="error text-danger">{{ $errors->first('newPassword') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="password" name="passwordconfirm" class="form-control" placeholder="Confirmar contraseña">
                                    @if ($errors->has('passwordconfirm'))
                                        <span id="name-error" class="error text-danger">{{ $errors->first('passwordconfirm') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <p class="help-block">* Dejar en blanco para no cambiar Contraseña</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	
            <div class="form-group">	
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary" onclick="callBlockUI()">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection