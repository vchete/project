@extends(config('hcode.crud.view.layoutApp'))

@section(config('hcode.crud.view.layoutSectionHead'))
@php
$includefechas = false;
$includeselect = false;
$includesummernote = false;

foreach ($fields as $f) {
    if (($f['type'] == 'date') || ($f['type'] == 'datetime' || $f['type'] == 'time')) {
        $includefechas = true;
    }

    if (($f['type'] == 'combobox') || ($f['type'] == 'enum') || $f['type'] == 'multi') {
        $includeselect = true;
    }

    if ($f['type'] == 'summernote') {
        $includesummernote = true;
    }
}
@endphp

@if($includefechas)
<link href="{{ asset('sources/datetimepicker/datetimepicker.css') }}" rel="stylesheet">
@endif

@if($includeselect)
<link href="{{ asset('sources/selectize/css/selectize.css') }}" rel="stylesheet">
{{-- <link href="{{ asset('sources/selectize/css/selectize.bootstrap.css') }}" rel="stylesheet"> --}}
@endif

@if($includesummernote)
<link href="{{ asset('sources/summernote/summernote.min.css') }}" rel="stylesheet">
@endif

@endsection

@section(config('hcode.crud.view.layoutSectionContent'))
	<div class="card">
		<div class="card-body">
			<form method="POST" action="{{ $route }}" class="form-online" id="frmCrud" enctype="multipart/form-data">
				@if($data)
					@method('PUT')
				@endif
				@csrf
				
				@php
					$tipoAllGrid = ['password','listbool','summernote'];
					$noShowLabel = ['bool'];
					$count       = 0;
				@endphp
				@foreach($fields as $f)
					@php
						$value    = old($f['fieldAs']) ? old($f['fieldAs']) : ($data ? $data->{$f['fieldAs']} : $f['default']);
						$label    = '<label for="' . $f['fieldAs'] . '" class="col-form-label">' . $f['name'] . '</label>';
						$allGrill = in_array($f['type'], $tipoAllGrid) || $f['allgrid'] ? true : false;
					@endphp
					
					@if ($allGrill)
						@if ($count == 1)
						</div>
						@php
						$count = -1;
						@endphp
						@endif
						<div class="row">
						<div class="col-sm-12">
						{!!$label!!}
					@elseif($count == 0)
						<div class="row">
					@endif
                    
                    @if (!$allGrill)
						<div class="col-sm-6">
						@if (!in_array($f['type'], $noShowLabel))
						{!!$label!!}
						@endif
						<div class="form-group">
					@else
						@if (in_array($f['type'], $noShowLabel))
						{!!$label!!}
						@endif
						<div class="form-group">
                    @endif
					<!---------------------------- PASSWORD ---------------------------------->
					@if($f['type'] == 'password')
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<input type="password" name="{{ $f['fieldAs'] }}" value="{{ old($f['fieldAs']) }}" placeholder="Password" class="form-control">
								</div>
								@if ($errors->has($f['fieldAs']))
								<span id="name-error" class="error text-danger">{{ $errors->first($f['fieldAs']) }}</span>
								@endif
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input type="password" name="{{ $f['fieldAs'] . 'confirm' }}"  value="{{ old($f['fieldAs'].'confirm') }}"class="form-control" placeholder="Repeat password">
								</div>
								@if ($errors->has($f['fieldAs']. 'confirm'))
								<span id="name-error" class="error text-danger">{{ $errors->first($f['fieldAs']. 'confirm') }}</span>
								@endif
							</div>
							<div class="col-sm-12">
								@if($data)
								<p class="help-block">* Dejar en blanco para no cambiar {!! $f['name'] !!}</p>
								@endif
							</div>
						</div>
					<!---------------------------- TEXTAREA ---------------------------------->
					@elseif($f['type'] == 'textarea')
						<textarea name="{{$f['fieldAs']}}" rows="3" class="form-control">{{ $value }}</textarea>
						@if ($errors->has($f['fieldAs']))
							<span id="name-error" class="error text-danger">{{ $errors->first($f['fieldAs']) }}</span>
						@endif
					<!---------------------------- SUMMERNOTE ---------------------------------->
					@elseif($f['type'] == 'summernote')
						<textarea name="{{$f['fieldAs']}}" class="summernote"></textarea>
					<!---------------------------- BOOLEAN ---------------------------------->
					@elseif($f['type'] == 'bool')
						<br>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="{{$f['fieldAs']}}" value="1" {{$value == 1 ? "checked" : ""}}>
								{!! $f['name'] !!}
							</label>
						</div>
					<!---------------------------- DATE,TIME ---------------------------------->
					@elseif($f['type'] == 'date' || $f['type'] == 'datetime' || $f['type'] == 'time')
						@php
						if($f['type'] == 'date') {
							$format = 'DD/MM/YYYY';
							$elValue = old($f['fieldAs']) ? old($f['fieldAs']) : ($value ? $value->format('d/m/Y') : '');
						}
						elseif ($f['type'] == 'datetime') {
							$format = 'DD/MM/YYYY HH:mm';
							$elValue = old($f['fieldAs']) ? old($f['fieldAs']) : ($value ? $value->format('d/m/Y H:i') : '');
						}
						else {
							$format = 'HH:mm';
							$elValue = old($f['fieldAs']) ? old($f['fieldAs']) : ($value ? $value->format('H:i') : '');
						}
						@endphp
						<div class="input-group dateTimePicker">
							<input type="text" name="{{ $f['fieldAs'] }}" value="{{ $elValue }}" data-date-format="{{ $format }}" class="form-control text-right dateTimePicker">
							<div class="input-group-append">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
						@if ($errors->has($f['fieldAs']))
							<span id="name-error" class="error text-danger">{{ $errors->first($f['fieldAs']) }}</span>
						@endif
					<!---------------------------- COMBOBOX/MULTI ---------------------------------->
					@elseif($f['type'] == 'combobox' || $f['type'] == 'multi')
						@php
							$multi = false;
						@endphp
						@if ($f['type'] == 'multi')
						@php
							$multi = true;
						@endphp
						@endif
						<select {{ $multi ? 'multiple="multiple" name='.$f['fieldAs'].'[]' : 'name='.$f['fieldAs'].'' }} class="selectize">
							@foreach($f['collect'] as $index => $opcion)
							<option value="{{ $index }}" {{ $index == $value ? 'selected' : '' }}>{!! $opcion !!}</option>
							@endforeach
						</select>
						@if ($errors->has($f['fieldAs']))
							<span id="name-error" class="error text-danger">{{ $errors->first($f['fieldAs']) }}</span>
						@endif
					<!---------------------------- FILE/IMAGE/SECUREFILE ---------------------------------->
					@elseif(($f['type'] == 'file') || ($f['type'] == 'image') || ($f['type'] == 'securefile'))
						<input type="file" name="{{ $f['fieldAs'] }}">
						@if($data)
							<p class="help-block">{!! $value !!}</p>
						@endif
					<!---------------------------- NUMERIC ---------------------------------->
					@elseif($f['type'] == 'numeric')
						<input type="number" step="any" name="{{ $f['fieldAs'] }}" value="{{ $value }}" class="form-control text-right {{ $errors->has($f['fieldAs']) ? 'is-invalid' : '' }}">
						@if ($errors->has($f['fieldAs']))
							<span id="name-error" class="error text-danger">{{ $errors->first($f['fieldAs']) }}</span>
						@endif
						<!---------------------------- DEFAULT ---------------------------------->
					@else
						<input type="text" name="{{ $f['fieldAs'] }}" class="form-control {{ $errors->has($f['fieldAs']) ? 'is-invalid' : '' }}"  value="{{ $value }}">
						@if ($errors->has($f['fieldAs']))
							<span id="name-error" class="error text-danger">{{ $errors->first($f['fieldAs']) }}</span>
						@endif
					@endif
					</div>

					@if ($allGrill)
					</div>
					</div>	
					@elseif ($count == 1)
					</div>
					</div>
					@php
						$count = -1;
					@endphp
					@else
					</div>
					@endif

					@php
						$count ++;
					@endphp
				@endforeach

				@if ($count == 1) 
				</div>
				@endif
					<div class="form-group">	
						<a href="{{ $routeIndex }}" class="btn btn-danger">Cancelar</a>
						<button type="submit" class="btn btn-primary" onclick="callBlockUI()">Guardar</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@stop

@section(config('hcode.crud.view.layoutSectionJs'))
	@if($includefechas)
	<script src="{{ asset('sources/datetimepicker/moment.js') }}"></script>
	<script src="{{ asset('sources/datetimepicker/datetimepicker.js') }}"></script>
	@endif

	@if($includeselect)
	<script src="{{ asset('sources/selectize/js/selectize.js') }}"></script>
	@endif

	@if($includesummernote)
	<script src="{{ asset('sources/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('sources/summernote/lang/summernote-es-ES.min.js') }}"></script>
	@endif

	<script type="text/javascript">
		$(function() {
			@if($includefechas)
				let confDate = confDateTimePicker;
				$('.dateTimePicker').datetimepicker(confDate);
			@endif
			@if($includeselect)
				$('.selectize').selectize();
			@endif
			@if($includesummernote)
				$('.summernote').summernote({
					'lang'   : 'es-ES',
				}); 
			@endif
		});
	</script>
@endsection
