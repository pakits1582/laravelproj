@extends('layout')
@section('title') {{ 'Assessment' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Assessment</h1>
        <p class="mb-4">Student registered subjects and breakdown of fees.</p>

        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Assessment Preview</h6>
                    </div>
                    <div class="card-body" id="">
                        @if (isset($success) && $success === false)
                            <h3 class="m-0 text-800 text-danger mid">No Assessment to be displayed!</h3>
                        @else
                            @include('assessment.assessment_preview')
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- DISPLAY SCHEDULE TABLE --}}
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Schedule Table</h6>
                    </div>
                    <div class="card-body">
                        <div id="">
                            @include('class.schedule_table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection