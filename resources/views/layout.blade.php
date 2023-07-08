<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <title>{{ config('app.name') }} -  @yield('title')</title>
    
    <!-- Custom fonts for this template-->
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ui-lightness/jquery-ui-1.10.4.custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">


</head>

<body id="page-top">
    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">
        <!-- Topbar Search -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-6 my-auto p-2">
                    <img src="
                        @isset($configuration)
                            {{ asset('images/'.$configuration->logo) }}
                        @endisset" 
                        height="60" alt="Logo">
                </div>
                <div class="col-xs-6 pl-2">  
                    <h3 class="mb-0 text-gray-800">{{ ($configuration) ? $configuration->name : '' }}</h3>
                    <h6 class="text-primary font-italic mb-0">{{ ($configuration) ? $configuration->address : '' }}</h6>
                    <h6 class="text-black font-bold mb-0" style="font-size: 12px;">{{ config('app.name') }}</h6>
                </div>
            </div>
        </div>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="mr-2 d-none d-lg-inline text-gray-600 small">
                        @auth
                            {{ Auth::user()->{ $info['info'] }->name }}
                            <p class="mb-0  font-weight-bold text-success">{{ session('periodname') }}</p>
                        @endauth
                    </div>
                    <img class="img-profile rounded-circle"
                        src="{{ asset('sbadmin/img/undraw_profile.svg') }}">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown">
                    @auth
                        <a class="dropdown-item current_period" href="#" id="{{ session('current_period') }}">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            {{ session('periodname') }}
                        </a>
                    @endauth
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- End of Topbar -->
    <!-- Page Wrapper -->
    <div id="wrapper">
        
        <!-- Sidebar -->
        @include('partials.user_menu')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="pt-3">
                @yield('content')
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmation"></div>
    <div id="ui_content"></div>
    <div id="ui_content2"></div>
    <div id="ui_content3"></div>
    <div id="ui_content4"></div>
    <div id="modal_container"></div>
    <!-- Bootstrap core JavaScript-->
    {{-- <script src="{{ asset('js/jquery-3.5.1.js') }}"></script> --}}
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    {{-- <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script> --}}

    <!-- Page level plugins -->
    {{-- <script src="{{ asset('sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('sbadmin/js/demo/datatables-demo.js') }}"></script> --}}
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    @php
        if(Helpers::getLoad()){ 
    		if(is_array(Helpers::getLoad())){
    			foreach(Helpers::getLoad() as $links){
    				@endphp
                        <script src="{{ asset('js/'.$links) }}"></script>
                    @php
    			}
    		}
        }
    @endphp
       

</body>

</html>