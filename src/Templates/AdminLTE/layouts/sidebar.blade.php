<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('images/logo-sistema.png') }}" alt="..." class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if (Session::has('hcMenu'))
                    {!! Session::get('hcMenu') !!}
                @endif
                {{--  <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <li class="nav-item has-treeview menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./index.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index2.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index3.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v3</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">OTHERS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="nav-icon far fa-calendar-alt"></i>
                    <p>
                        Calendar
                        <span class="badge badge-info right">2</span>
                    </p>
                    </a>
                </li>  --}}
            </ul>
        </nav>
    </div>
  </aside>