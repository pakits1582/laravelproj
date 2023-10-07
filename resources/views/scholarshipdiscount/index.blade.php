@extends('layout')
@section('title') {{ 'Scholarships and Discounts List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Scholarships and Discounts</h1>
        <p class="mb-4">List of all scholarships and discounts in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h6 class="font-weight-bold text-primary mb-0">List of Scholarships and Discounts</h6>
                    </div>
                    <div class="col-md-5 right">
                        <a href="{{ route('scholarshipdiscounts.create') }}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-square"></i>
                            </span>
                            <span class="text">Add New Scholarship or Discount</span>
                        </a>
                    </div>
                </div>                
            </div>
            <div class="card-body">
                <form method="POST" action="" id="filter_form" target="_blank" data-field="scholarshipdiscounts">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type" class="m-0 font-weight-bold text-primary">Type</label>
                                <select name="type" class="form-control dropdownfilter" id="type">
                                    <option value="">- select type -</option>
                                    <option value="1">Scholarship</option>
                                    <option value="2">Discount</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="keyword" class="m-0 font-weight-bold text-primary">Keyword</label>
                                <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control" id="keyword">
                            </div>
                        </div>
                    </div>
                </form>
                @include('scholarshipdiscount.return_scholarshipdiscount')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection