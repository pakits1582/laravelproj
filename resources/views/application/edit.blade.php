@extends('layout')
@section('title') {{ 'Edit Application' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Edit Application</h1>
        <p class="mb-4">Updating of student's applications.</p>
        
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-0">
                    <div class="card-header py-3">
                        <h4 class="m-0 font-weight-bold text-primary">Edit Application for {{ $applicant->entryperiod->name }}</h4>
                    </div>
                    <div class="card-body p-0">
                        <form method="POST" id="form_update_application" action="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('application.partials.instruction_classification', ['withperiod' => $withperiod])
                            
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
                            <div class="row p-3">
                                <div class="col-lg-12">
                                    <input type="hidden" id="student_applicant" value="{{ $applicant->id ?? '' }}" >
                                    <input type="submit" name="" id="" class="btn btn-primary btn-user btn-block btn-lg" value="Update Application">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <!-- /.container-fluid -->
@endsection