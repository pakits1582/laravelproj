@extends('layout')
@section('title') {{ 'Fees List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Fees</h1>
        <p class="mb-4">List of all fees in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h6 class="font-weight-bold text-primary mb-0">List of Fees</span></h6>
                    </div>
                    <div class="col-md-5 right">
                        <a href="{{ route('fees.create') }}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-square"></i>
                            </span>
                            <span class="text">Add New Fee</span>
                        </a>
                    </div>
                </div>                
            </div>
            <div class="card-body">
                <form method="POST" action="" id="filter_form" target="_blank" data-field="fees">
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
                                <label for="fee_type" class="m-0 font-weight-bold text-primary">Fee Type</label>
                                <select name="fee_type_id" class="form-control dropdownfilter" id="fee_type">
                                    <option value="">- select type -</option>
                                    @if ($fee_types)
                                        @foreach ($fee_types as $fee_type)
                                            <option  value="{{ $fee_type->id }}">{{ $fee_type->type }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                @include('fee.return_fees')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection