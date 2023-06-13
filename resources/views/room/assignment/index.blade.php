@extends('layout')
@section('title') {{ 'Room Assignment' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Room Assignment</h1>
        <p class="mb-4">List of classes assigned to the rooms.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Room Assignment <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                <div class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Period</label>
                                <select name="period_id" class="form-control filter_item" id="period">
                                    @if ($periods)
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Room</label>
                                <select name="period_id" class="form-control filter_item" id="period">
                                    <option value="">- select room -</option>
                                    {{-- @if ($periods)
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                        @endforeach
                                    @endif --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="submit" id="print_roomassignment" class="btn btn-danger btn-icon-split actions mb-2">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-print"></i>
                                    </span>
                                    <span class="text">Print Room Assignment</span>
                                </button>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div id="return_roomassignment">
            <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection