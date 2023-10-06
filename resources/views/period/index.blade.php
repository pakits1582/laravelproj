@extends('layout')
@section('title') {{ 'Period List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Period</h1>
        <p class="mb-4">List of all periods in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h6 class="font-weight-bold text-primary mb-0">List of Periods</h6>
                    </div>
                    <div class="col-md-5 right">
                        <a href="{{ route('periods.create') }}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-square"></i>
                            </span>
                            <span class="text">Add New Period</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="filter_form" target="_blank" data-field="periods">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department" class="m-0 font-weight-bold text-primary">Keyword</label>
                                <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control" id="keyword">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="source" class="m-0 font-weight-bold text-primary">Source</label>
                                <select name="source" class="form-control dropdownfilter" id="source">
                                    <option value="">- select source -</option>
                                    <option value="1">Internal</option>
                                    <option value="2">External</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                @include('period.return_periods')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection