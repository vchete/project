@extends('layouts.app')

@section('head')
    <link href="{{ asset('sources/datatable/css/datatable.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('sources/datatable/css/datatable.responsive.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        @if ($moduleAccess['create'])
        <div class="float-right">
            @php
                $module = str_replace('/', '.', Request::path());
            @endphp
            <a class="btn btn-sm btn-success" title="Crear" href="{{ route($module.'.create') }}">
                <span class="fa fa-plus"></span> Crear
            </a>
        </div>
        @endif
        <table id="datatable" class="table table-bordered display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    @foreach ($columns as $c)
                    <th class="{{ $c['type'] != 'numeric' ? 'text-center' : 'text-rigth' }}">{{ $c['name'] }}</th>
                    @endforeach
                    <th width="100"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-destroy">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de eliminar el registro?</p>

            </div>
            <div class="modal-footer" style="padding: 5px 15px 10px 15px;">
                <form  method="POST" id="frm-destroy" class="out-margin-b">
                    <input type="hidden" name="_method" value="DELETE">
                    @csrf
                    <div class="pull-right">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('#frm-destroy').removeAttr('action')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mdlButtonActions" tabindex="-1" role="dialog" aria-labelledby="mdlButtonActions" aria-hidden="true">
    <div class="modal-dialog modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Acciones extras</h5></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach ($buttonActions as $item)
                <a title="{{ $item['title'] }}" class="btn {{ isset($item['class']) ? $item['class'] : '' }}">
                    <i class="{{ isset($item['icon']) ? ($item['icon'].' fa-2x') : 'fas fa-question fa-2x' }}"></i>
                </a>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script src="{{ asset('sources/datatable/js/datatable.min.js') }}"></script>
    <script src="{{ asset('sources/datatable/js/datatable.bootstrap.min.js') }}"></script>
    <script src="{{ asset('sources/datatable/js/datatable.responsive.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
                language: {
                    sProcessing:     "Procesando...",
                    sLengthMenu:     "Mostrar _MENU_ registros",
                    sZeroRecords:    "No se encontraron resultados",
                    sEmptyTable:     "Ningún dato disponible en esta tabla",
                    sInfo:           "Mostrando registros del _START_ al _END_ de un total de _MAX_ registros",
                    sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered:   "",
                    sInfoPostFix:    "",
                    sSearch:         "Buscar:",
                    sUrl:            "",
                    sInfoThousands:  ",",
                    sLoadingRecords: "Cargando...",
                    oPaginate: {
                        sFirst:    "Primero",
                        sLast:     "Último",
                        sNext:     "Siguiente",
                        sPrevious: "Anterior"
                    },
                    oAria: {
                        sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
                        sSortDescending: ": Activar para ordenar la columna de manera descendente"
                    }
                },
                responsive: true,
                processing: true,
                serverSide: true,
                searching: true,
                columnDefs: [
                    { orderable: false, targets: parseInt("{{ $columns->count() }}") },
                    @foreach ($columns as $index => $c)
                    @php
                        $class     = 'text-left';
                        $orderable = true;
                        if ($c['class'] == '' && in_array($c['type'], ['date','datetime','time'])) {
                            $class = 'text-center';
                        }
                        elseif ($c['class'] == '' && $c['type'] == 'numeric') {
                            $class = 'text-right';
                        }
                        elseif ($c['class'] != '') {
                            $class = $c['class'];
                        }
                    @endphp
                    { orderable: '{{ $orderable }}', className: '{{ $class }}', targets: parseInt('{{ $index }}') },
                    @endforeach
                ],
                lengthMenu: JSON.parse("{{ json_encode(config('hcode.dataTable.lengthMenu')) }}"),
                ajax: {
                    url: "{{ route($route) }}",
                    type: "GET",
                    data: function (d) {
                        d.responseJson = true;
                    }
                },
                
            });

            $('#datatable_filter input').unbind();
            $('#datatable_filter input').bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    $('#datatable').DataTable().search($(this).val()).draw();
                }
            });
        });

        $(document).on('click', '.btnMoreActions', function () {
            $('#mdlButtonActions').modal('show');
        })
    </script>
@endsection