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
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ui-lightness/jquery-ui-1.10.4.custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
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
                        <h4 class="m-0 font-weight-bold text-primary">Online Application for {{ $configuration->applicationperiod->name ?? '' }}</h4>

                    </div>
                    <div class="card-body p-0">
                        <form method="POST" id="form_application" action="">
                            @csrf
                            <div class="row mx-3">
                                <div class="col-lg-6 my-3">
                                    <div class="card border-left-info h-100">
                                        <div class="card-body p-2">
                                            <h4 class="mb-2 font-weight-bold text-primary">Instructions</h4>
                                            <p class="pl-3 font-italic font-weight-bold text-info">Fill out this form carefully and type all information requested. Write N/A if the information is not applicable to you. Omissions can delay the processing of your application.</p>
                                            <p class="pl-3 font-italic font-weight-bold text-info">INCOMPLETE APPLICATION FORMS WILL NOT BE PROCESSED.</p>
                                            <p class="pl-3 font-italic font-weight-bold text-info">(*) Denotes required fields, you may opt to skip filling up fields without an asterisk.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 my-3">
                                    <div class="card border-left-info h-100">
                                        <div class="card-body p-2 align-items-end">
                                            <p class="mb-0 font-italic font-weight-bold text-info">Please submit your old ID number if you are a graduate or returnee from this institution and you are applying to a new program.</p>
                                            <div class="row pt-3">
                                                <div class="col-md-9 col-sm-12">
                                                    <label for="idno" class="m-0 font-weight-bold text-primary">ID Number</label>
                                                    <input type="text" name="idno" placeholder="" class="form-control" id="idno">
                                                </div>
                                                <div class="col-md-3 d-none d-lg-block"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9 col-sm-12">
                                                    <label for="classification" class="m-0 font-weight-bold text-primary">* Classification</label>
                                                    <select name="classification" class="form-control text-uppercase" id="classification">
                                                        <option value="">- select classification -</option>
                                                        <option value="1">NEW STUDENT</option>
                                                        <option value="2">TRANSFEREE</option>
                                                        <option value="3">READMIT</option>
                                                        <option value="4">GRADUATED (New Program)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 d-none d-lg-block"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="program_id" class="m-0 font-weight-bold text-primary">* Academic Program</label>
                                                    <select name="program_id" class="form-control text-uppercase" id="program_id">
                                                        <option value="">- select program -</option>
                                                        @php
                                                            $groupName = '';
                                                            $first = true;
                                                        @endphp
                                                        @if($programs)
                                                            @foreach($programs as $program)
                                                                @if ($program->level->level != $groupName)
                                                                    @php
                                                                        $groupName = $program->level->level; // Just set the new group name
                                                                    @endphp

                                                                    @if (!$first) <!-- Add a closing tag when we change the group, but only if we're not in the first loop -->
                                                                        </optgroup>
                                                                    @else
                                                                        @php
                                                                            $first = false; // Make sure we don't close the tag first time, but do after the first loop
                                                                        @endphp
                                                                    @endif
                                                                    <optgroup label="{{ $groupName }}">
                                                                @endif
                                                                <option value="{{ $program->id }}">
                                                                    ({{ $program->code }}) - {{ $program->name }}
                                                                </option>
                                                            @endforeach
                                                            @if (!$first) <!-- Add a closing tag after the last loop -->
                                                                </optgroup>
                                                            @endif
                                                        @endif
                                                    </select>
                                                </div>
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
                            <div class="row m-0">
                                <div class="col-lg-12">
                                    @include('application.partials.academic_information')
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="col-lg-12">
                                    @include('application.partials.family_information')
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="col-lg-12">
                                    @include('application.partials.attach_requirements')
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <script>
        var baseUrl = '{{ asset("") }}';
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sbadmin/js/jquery-ui.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>

    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/jquery_application.js') }}"></script>
</body>

</html>