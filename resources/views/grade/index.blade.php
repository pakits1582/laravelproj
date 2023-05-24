@extends('layout')
@section('title') {{ 'Grade File' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Grade File</h1>
        <p class="mb-4">Student's all recorded grades.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Student's Information</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="code"  class="m-0 font-weight-bold text-primary">Grade No.</label>
                                        <input type="text" id="grade_id" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-7"></div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="code"  class="m-0 font-weight-bold text-primary">Curriculum</label>
                                        <input type="text" id="curriculum" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="term" class="m-0 font-weight-bold text-primary">Student</label>
                                        <select name="student_id" class="form-control select clearable" id="student">
                                            <option value="">- search student -</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="class_end" class="m-0 font-weight-bold text-primary">Level</label>
                                        <input type="text" name="" id="educational_level" placeholder="" class="form-control text-uppercase" readonly value="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="class_ext" class="m-0 font-weight-bold text-primary">College</label>
                                        <input type="text" name="" id="college" placeholder="" class="form-control text-uppercase" readonly value="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="class_ext" class="m-0 font-weight-bold text-primary">Year Level</label>
                                        <input type="text" name="" id="year_level" placeholder="" class="form-control text-uppercase" readonly value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_start" class="m-0 font-weight-bold text-primary">Period</label>
                                        <select name="period_id" class="form-control" id="period_id" required>
                                            <option value="">All Periods</option>
                                            @if ($periods)
                                                @foreach ($periods as $period)
                                                    <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_end" class="m-0 font-weight-bold text-primary">Program</label>
                                        <input type="text" name="" id="program" placeholder="" class="form-control text-uppercase" readonly value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="return_gradefile">
            <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection