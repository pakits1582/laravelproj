@extends('layout')
@section('title') {{ 'Colleges List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Colleges</h1>
        <p class="mb-4">List of all colleges in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h1 class="h3 text-800 text-primary mb-0">List of Schools and Colleges</h1>
                    </div>
                    <div class="col-md-5 right">
                        <a href="{{ route('colleges.create') }}" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-square"></i>
                            </span>
                            <span class="text">Add New College</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="filter_form" target="_blank" data-field="colleges">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department" class="m-0 font-weight-bold text-primary">Keyword</label>
                                <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control" id="keyword">
                            </div>
                        </div>
                    </div>
                </form>
                @include('college.return_colleges')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection