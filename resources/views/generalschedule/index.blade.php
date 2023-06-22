@extends('layout')
@section('title') {{ 'General Schedule' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">General Schedule</h1>
        <p class="mb-4">List of all classes offering of current term and their size.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">General Schedule <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                <form method="POST" id="form_filtergeneralschedule" action="">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">Dissolved</label>
                                <div class="legend dissolved"></div>   
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">Tutorial</label>
                                <div class="legend tutorial"></div>   
                            </div>
                        </div>
                        
                    </div>
                </form>
                <div id="return_generalschedule">
                    @include('generalschedule.return_generalschedule')
                </div>
                <div class="row mt-3 d-flex align-items-center">
                    <div class="col-md-1">
                        <div class="form-group">
                            <h6 class="m-0 font-weight-bold text-primary mid">Display</h6>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select name="display" class="form-control filter_item" id="display">
                                <option value="1">ALL</option>
                                <option value="open">OPEN</option>
                                <option value="closed">CLOSED</option>
                                <option value="dissolved">DISSOLVED</option>
                                <option value="tutorial">TUTORIAL</option>
                                <option value="f2f">F2F</option>
                                <option value="merged">MERGED</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" id="print_masterlist" class="btn btn-danger btn-icon-split actions mb-3">
                            <span class="icon text-white-50">
                                <i class="fas fa-print"></i>
                            </span>
                            <span class="text">Print PDF</span>
                        </button>
                        <button type="submit" id="download_masterlist" class="btn btn-success btn-icon-split actions mb-3">
                            <span class="icon text-white-50">
                                <i class="fas fa-download"></i>
                            </span>
                            <span class="text">Download Excel</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection