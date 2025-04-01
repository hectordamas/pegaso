<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    @yield('metadata')

    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('assets/adminty/assets/images/logo.png') }}" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- radial chart.css -->
    <link rel="stylesheet" href="{{ asset('assets/adminty/assets/pages/chart/radial/css/radial.css') }}" type="text/css" media="all">
    <!-- feather Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/assets/icon/feather/css/feather.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <!-- Style.css -->
    @yield('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/assets/pages/data-table/extensions/buttons/css/buttons.dataTables.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/adminty/bower_components/select2/dist/css/select2.min.css') }}" />

    <!-- Multi Select CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/bower_components/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/bower_components/multiselect/css/multi-select.css') }}" />

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/adminty/assets/css/jquery.mCustomScrollbar.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/customAssets/css/style.css') }}">

</head>
<!-- Menu sidebar static layout -->

<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="ball-scale">
            <div class="preloader3 loader-block">
                <div class="circ1"></div>
                <div class="circ2"></div>
                <div class="circ3"></div>
                <div class="circ4"></div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->

    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">

                    <div class="navbar-logo">
                        <a class="mobile-menu" id="mobile-collapse" href="#!">
                            <i class="feather icon-menu"></i>
                        </a>
                        <a href="{{ url('/') }}">
                            <img class="img-fluid" src="{{ asset('assets/adminty/assets/images/logo-pegaso.png') }}" alt="Theme-Logo" />
                        </a>
                        <a class="mobile-options">
                            <i class="feather icon-more-horizontal"></i>
                        </a>
                    </div>

                    <div class="navbar-container">
                        <ul class="nav-left">
                            <li class="header-search">
                                <div class="main-search morphsearch-search">
                                    <div class="input-group">
                                        <span class="input-group-prepend search-close">
										<i class="feather icon-x input-group-text"></i>
									</span>
                                        <input type="text" class="form-control" placeholder="Enter Keyword">
                                        <span class="input-group-append search-btn">
										<i class="feather icon-search input-group-text"></i>
									</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
                                <i class="full-screen feather icon-maximize"></i>
                            </a>
                            </li>
                        </ul>
                        <ul class="nav-right">


                            <li class="user-profile header-notification">
                                <div class="dropdown-primary dropdown">
                                    <div class="dropdown-toggle" data-bs-toggle="dropdown">
                                        <img src="{{ Auth::user()->photo ?? asset('assets/customAssets/img/user_default.jpg') }}" class="img-radius"
                                            alt="{{ Auth::user()->name }} Foto de Perfil">
                                        <span>{{ Auth::user()->name }}</span>
                                        <i class="feather icon-chevron-down"></i>
                                    </div>
                                    <ul class="show-notification profile-notification dropdown-menu"
                                        data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                        <li>
                                            <a href="{{ url('editar-perfil/' . Auth::id()) }}">
                                                <i class="feather icon-user"></i> Perfil
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}" 
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="feather icon-log-out"></i> Cerrar Sesión
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                        
                                    </ul>

                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>


            <!-- Sidebar inner chat end-->
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <nav class="pcoded-navbar">
                        <div class="pcoded-inner-navbar main-menu">
                            <div class="pcoded-navigatio-lavel">Menú Principal</div>
                            <ul class="pcoded-item pcoded-left-item">
                            <!-- Renderizar el menú normal -->
                            <li class="{{ Request::is('home*') ? 'active' : '' }}">
                                <a href="{{ url('home') }}">
                                    <span class="pcoded-micon">
                                        <i class="fas fa-home"></i>                                    
                                    </span>
                                    <span class="pcoded-mtext">Inicio</span>
                                </a>
                            </li>
                            @php
                                $submenuCXC = [];
                                $submenuConfig = [];
                            @endphp
                        
                            @foreach($globalMenus as $menu)
                                @if(in_array($menu->codmenu, [3, 14]))
                                    @php
                                        $submenuCXC[] = $menu;
                                    @endphp
                                @elseif(in_array($menu->codmenu, [9, 10, 11]))
                                    @php
                                        $submenuConfig[] = $menu;
                                    @endphp
                                @else
                                    <!-- Si ya acumulamos elementos de "Cuentas por Cobrar", renderizamos el dropdown -->
                                    @if(count($submenuCXC) > 0)
                                        <li class="pcoded-hasmenu {{ Request::is('cuentas-por-cobrar*') ? 'active' : '' }}">
                                            <a href="javascript:void(0)">
                                                <span class="pcoded-micon">
                                                    <i class="fas fa-comments-dollar"></i>
                                                </span>
                                                <span class="pcoded-mtext">Cuentas por Cobrar</span>
                                            </a>
                                            <ul class="pcoded-submenu">
                                                @foreach($submenuCXC as $submenu)
                                                    <li class="{{ Request::is($submenu->ruta . '*') ? 'active' : '' }}">
                                                        <a href="{{ url($submenu->ruta) }}">
                                                            <span class="pcoded-mtext">{{ $submenu->nombre }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        @php
                                            $submenuCXC = []; // Limpiar la lista después de renderizar
                                        @endphp
                                    @endif
                        
                                    <!-- Renderizar el menú normal -->
                                    <li class="{{ Request::is($menu->ruta . '*') ? 'active' : '' }}">
                                        <a href="{{ url($menu->ruta) }}">
                                            <span class="pcoded-micon">
                                                <i class="{{ $menu->logo_boostrap }}"></i>
                                            </span>
                                            <span class="pcoded-mtext">{{ $menu->nombre }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                            </ul>

                            @if(Auth::user()->master)
                            <div class="pcoded-navigatio-lavel">Administrador</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="pcoded-hasmenu">
                                    <a href="javascript:void(0)">
                                        <span class="pcoded-micon"><i class="feather icon-settings"></i></span>
                                        <span class="pcoded-mtext">Configuración</span>
                                    </a>
                                    <ul class="pcoded-submenu">
                                        @foreach($submenuConfig as $submenu)
                                            <li class="{{ Request::is($submenu->ruta . '*') ? 'active' : '' }}">
                                                <a href="{{ url($submenu->ruta) }}">
                                                    <span class="pcoded-mtext">{{ $submenu->nombre }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            @endif
                        </div>
                    </nav>
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/popper.js/dist/umd/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/modernizr/modernizr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/modernizr/feature-detects/css-scrollbars.js') }}"></script>
    <!-- Chart js -->
    <script type="text/javascript" src="{{ asset('assets/adminty/bower_components/chart.js/dist/Chart.js') }}"></script>

    <!-- gauge js -->
    <script src="{{ asset('assets/adminty/assets/pages/widget/gauge/gauge.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/widget/amchart/amcharts.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/widget/amchart/serial.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/widget/amchart/gauge.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/widget/amchart/pie.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/widget/amchart/light.js') }}"></script>
    <!-- Custom js -->
    <script src="{{ asset('assets/adminty/assets/js/pcoded.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/js/vartical-layout.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>


    <script src="{{ asset('assets/adminty/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/data-table/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/data-table/extensions/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/data-table/extensions/buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/data-table/extensions/buttons/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/data-table/extensions/buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/data-table/extensions/buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    
    <script src="{{ asset('assets/adminty/assets/pages/data-table/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/pages/data-table/extensions/buttons/js/extension-btns-custom.js') }}"></script>

    <script src="{{ asset('assets/adminty/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Multiselect js -->
    <script src="{{ asset('assets/adminty/bower_components/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('assets/adminty/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/adminty/assets/js/jquery.quicksearch.js') }}"></script>
    <!-- Custom js -->
    <script src="{{ asset('assets/adminty/assets/pages/advance-elements/select2-custom.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/adminty/assets/js/script.js') }}"></script>

    <!--Input mask -->
    <script src="{{ asset('assets/customAssets/js/inputmask/jquery.inputmask.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/customAssets/js/sweetalert2/sweetalert2.all.min.js') }}"></script>


    @yield('scripts')

    <script src="{{ asset('assets/customAssets/js/script.js') }}"></script>


    @if(session()->has('message'))
    <script>	
        Swal.fire({
            text: "{{ session('message') }}",
            icon: "success",
            confirmButtonText: "Continuar", 
            confirmButtonColor: '#28a745'
        });
    </script>
    @endif	

    @if(session()->has('error'))
    <script>	
        Swal.fire({
            text: "{{ session('error') }}",
            icon: "error",
            confirmButtonText: "Entendido!", 
            confirmButtonColor: '#dc3545'
        });
    </script>
    @endif	

    @foreach($errors->all() as $error)
    <script>	
        Swal.fire({
            text: "{{ $error }}",
            icon: "error",
            confirmButtonText: "Entendido!", 
            confirmButtonColor: '#dc3545'
        });
    </script>
    @endforeach


    <!-- Spinner de carga para Ajax -->
    <div id="loadingSpinner" style="
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999999999999;
        align-items: center;
        justify-content: center;">

        <div class="spinner-border text-light" role="status" style="width: 4rem; height: 4rem;">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>


</body>

</html>