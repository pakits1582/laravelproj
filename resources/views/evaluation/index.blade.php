@extends('layout')
@section('title') {{ 'Evaluate Student' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Evaluate Students</h1>
        <p class="mb-4">List of all students in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h1 class="h3 text-800 text-primary mb-0">Evaluate Students</h1>
                    </div>
                    <div class="col-md-5 right">
                        
                    </div>
                </div>  
            </div>
            <div class="card-body">
                <form method="POST" action="" id="filter_form" target="_blank" data-field="evaluations">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department" class="m-0 font-weight-bold text-primary">Keyword</label>
                                <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control" id="keyword">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="program" class="m-0 font-weight-bold text-primary">Program</label>
                                @include('partials.programs.dropdown', ['fieldname' => 'program', 'fieldid' => 'program', 'fieldclass' => 'dropdownfilter'])
                            </div>
                        </div>
                    </div>
                </form>
                @include('evaluation.return_students')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection