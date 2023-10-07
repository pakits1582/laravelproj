@extends('layout')
@section('title') {{ 'Internal Grades' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Internal Grades</h1>
        <p class="mb-4">Student's internally recorded grades.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="font-weight-bold text-primary mb-0">Internal Grades Management</h6>
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
                                        @include('partials.periods.dropdown', ['value' => session('current_period'), 'fieldname' => 'period_id', 'fieldid' => 'period_id'])
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
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="m-0 font-weight-bold text-primary">Student's Internal Grade File</h6>
                            </div>
                            <div class="col-md-6 text-right">
                                {{-- <button type="button" id="add_internal_grade" class="btn btn-sm btn-success btn-icon-split mb-2 mb-md-0">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus-square"></i>
                                    </span>
                                    <span class="text">Add Internal Grade</span>
                                </button> --}}
                                <button type="button" id="grade_information" class="btn btn-sm btn-primary btn-icon-split actions mb-2 mb-md-0">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span class="text">Internal Grade Info</span>
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
                                        <th class="w150">Section</th>
                                        <th class="w50">Class</th>
                                        <th class="w150">Subject Code</th>
                                        <th class="">Subject Description</th>
                                        <th class="w50">Units</th>
                                        <th class="w50">Grade</th>
                                        <th class="w50">C. G.</th>
                                        <th class="w150">Remark</th>
                                        <th class="w150">Instructor</th>
                                        <th class="50"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-black" id="return_internal_grades">
                                    <tr><td class="mid" colspan="13">No records to be displayed!</td></tr>
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