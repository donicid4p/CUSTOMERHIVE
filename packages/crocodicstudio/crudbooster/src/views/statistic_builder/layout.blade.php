<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ ($page_title)?Session::get('appname').': '.strip_tags($page_title):"Admin Area" }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name='generator' content='CustomerHive' />
    <meta name='robots' content='noindex,nofollow' />
    <link rel="shortcut icon"
        href="{{ CRUDBooster::getSetting('favicon')?asset(CRUDBooster::getSetting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png') }}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.2 -->
    <!--<link href="{{ asset('vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
        type="text/css" />-->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome Icons -->
    <link href="{{asset('vendor/crudbooster/assets/adminlte/font-awesome/css')}}/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 2.2.3 -->
    <script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>

    <!-- Bootstrap 3.4.1 JS -->
    <!--<script src="{{ asset ('vendor/crudbooster/assets/adminlte/bootstrap/js/bootstrap.min.js') }}"
        type="text/javascript"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset ('vendor/crudbooster/assets/adminlte/dist/js/app.js') }}" type="text/javascript"></script>


    <!-- Theme style -->
    <link href="{{ asset('vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('vendor/crudbooster/assets/adminlte/dist/css/skins/_all-skins.min.css')}}" rel="stylesheet"
        type="text/css" />

    <!--SWEET ALERT-->
    <script src="{{asset('vendor/crudbooster/assets/sweetalert/dist/sweetalert.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/sweetalert/dist/sweetalert.css')}}">

    @stack('head')

    <style>

        .skin-red .main-header .navbar .nav>li>a {
            text-decoration: none;
        }

    </style>
</head>

<body class="<?php echo (Session::get('theme_color')) ?: 'skin-blue'?> old-transition layout-top-nav fixed">
    <div id='app' class="wrapper">

        <header class="main-header">
            <nav class="navbar navbar-expand-lg navbar-light justify-content-between">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{url(config('crudbooster.ADMIN_PATH'))}}" title="{{Session::get('appname')}}"
                            class="navbar-brand">{{CRUDBooster::getSetting('appname')}}</a>
                        <button style="background-color: transparent;" type="button" class="navbar-toggle collapsed" data-bs-toggle="collapse"
                            data-bs-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a class='btn-save-statistic' href="#" title='Auto Save Status'><i
                                        class='fa fa-save'></i> Auto Save Ready</a></li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"><a href="#" class='btn-show-sidebar nav-link' data-bs-toggle="control-sidebar"><i
                                        class='fa fa-bars'></i> Add Widget</a></li>

                            <li  class="nav-item"><a class='nav-link' href="{{CRUDBooster::mainpath()}}"><i class='fa fa-sign-out'></i> Exit</a></li>
                        </ul>
                    </div>
                    <!-- /.navbar-custom-menu -->
                </div>
                <!-- /.container-fluid -->
            </nav>
        </header>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">


            <!-- Main content -->
            <section id='content_section' class="content">
                <!-- Your Page Content Here -->
                @yield('content')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <!-- The Right Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Content of the sidebar goes here -->
            <ul>
                <li class='connectedSortable' title='Drag To Main Area'>
                    <div id='btn-smallbox' class='button-widget-area'>
                        <a href="#" data-component='smallbox' class='btn-add-widget add-small-box'>
                            <img src='{{asset("vendor/crudbooster/assets/statistic_builder/smallbox.png")}}' />
                            <div class='title'>Small Box</div>
                        </a>
                    </div>
                </li>
                <li class='connectedSortable' title='Drag To Main Area'>
                    <div id='btn-table' class='button-widget-area'>
                        <a href="#" data-component='table' class='btn-add-widget add-table'>
                            <img src='{{asset("vendor/crudbooster/assets/statistic_builder/table.png")}}' />
                            <div class='title'>Table</div>
                        </a>
                    </div>
                </li>
                <li class='connectedSortable' title='Drag To Main Area'>
                    <div id='btn-chartarea' class='button-widget-area'>
                        <a href="#" data-component='chartarea' class='btn-add-widget add-chart-area'>
                            <img src='{{asset("vendor/crudbooster/assets/statistic_builder/chart_area.png")}}' />
                            <div class='title'>Chart Area</div>
                        </a>
                    </div>
                </li>
                <li class='connectedSortable' title='Drag To Main Area'>
                    <div id='btn-panelarea' class='button-widget-area'>
                        <a href="#" data-component='cardarea' class='btn-add-widget add-panel-area'>
                            <img src='{{asset("vendor/crudbooster/assets/statistic_builder/card.png")}}' />
                            <div class='title'>card Area</div>
                        </a>
                    </div>
                </li>

                <li class='connectedSortable' title='Drag To Main Area'>
                    <div id='btn-panelcustom' class='button-widget-area'>
                        <a href="#" data-component='cardcustom' class='btn-add-widget add-panel-custom'>
                            <img src='{{asset("vendor/crudbooster/assets/statistic_builder/card.png")}}' />
                            <div class='title'>card Custom</div>
                        </a>
                    </div>
                </li>

                <li class='connectedSortable' title='Drag To Main Area'>
                    <div id='btn-chartarea' class='button-widget-area'>
                        <a href="#" data-component='chartline' class='btn-add-widget add-chart-line'>
                            <img src='{{asset("vendor/crudbooster/assets/statistic_builder/chart_line.png")}}' />
                            <div class='title'>Chart Line</div>
                        </a>
                    </div>
                </li>

                <li class='connectedSortable' title='Drag To Main Area'>
                    <div id='btn-chartarea' class='button-widget-area'>
                        <a href="#" data-component='chartbar' class='btn-add-widget add-chart-bar'>
                            <img src='{{asset("vendor/crudbooster/assets/statistic_builder/chart_bar.png")}}' />
                            <div class='title'>Chart Bar</div>
                        </a>
                    </div>
                </li>
                <li class='connectedSortable' title='Drag To Main Area'>
                    <div id='btn-qlikwidget' class='button-widget-area'>
                        <a href="#" data-component='qlikwidget' class='btn-add-widget add-qlikwidget'>
                            <img style="width: 50%;" src='/images/qlik_logo.png' />
                            <div class='title'>Qlik Widget</div>
                        </a>
                    </div>
                </li>
            </ul>
        </aside>
        <!-- The sidebar's background -->
        <!-- This div must placed right after the sidebar for it to work-->
        <div class="control-sidebar-bg"></div>

        <!-- Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                Powered By {{Session::get('appname')}}
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy;
                <?php echo date('Y') ?>. All rights reserved.
            </strong>| 
        </footer>

    </div><!-- ./wrapper -->

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
    <!-- SlimScroll 1.3.0 -->
    <script src="{{ asset('vendor/crudbooster/assets/adminlte/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>

    @stack('bottom')
</body>

</html>