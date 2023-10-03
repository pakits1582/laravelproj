@extends('layout')
@section('title') {{ 'Faculty Evaluation' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Faculty Evaluations</h1>
        <p class="mb-4">List and management of classes for evaluation.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h1 class="h3 text-800 text-primary mb-0">Faculty Evaluations <span id="period_name">{{ session('periodname') }}</span></h1>
                    </div>
                    <div class="col-md-5 right">
                        <a href="{{ route('facultyevaluations.questions') }}" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="text">Survey Questions</span>
                        </a>
                        <a href="{{ route('facultyevaluations.results') }}" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-list"></i>
                            </span>
                            <span class="text">Evaluation Results</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="" data-field="">
                    @csrf
                    <div class="row">
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Period</label>
                                <select name="period_id" class="form-control" id="period_id">
                                    @if ($periods)
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instructor_id" class="m-0 font-weight-bold text-primary">Faculty</label>
                                <select name="instructor_id" class="form-control" id="instructor_id" data-field="evaluations">
                                    <option value="">- select faculty -</option>
                                    @if ($instructors)
                                        @foreach ($instructors as $instructor)
                                            @if ($instructor['id'] !== null)
                                                <option value="{{ $instructor['id'] }}">{{ $instructor['full_name'] }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="return_evaluations">
                    @include('facultyevaluation.return_evaluations')
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection