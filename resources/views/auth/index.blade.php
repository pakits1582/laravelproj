<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
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
    </nav>
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-12 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h3 text-800 text-primary mb-4">Login Account</h1>
                                    </div>
                                    @if(Session::has('message'))
                                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                    @endif
                                    <form class="user" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>ID Number</label>
                                            <input type="text" class="form-control form-control-user" name="idno"
                                                id="idno" aria-describedby="idno"  placeholder="12345678" value="{{ old('idno') }}">
                                            @error('idno')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control form-control-user" name="password"
                                                id="password" placeholder="*********" value="{{ old('password') }}">
                                            @error('password')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <input type="submit" name="" id="" class="btn btn-primary btn-user btn-block" value="Login Account">
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="card shadow m-4 border-left-primary">
                                    <div class="card-header py-3">
                                        <h1 class="h3 text-800 text-primary mb-0">Online Application</h1>
                                    </div>
                                    <div class="card-body">
                                        <h1 class="h3 text-900 text-primary mb-4 text-center">{{ $configuration->applicationperiod->name }}</h1>

                                        @if ($configuration->status == 1)
                                            <h4 class="text-900 text-danger mb-4 text-center">Application is closed!</h4>
                                        @else
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ url('/applications/onlineapplication') }}" class="btn btn-success btn-icon-split" id="">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <span class="text">Click here to apply</span>
                                                </a>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script>

</body>

</html>