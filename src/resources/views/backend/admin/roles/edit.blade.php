@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        @php
        $action = $data ? route('admin.roles.update', Crypt::encrypt($data->rol_id)) : route('admin.roles.store')
        @endphp
        <form method="POST" action="{{ $action }}" class="form-online" enctype="multipart/form-data">
            @if ($data)
            @method('PUT')
            @endif
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <label for="">Nombre</label>
                    <div class="form-group">
                        <input type="text" name="nombre" value="{{ $data ? $data->nombre : '' }}" class="form-control">
                        @if ($errors->has('nombre'))
						    <span id="name-error" class="error text-danger">{{ $errors->first('nombre') }}</span>
						@endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="">Descripción</label>
                    <div class="form-group">
                        <input type="text" name="descripcion" value="{{ $data ? $data->descripcion : '' }}" class="form-control">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <label for="">Permisos</label>
                    <hr style="margin-top:0px;">
                    <div class="row">
                        @foreach ($modulos as $m)
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="card" id="modulo-{{$m->modulo_id}}" style="padding:0px!important; margin-bottom:20px;">
                                <div class="card-header" style="background-color: rgba(0,0,0,.03); border-bottom: 1px solid rgba(0,0,0,.125); padding:10px;">
                                    {{ $m->nombre_friendly }}
                                </div>
                                <div class="card-body">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            @foreach ($m->moduloPermisos as $mp)
                                            @php
                                                $isChecked = $data ? $data->modulosPermisos->where('modulo_permiso_id', $mp->modulo_permiso_id)->first() : null;
                                            @endphp
                                            <div class="col-xs-6 col-sm-6">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="modulosPermisos[{{$m->modulo_id}}][{{$mp->modulo_permiso_id}}]" data-modulo="{{$m->modulo_id}}" {{ $isChecked ? 'checked' : '' }} class="in-modulo-{{$m->modulo_id}} form-check-input check-seccion">
                                                            {{ $mp->nombre_friendly }}
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
                        @endforeach
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-danger">Cancelar</a>
                    <button type="submit" class="btn btn-primary" onclick="callBlockUI()">Guardar</button>
                </div>
            </div> 
        </form>
    </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            @foreach ($modulos as $m)
                $('#modulo-{{ $m->modulo_id }}').removeClass('border-success').removeClass('border-danger').removeClass('border-warning')

                @php
                    $moduloPermisos    = $m->moduloPermisos->pluck('modulo_permiso_id');
                    $rolModuloPermisos = $data ? $data->modulosPermisos->whereIn('modulo_permiso_id', $moduloPermisos) : [];
                @endphp
                @if (count($rolModuloPermisos) == 0)
                    $('#modulo-{{ $m->modulo_id }}').addClass('border-danger')
                @elseif (count($rolModuloPermisos) == count($moduloPermisos))
                    $('#modulo-{{ $m->modulo_id }}').addClass('border-success')
                @elseif (count($rolModuloPermisos) > 0 &&  count($rolModuloPermisos) < count($moduloPermisos))
                    $('#modulo-{{ $m->modulo_id }}').addClass('border-warning')
                @endif
            @endforeach

            $('.check-seccion').click(function () {
                let moduloId = $(this).attr('data-modulo')
                $('#modulo-' + moduloId).removeClass('border-success').removeClass('border-danger').removeClass('border-warning')

                let inputs     = $('.in-modulo-' + moduloId)
                let inputCheck = inputs.filter(function () {
                    return this.checked
                })
                
                if (inputCheck.length == 0) {
                    $('#modulo-' + moduloId).addClass('border-danger')
                }
                else if (inputCheck.length == inputs.length) {
                    $('#modulo-' + moduloId).addClass('border-success')
                }
                else if (inputCheck.length > 0 && inputCheck.length < inputs.length) {
                    $('#modulo-' + moduloId).addClass('border-warning')
                }
            }) 
        })
    </script>
@endsection