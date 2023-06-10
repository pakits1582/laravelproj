@extends('layout')
@section('title') {{ 'Faculty Loads' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Faculty Loads</h1>
        <p class="mb-4">List of all subjects assigned to faculty.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Faculty Loads <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                <form method="POST" id="form_filterfacultyload" action="">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Period</label>
                                <select name="period_id" class="form-control filter_item" id="period">
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
                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level_id', 'fieldid' => 'educational_level', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                @include('partials.colleges.dropdown', ['fieldname' => 'college_id', 'fieldid' => 'college', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </form>
                <table id="scrollable_table" class="table table-striped table-bordered hover" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="w150">Name</th>
                            <th class="w50">Code</th>
                            <th class="w120">Section</th>
                            <th class="w120">Subject</th>
                            <th class="">Description</th>
                            <th class="">Schedule</th>
                            <th class="w30">Load</th>
                            <th class="w30">Lec</th>
                            <th class="w30">Lab</th>
                            <th class="w30">Units</th>
                        </tr>
                    </thead>
                    <tbody id="return_facultyload">
                        @include('facultyload.return_facultyload')
                    </tbody>
                </table>
                <div class="row mt-3 d-flex align-items-center">
                    <div class="col-md-2">
                        <h6 class="m-0 font-weight-bold text-primary mb-2">Total Units (<span id="totalunits">{{ $faculty_loads->sum('loadunits') ?? 0 }}</span>)</h6>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control filter_item mb-2" name="faculty_id" id="faculty_id">
                            <option value="">ALL FACULTY</option>
                            @if (count($faculty) > 0)
                                @foreach ($faculty as $fac)
                                    @if ($fac->instructor_id != NULL)
                                        <option value="{{ $fac->instructor_id }}">{{ $fac->full_name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" id="print_masterlist" class="btn btn-danger btn-icon-split actions mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-print"></i>
                            </span>
                            <span class="text">Print PDF</span>
                        </button>
                        <button type="submit" id="download_masterlist" class="btn btn-success btn-icon-split actions mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-download"></i>
                            </span>
                            <span class="text">Download Excel</span>
                        </button>
                        <button type="submit" id="other_assignments" class="btn btn-primary btn-icon-split actions mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Other Assignments</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection