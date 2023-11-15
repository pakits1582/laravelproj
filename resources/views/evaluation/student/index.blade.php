{{-- {{ dd($evaluation) }} --}}
@extends('layout')
@section('title') {{ 'Curriculum Evaluation' }} @endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Student's Evaluation</h1>
    <p class="mb-4">Student's curriculum evaluation.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-2">
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
        <div class="card-header py-2 mid">
            <h1 class="h3 text-800 text-primary m-0">Curriculum {{ $student->curriculum->curriculum }}</h1>
        </div> 
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-md-2 text-lg-right">
                    <h6 class="m-0 font-weight-bold text-primary">Evaluated</h6>
                </div>
                <div class="col-md-1">
                    <div class="p-3 bg-success text-white"></div>
                </div>
                <div class="col-md-2 text-lg-right">
                    <h6 class="m-0 font-weight-bold text-primary">Tagged</h6>
                </div>
                <div class="col-md-1">
                    <div class="p-3 bg-primary text-white"></div>
                </div>
                <div class="col-md-2 text-lg-right">
                    <h6 class="m-0 font-weight-bold text-primary">In Progress</h6>
                </div>
                <div class="col-md-1">
                    <div class="p-3 text-white" style="background-color: black"></div>
                </div>
                <div class="col-md-2 text-lg-right">
                    <h6 class="m-0 font-weight-bold text-primary">Deficiency</h6>
                </div>
                <div class="col-md-1">
                    <div class="p-3 bg-danger text-white"></div>
                </div>
            </div>
            <div id="return_evaluation">
                @include('evaluation.student.evaluation')
            </div>
        </div>
    </div>
</div>
@endsection