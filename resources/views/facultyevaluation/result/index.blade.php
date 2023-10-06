@extends('layout')
@section('title') {{ 'Faculty Evaluation Results' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Faculty Evaluation Results</h1>
        <p class="mb-4">List and management of faculty evaluation results.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h6 class="font-weight-bold text-primary mb-0">Faculty Evaluation Results <span id="period_name">{{ session('periodname') }}</span></h6>
                    </div>
                    <div class="col-md-5 right">
                        <a href="{{ route('facultyevaluations.index') }}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="text">Faculty Evaluation</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="" data-field="">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instructor_id" class="m-0 font-weight-bold text-primary">Faculty</label>
                                <select name="instructor_id" class="form-control" id="instructor_id" data-field="results">
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
                <div id="return_results">
                    @include('facultyevaluation.result.return_results')
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection