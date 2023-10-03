@extends('layout')
@section('title') {{ 'Grading Systems List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Grading Systems</h1>
        <p class="mb-4">List of all grading system in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h1 class="h3 text-800 text-primary mb-0">Grade System Management</h1>
                    </div>
                    <div class="col-md-5 right">
                        <a href="{{ route('gradingsystems.create') }}" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-square"></i>
                            </span>
                            <span class="text">Add New Grade</span>
                        </a>
                    </div>
                </div>                
            </div>
            <div class="card-body">
                <form method="POST" action="" id="filter_form" target="_blank" data-field="gradingsystems">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="keyword" class="m-0 font-weight-bold text-primary">Keyword</label>
                                <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control" id="keyword">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id', 'fieldclass' => 'dropdownfilter'])
                            </div>
                        </div>
                    </div>
                </form>
                @include('gradingsystem.return_gradingsystems')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection