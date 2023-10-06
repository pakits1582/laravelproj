@extends('layout')
@section('title') {{ 'Subjects List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Subjects</h1>
        <p class="mb-4">List of all subjects in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h6 class="font-weight-bold text-primary mb-0">List of Subjects</h6>
                    </div>
                    <div class="col-md-9 right">
                        <a href="{{ route('subjects.create') }}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-square"></i>
                            </span>
                            <span class="text">Add New Subject</span>
                        </a>
                        <a href="#" class="btn btn-sm btn-danger btn-icon-split" id="generate_pdf">
                            <span class="icon text-white-50">
                                <i class="fas fa-print"></i>
                            </span>
                            <span class="text">Print PDF</span>
                        </a>
                        <a href="#" class="btn btn-sm btn-success btn-icon-split" id="download_excel">
                            <span class="icon text-white-50">
                                <i class="fas fa-download"></i>
                            </span>
                            <span class="text">Download Excel</span>
                        </a>
                        <a href="{{ route('subjects.import') }}" class="btn btn-sm btn-secondary btn-icon-split" id="upload_excel" data-field="subjects">
                            <span class="icon text-white-50">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="text">Upload Excel</span>
                        </a>
                    </div>
                </div>                
            </div>
            <div class="card-body">
                <form method="POST" action="" id="filter_form" target="_blank" data-field="subjects">
                    @csrf
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
                                <label for="designation" class="m-0 font-weight-bold text-primary">Type</label>
                                <select name="type" class="form-control dropdownfilter" id="type">
                                    <option value="">All</option>
                                    <option value="laboratory">Laboratory</option>
                                    <option value="professional">Professional</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                @include('subject.return_subjects')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection