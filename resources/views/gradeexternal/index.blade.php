@extends('layout')
@section('title') {{ 'External Grades' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">External Grades</h1>
        <p class="mb-4">Student's externally recorded grades.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Student's Information</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="term" class="m-0 font-weight-bold text-primary">Student</label>
                                        <select name="student_id" class="form-control select" id="student">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="class_start" class="m-0 font-weight-bold text-primary">Period</label>
                                        <select name="period_id" class="form-control" id="period">
                                            <option value="">- select period -</option>
                                            @if ($periods)
                                                @foreach ($periods as $period)
                                                    <option value="{{ $period->id }}" 
                                                        {{ (old('period') === $period->id) ? 'selected' : '' }}
                                                        {{ (session('current_period') === $period->id) ? 'selected' : '' }}
                                                        >{{ $period->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="code"  class="m-0 font-weight-bold text-primary">Curriculum</label>
                                        <input type="text" id="curriculum" class="form-control text-uppercase" readonly value="" placeholder="">
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
        <div id="form_container">
            @include('gradeexternal.create')
        </div>
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="m-0 font-weight-bold text-primary">Student's External Grade File</h6>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" id="internal_grade_info" class="btn btn-primary btn-icon-split mb-2 mb-md-0" disabled>
                                    <span class="icon text-white-50">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span class="text">External Grade Info</span>
                                </button>                            
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                <thead class="">
                                    <tr>
                                        <th class="w30"></th>
                                        <th class="w20"></th>
                                        <th class="w170">School</th>
                                        <th class="w150">Program</th>
                                        <th class="w150">Subject Code</th>
                                        <th class="">Description</th>
                                        <th class="w80">Grade</th>
                                        <th class="w50">C. G.</th>
                                        <th class="w50">Equiv</th>
                                        <th class="w50">Units</th>
                                        <th class="w120">Remark</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black" id="return_external_grades">
                                    <tr><td class="mid" colspan="11">No records to be displayed!</td></tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection