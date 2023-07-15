<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Application</title>

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
    <div class="my-auto mx-auto" style="width: 95% !important;">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-header py-3">
                        <h1 class="h3 text-800 text-primary mb-0">Online Application for {{ $configuration->applicationperiod->name ?? '' }}</h1>
                    </div>
                    <div class="card-body p-0">
                        <form method="POST" id="form_application" action="">
                            @csrf
                            <div class="row m-0">
                                <div class="col-lg-6 pr-0">
                                    <div class="card m-3 border-left-info">
                                        <div class="card-body">
                                            <h4 class="mb-2 font-weight-bold text-primary">Instructions</h4>
                                            <p class="pl-3 font-italic font-weight-bold text-info">Fill out this form carefully and type all information requested. Write N/A if the information is not applicable to you. Omissions can delay the processing of your application.</p>
                                            <p class="pl-3 font-italic font-weight-bold text-info">INCOMPLETE APPLICATION FORMS WILL NOT BE PROCESSED.</p>
                                            <p class="pl-3 font-italic font-weight-bold text-info">(*) Denotes required fields, you may opt to skip filling up fields without an asterisk.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row pt-3">
                                        <div class="col-md-9 col-sm-12">
                                            <div class="form-group">
                                                <label for="period_id" class="m-0 font-weight-bold text-primary">* ID Number</label>
                                                <input type="text" name="keyword" placeholder="" class="form-control" id="keyword">
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none d-lg-block"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9 col-sm-12">
                                            <div class="form-group">
                                                <label for="period_id" class="m-0 font-weight-bold text-primary">* Classification</label>
                                                <select name="period_id" class="form-control filter_item" id="period_id">
                                                   
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-none d-lg-block"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="period_id" class="m-0 font-weight-bold text-primary">* Academic Program</label>
                                                <select name="period_id" class="form-control filter_item" id="period_id">
                                                   
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row m-0">
                                <div class="col-lg-6">
                                    @include('application.partials.personal_information')
                                </div>
                                <div class="col-lg-6">
                                    @include('application.partials.contact_information')
                                </div>
                            </div>
                        </form>
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