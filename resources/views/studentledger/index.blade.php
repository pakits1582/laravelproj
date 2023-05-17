@extends('layout')
@section('title') {{ 'Statement of Account' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Statement of Account</h1>
        <p class="mb-4">Student's payables and payments trail.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Statement of Account <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            {{-- <p class="font-italic text-info">Note: (*) Denotes field is required.</p> --}}
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" action=""  role="form" id="form_studentledger">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">* Student</label>
                                                <select name="student_id" class="form-control select clearable" id="student">
                                                    <option value="">- search student -</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Level</label>
                                                <input type="text" id="educational_level" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">College</label>
                                                <input type="text" id="college" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="period"  class="m-0 font-weight-bold text-primary">* Period</label>
                                                <select name="period_id" class="form-control" id="period" required>
                                                    <option value="">All Periods</option>
                                                    @if ($periods)
                                                        @foreach ($periods as $period)
                                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_period_id" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Curriculum</label>
                                                <input type="text" id="curriculum" class="form-control text-uppercase clearable" readonly value="" placeholder="">

                                            </div>                                         
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">Year Level</label>
                                                <input type="text" id="year_level" class="form-control text-uppercase clearable" readonly value="" placeholder="">

                                            </div>                                 
                                        </div>
                                    </div>
                                    <div class="row  align-items-end">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Program</label>
                                                <input type="text" id="program" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group right" id="button_group">
                                                <button type="button" id="print_soa" class="btn btn-success btn-icon-split actions mb-2" disabled>
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-print"></i>
                                                    </span>
                                                    <span class="text">Print Statement of Account</span>
                                                </button>
                                                <button type="button" id="print_permit" class="btn btn-primary btn-icon-split actions mb-2" disabled>
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-print"></i>
                                                    </span>
                                                    <span class="text">Print Permit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                          <!-- End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="return_soa">
            <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection