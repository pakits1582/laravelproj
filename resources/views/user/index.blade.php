@extends('layout')
@section('title') {{ 'Users List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Users</h1>
        <p class="mb-4">List of all users in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h6 class="font-weight-bold text-primary mb-0">User Management</h6>
                    </div>
                    <div class="col-md-5 right">
                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-square"></i>
                            </span>
                            <span class="text">Add New User</span>
                        </a>
                    </div>
                </div>
                
                {{-- <a href="#" class="btn btn-danger btn-icon-split" id="generate_pdf">
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
                <a href="{{ route('subjects.import') }}" class="btn btn-secondary btn-icon-split" id="upload_excel" data-field="subjects">
                    <span class="icon text-white-50">
                        <i class="fas fa-upload"></i>
                    </span>
                    <span class="text">Upload Excel</span>
                </a> --}}
            </div>
            <div class="card-body">
                <form method="POST" action="" id="filter_form" target="_blank" data-field="users">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="designation" class="m-0 font-weight-bold text-primary">User Type</label>
                                <select name="type" class="form-control dropdownfilter" id="type">
                                    <option value="0" selected>Admin</option>
                                    <option value="1">Instructor</option>
                                    <option value="2">Student</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="department" class="m-0 font-weight-bold text-primary">Keyword</label>
                                <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control" id="keyword">
                            </div>
                        </div>
                        <div class="col-md-4">
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
                </form>
                @include('user.return_users')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection