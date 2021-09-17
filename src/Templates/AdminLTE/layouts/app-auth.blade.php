<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSTOCK - Auth</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('sources/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('sources/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sources/css/adminlte.min.css') }}">

    @yield('head')
</head>
<body class="bg-dark img-pattern">
    <div class="account-pages my-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center pb-4">
                        <img src="{{ asset('images/logo-sistema.png') }}" height="50" alt="logo">
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="p-2">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('sources/js/jquery.min.js') }}"></script>
    <script src="{{ asset('sources/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('sources/js/adminlte.min.js') }}"></script>
    @yield('js')
</body>
</html>