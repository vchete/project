@extends(config('hcode.crud.view.layoutApp'))

@section(config('hcode.crud.view.layoutSectionPageTitle'))
    Inicio
@endsection

@section(config('hcode.crud.view.layoutSectionBreadcrumb'))
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item active"><i class="fa fa-star"></i> Inicio</li>
</ol>
@endsection

@section(config('hcode.crud.view.layoutSectionContent'))
<div class="card card-cyan card-outline">
    <div class="card-body">
        <div class="col-12">
            <h1 class="text-center">Inicio</h1>   
        </div>
    </div>
</div>
@endsection