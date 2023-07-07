@extends('layout')
@section('title') {{ 'Masterlist' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Masterlist</h1>
        <p class="mb-4">List of all students enrolled in the period.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Masterlist <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                <form method="POST" id="form_filtermasterlist" action="">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level', 'fieldid' => 'educational_level', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">Year Level</label>
                                <select name="year_level" class="form-control filter_item" id="year_level">
                                    <option value="">- select year -</option>
                                    <option value="1">First Year</option>
                                    <option value="2">Second Year</option>
                                    <option value="3">Third Year</option>
                                    <option value="4">Fourth Year</option>
                                    <option value="5">Fifth Year</option>
                                    <option value="6">Sixth Year</option>
                                </select>                        
                            </div>
                        </div>
                    </div>
                </form>
                <div id="return_masterlist">
                    @include('masterlist.return_masterlist')
                </div>
                <div class="row mt-3 d-flex align-items-center">
                    <div class="col-md-2">
                        <div class="form-group">
                            <h6 class="m-0 font-weight-bold text-primary">Total Students (<span id="totalcount">{{ count($masterlist) }}</span>)</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="all"  class="m-0 font-weight-bold text-primary"><input type="radio" class="filter_item" name="status" value="2" id="all" checked> All </label>
                            <label for="validated"  class="m-0 font-weight-bold text-primary"><input type="radio" class="filter_item" name="status" value="1" id="validated"> Validated </label>    
                            <label for="unpaid"  class="m-0 font-weight-bold text-primary"><input type="radio" class="filter_item" name="status" value="0" id="unpaid"> Unpaid </label>    
                        </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection