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
                                        <input type="text" id="internalgrade_id" class="form-control text-uppercase clearable" readonly value="" placeholder="">
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
                        <h6 class="m-0 font-weight-bold text-primary">Add Internal Grade</h6>
                    </div>
                    <div class="card-body">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                        <form method="POST" action=""  role="form" id="form_enrollment">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="year_level" class="m-0 font-weight-bold text-primary">* Subject</label>
                                        <select name="subject_id"  class="form-control" id="subject">
                                            <option value="">- select subject -</option>
                                            @if ($subjects)
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->id }}" id="option_{{ $subject->id }}" title="{{ $subject->name }}">({{ $subject->units }}) - [ {{ $subject->code }} ] {{ $subject->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="term" class="m-0 font-weight-bold text-primary">Instructor</label>
                                    <select name="instructor_id" class="form-control" id="instructor">
                                        <option value="">- select instructor -</option>
                                        @if ($instructors)
                                            @foreach ($instructors as $instructor)
                                                <option value="{{ $instructor->id }}">{{ $instructor->last_name.', '.$instructor->first_name.' '.$instructor->middle_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>                                            
                                </div>
                            </div>
                            <div class="row  align-items-end">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="code"  class="m-0 font-weight-bold text-primary">* Grade</label>
                                        <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="code"  class="m-0 font-weight-bold text-primary">C. G.</label>
                                        <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="code"  class="m-0 font-weight-bold text-primary">* Units</label>
                                        <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right" id="button_group">
                                        <button type="submit" id="save_class" class="btn btn-success btn-icon-split mb-2" disabled>
                                            <span class="icon text-white-50">
                                                <i class="fas fa-save"></i>
                                            </span>
                                            <span class="text">Save Changes</span>
                                        </button>
                                        <button type="button" id="edit" class="btn btn-primary btn-icon-split actions mb-2" disabled>
                                            <span class="icon text-white-50">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            <span class="text">Edit</span>
                                        </button>
                                        <button type="button" id="delete" class="btn btn-danger btn-icon-split actions mb-2" disabled>
                                            <span class="icon text-white-50">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span class="text">Delete</span>
                                        </button>
                                        <button type="button" id="cancel" class="btn btn-danger btn-icon-split mb-2">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-times"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </button>
                                        <button type="button" class="btn btn-success btn-icon-split mb-2" id="add_subjects">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus-square"></i>
                                            </span>
                                            <span class="text">Add Multiple Subjects</span>
                                        </button>
                                    </div>                                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Student's Internal Grade File</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                <thead class="">
                                    <tr>
                                        <th class="w30"></th>
                                        <th class="w20"></th>
                                        <th class="w170">Section</th>
                                        <th class="w50">Class</th>
                                        <th class="w150">Subject Code</th>
                                        <th class="">Description</th>
                                        <th class="w80">Grade</th>
                                        <th class="w50">C. G.</th>
                                        <th class="w50">Units</th>
                                        <th class="w120">Remark</th>
                                        <th class="w170">Instructor</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black" id="return_enrolled_subjects">
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