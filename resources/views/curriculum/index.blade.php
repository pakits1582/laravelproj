@extends('layout')
@section('title') {{ 'Curriculum Management' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Curriculum Management</h1>
        <p class="mb-4">List of all programs in the database</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h6 class="font-weight-bold text-primary mb-0">All Programs Under Deanship/Headship</h6>
                    </div>
                    <div class="col-md-5 right">
                        
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (Auth::user()->utype == 0)
                    <form method="POST" action="" id="filter_form" target="_blank" data-field="curriculum">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="department" class="m-0 font-weight-bold text-primary">Keyword</label>
                                    <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control" id="keyword">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                    @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level', 'fieldid' => 'educational_level', 'fieldclass' => 'dropdownfilter'])
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                    @include('partials.colleges.dropdown', ['fieldname' => 'college', 'fieldid' => 'college', 'fieldclass' => 'dropdownfilter'])
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
                @include('curriculum.return_programs')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection