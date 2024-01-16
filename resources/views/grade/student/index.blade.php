{{-- {{ dd($evaluation) }} --}}
@extends('layout')
@section('title') {{ 'Grade File' }} @endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Grade File</h1>
    <p class="mb-4">View all recorded internal and external grades.</p>

    @include('partials.student.information')
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Grade File</h6>
        </div>
        <div class="card-body">
            <div id="">
                @include('grade.student.grade_file')
            </div>
        </div>
    </div>
</div>
@endsection