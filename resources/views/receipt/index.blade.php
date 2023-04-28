@extends('layout')
@section('title') {{ 'Receipt Entry' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Receipt Entry</h1>
        <p class="mb-4">Collect student payment, manage receipt, view collection</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Payment Collection <span id="period_name">{{ session('periodname') }}</span></h1>
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
                                <form method="POST" action=""  role="form" id="form_receipt">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="receipt_no"  class="m-0 font-weight-bold text-primary">* Receipt No.</label>
                                                <input type="text" name="receipt_no" id="receipt_no" class="form-control text-uppercase clearable"  value="" placeholder="Receipt No.">
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="receipt_date"  class="m-0 font-weight-bold text-primary">* Date</label>
                                                <input type="text" name="receipt_date" id="receipt_date" class="form-control text-uppercase clearable" value="" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Student</label>
                                                <select name="student_id" class="form-control select" id="student">
                                                    <option value="">- search student -</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="payor_name" class="m-0 font-weight-bold text-primary">Payor Name</label>
                                                <input type="text" name="payor_name" id="payor_name" class="form-control text-uppercase clearable" value="" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Payment Period</label>
                                                <input type="text" id="period" class="form-control text-uppercase clearable" readonly value="{{ session('periodname') }}" placeholder="">
                                                <input type="hidden" name="period_id" id="period_id" value="{{ session('current_period') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">College</label>
                                                <input type="text" id="college" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">Level</label>
                                                <input type="text" id="educational_level" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>                                 
                                        </div>
                                    </div>
                                    <div class="row align-items-end">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="program" class="m-0 font-weight-bold text-primary">Program</label>
                                                <input type="text" name="program" id="program" class="form-control text-uppercase clearable text-uppercase" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="year_level" class="m-0 font-weight-bold text-primary">Year Level</label>
                                                <input type="text" id="year_level" class="form-control text-uppercase clearable" readonly value="" placeholder="">
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
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Previous Balance or Refund</h6>
                    </div>
                    <div class="card-body" id="">
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payables and Payments Trail</h6>
                    </div>
                    <div class="card-body" id="">
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                    </div>
                    <div class="card-body" id="">
                        @include('receipt.payment_info')
                    </div>
                </div>
            </div>
        </div>

        @include('studentadjustment.create')
    </div>
    <!-- /.container-fluid -->
@endsection