{{-- {{ dd($evaluation) }} --}}
@extends('layout')
@section('title') {{ 'Evaluate Student' }} @endsection
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-2">
            <h1 class="h3 text-800 text-primary m-0">Student's Evaluation</h1>
        </div> 
        <div class="card-body">
            <div class="row">
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">ID No.</label>
                </div>
                <div class="col-md-4">
                    {{ $student->user->idno }}
                    <input type="hidden" id="student_id" value="{{ $student->id }}" />
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Program</label>
                </div>
                <div class="col-md-6">
                    ({{ $student->program->code }}) {{ $student->program->name }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Name</label>
                </div>
                <div class="col-md-4">
                    {{ $student->name }}
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
                <div class="col-md-3">
                    {{ $student->curriculum->curriculum }}
                </div>
            </div>
        </div>
    </div>
    @if ($evaluation === false)
        <div class="card shadow mb-4">
            <div class="card-header py-2 mid">
                <h1 class="h3 text-800 text-danger m-0">ACCESS DENIED</h1>
            </div> 
            <div class="card-body">
                <h1 class="h3 text-800 text-danger m-0 mid">Sorry you are not allowed to evaluate the selected student, your account does not have enough access to student's current program! You can update student's program first!</h1>
            </div>
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-2 mid">
                <h1 class="h3 text-800 text-primary m-0">Curriculum {{ $curriculum->curriculum }}</h1>
                <input type="hidden" name="curriculum_id" value="{{ $curriculum->id }}" id="curriculum_id" />
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
                    @include('evaluation.return_evaluation')
                </div>
            </div>
        </div>
    @endif
</div>
@endsection