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
                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level', 'fieldid' => 'educational_level', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                @include('partials.colleges.dropdown', ['fieldname' => 'college', 'fieldid' => 'college', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </form>
                <table id="scrollable_table" class="table table-sm table-striped table-bordered hover" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="">Name</th>
                            <th class="w50">Code</th>
                            <th class="w150">Section</th>
                            <th class="w150">Subject</th>
                            <th class="">Description</th>
                            <th class="">Schedule</th>
                            <th class="w50">Load</th>
                            <th class="w50">Lec</th>
                            <th class="w50">Lab</th>
                            <th class="w50">Units</th>
                        </tr>
                    </thead>
                    <tbody id="return_masterlist">
                        @include('facultyload.return_facultyload')
                    </tbody>
                </table>
                <div class="row mt-3 d-flex align-items-center">
                    <div class="col-md-2">
                        <h6 class="m-0 font-weight-bold text-primary">Total Units (<span id="totalunits"></span>)</h6>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control filter_item" name="faculty_id" id="faculty_id">
                            <option>All Faculty</option>
                        </select>
                    </div>
                    <div class="col-md-7">
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
                        <button type="submit" id="download_masterlist" class="btn btn-primary btn-icon-split actions mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Faculty Other Assignment</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection