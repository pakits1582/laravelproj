@extends('layout')
@section('title') {{ 'Student\'s Profile' }} @endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Student's Profile</h1>
    <p class="mb-4">View and update your personal and academic information.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="font-weight-bold text-primary m-0">Student Information</h6>
        </div> 
        <div class="card-body">
            <div class="row">
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">ID No.</label>
                </div>
                <div class="col-md-5">
                    {{ $student->user->idno }}
                    <input type="hidden" id="student_id" value="{{ $student->id }}" />
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Year</label>
                </div>
                <div class="col-md-2">
                    {{ Helpers::yearLevel($student->year_level) }}
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Curriculum</label>
                </div>
                <div class="col-md-2">
                    {{ $student->curriculum->curriculum }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Name</label>
                </div>
                <div class="col-md-5">
                    {{ $student->name }}
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Level</label>
                </div>
                <div class="col-md-2">
                    {{ $student->program->level->code }}
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">College</label>
                </div>
                <div class="col-md-2">
                    {{ $student->program->collegeinfo->code }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Program</label>
                </div>
                <div class="col-md-11">
                    ({{ $student->program->code }}) {{ $student->program->name }}
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Statement of Account</h6>
        </div>
        <div class="card-body">
            <div id="">
                
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Schedule</h6>
                </div>
                <div class="card-body">
                    <div id="">
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Previous Balance or Refund</h6>
                </div>
                <div class="card-body">
                    <div id="">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Previous Statement of Accounts</h6>
        </div>
        <div class="card-body">
            <div id="">
                
            </div>
        </div>
    </div>
</div>
@endsection