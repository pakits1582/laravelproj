@extends('layout')
@section('title') {{ 'Enrollment Summary' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Enrollment Summary</h1>
        <p class="mb-4">Summary of enrolled students by term, colleges and year level.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="font-weight-bold text-primary mb-0">Enrollment Summary <span id="period_name">{{ session('periodname') }}</span></h6>
            </div>
            <div class="card-body">
                <form method="POST" id="form_filtersummary" action="">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Period</label>
                                <select name="period_id" class="form-control filter_item" id="period_id">
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
                                <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                @include('partials.colleges.dropdown', ['fieldname' => 'college', 'fieldid' => 'college', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-5 right">
                            <div class="form-group">
                                <button type="button" id="print_permit" class="btn btn-sm btn-primary btn-icon-split actions">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-print"></i>
                                    </span>
                                    <span class="text">Print Enrollment Summary</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="return_enrollmentsummary">
                    @include('enrollmentsummary.summary')
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection