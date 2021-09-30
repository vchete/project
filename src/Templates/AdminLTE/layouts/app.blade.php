<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{ asset('sources/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('sources/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sources/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sources/overlayScrollbars/css/overlayScrollbars.min.css') }}">
    {{--  <link rel="stylesheet" href="{{ asset('css/app-styles.css') }}">  --}}

    @yield('head')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapped">
        @include('layouts.navbar')
        @include('layouts.sidebar')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">@yield('pageTitle')</h1>
                        </div>
                        <div class="col-sm-6">
                            @yield('breadcrumb')
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
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
    <script src="{{ asset('sources/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('sources/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('sources/overlayScrollbars/js/overlayScrollbars.min.js') }}"></script>
    @yield('js')
</body>
</html>