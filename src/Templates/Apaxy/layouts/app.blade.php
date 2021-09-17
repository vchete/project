<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSTOCK</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('sources/slick-slider/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('sources/slick-slider/slick/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('sources/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sources/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sources/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sources/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-styles.css') }}">

    @yield('head')
</head>
<body data-sidebar="dark">
    <div class="modal-backdrop-custom" id="modal-backdrop-custom" style="display: none;"></div>
    <div class="loading-backdrop-custom" id="loading-backdrop-custom" style="display: none;"></div>
    <div id="loading-spinner" class="loading-spinner">
        <div class="loading-spinner-message">
            <div class="fa-4x">
                <i class="fas fa-spinner fa-pulse"></i>
            </div>
            <b id="message" style="font-size: 18px; font-weight: 500;"></b>
        </div>
    </div>
    <div id="layout-wrapper">
        <div id="app">
            @include('layouts.navbar')
            @include('layouts.sidebar')
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18">@yield('page-title')</h4>
                                    <div class="page-title-right">
                                        @yield('page-breadcrumb')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="container-fluid">
                                @if (isset($tituloPagina))
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-title-box d-flex align-items-center justify-content-between">
                                            <h3 class="mb-0 font-size-20">{!! $tituloPagina !!}</h3>
                                            <div class="page-title-right">
                                                @yield('breadCrumb')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('sources/js/jquery.min.js') }}"></script>
    @if(isset($dataVue))
    <script>
        var dataVue = {!! json_encode($dataVue, JSON_PRETTY_PRINT) !!};
        window.ceroCrypt = '{!! encrypt(0) !!}';
    </script>
    <script src="{{mix('js/app.js')}}"></script>
    @endif
    <script src="{{ asset('sources/js/popper.min.js') }}"></script>
    <script src="{{ asset('sources/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sources/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('sources/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('sources/js/waves.min.js') }}"></script>
    <script src="{{ asset('sources/js/theme.js') }}"></script>
    <script src="{{ asset('sources/slick-slider/slick/slick.min.js') }}"></script>
    @yield('js')
    
</body>
</html>