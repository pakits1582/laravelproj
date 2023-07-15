@extends('layout')
@section('title') {{ 'Applications' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Applications</h1>
        <p class="mb-4">List and management of student applications.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Applications <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                <form method="POST" id="form_filterapplication" action="">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Period</label>
                                <select name="period_id" class="form-control filter_item" id="period_id">
                                    @if ($periods)
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level', 'fieldid' => 'educational_level', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                @include('partials.colleges.dropdown', ['fieldname' => 'college', 'fieldid' => 'college', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="program_id" class="m-0 font-weight-bold text-primary">Program</label>
                                <select name="program_id" class="form-control filter_item" id="program">
                                    <option value="">- select program -</option>
                                    @if ($programs)
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}">{{ $program->code }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="return_applicants">
                    @include('application.return_applicants')
                </div>
                <div class="row mt-3 d-flex align-items-center">
                    <div class="col-md-2">
                        <div class="form-group">
                            <h6 class="m-0 font-weight-bold text-primary">Total Applicants (<span id="totalcount">{{ count($applicants) ?? 0 }}</span>)</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="button" id="delete_selected_applicants" class="btn btn-danger btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Delete Selected</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection