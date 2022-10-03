@extends('layout')
@section('title') {{ 'Programs List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Programs</h1>
        <p class="mb-4">List of all programs in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{-- <h6 class="m-0 font-weight-bold text-primary">programs Table</h6> --}}
                <a href="{{ route('programs.create') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-square"></i>
                    </span>
                    <span class="text">Add New Program</span>
                </a>
                <a href="#" class="btn btn-danger btn-icon-split" id="generate_pdf">
                    <span class="icon text-white-50">
                        <i class="fas fa-print"></i>
                    </span>
                    <span class="text">Print PDF</span>
                </a>
                <a href="#" class="btn btn-success btn-icon-split" id="download_excel">
                    <span class="icon text-white-50">
                        <i class="fas fa-download"></i>
                    </span>
                    <span class="text">Download Excel</span>
                </a>
                <a href="{{ route('programs.import') }}" class="btn btn-secondary btn-icon-split" id="upload_excel" data-field="programs">
                    <span class="icon text-white-50">
                        <i class="fas fa-upload"></i>
                    </span>
                    <span class="text">Upload Excel</span>
                </a>
                <div>
                    <form method="POST" action="" id="filter_form" target="_blank" data-field="programs">
                        @csrf
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="department" class="m-0 font-weight-bold text-primary">Keyword</label>
                                        <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control" id="keyword">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                        @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level', 'fieldid' => 'educational_level', 'fieldclass' => 'dropdownfilter'])
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                        @include('partials.colleges.dropdown', ['fieldname' => 'college', 'fieldid' => 'college', 'fieldclass' => 'dropdownfilter'])
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="designation" class="m-0 font-weight-bold text-primary">Status</label>
                                        <select name="status" class="form-control dropdownfilter" id="status">
                                            <option value="">- select status -</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @include('program.return_programs')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection