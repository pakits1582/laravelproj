{{-- {{ dd($evaluation) }} --}}
@extends('layout')
@section('title') {{ 'Curriculum Evaluation' }} @endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Curriculum Evaluation</h1>
    <p class="mb-4">Student's curriculum evaluation.</p>

    @include('partials.student.information')
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 mid">
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