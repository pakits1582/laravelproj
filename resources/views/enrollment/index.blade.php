@extends('layout')
@section('title') {{ 'Enrolment' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Enrollment</h1>
        <p class="mb-4">Student enrolment/registration form.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Enrollment {{ session('periodname') }}</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" action="{{ route('classes.store') }}"  role="form" id="form_classoffering">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="code"  class="m-0 font-weight-bold text-primary">* Enrollment No.</label>
                                                        <input type="text" id="subject_code" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                                        <div id="error_curriculum_id"></div>
                                                        @error('curriculum_id')
                                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    
                                                </div>
                                                <div class="col-md-5">
                                                    
                                                </div>
                                                
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">* Student</label>
                                                        <select name="instructor_id" class="form-control select clearable" id="instructor">
                                                            <option value="">- select instructor -</option>
                                                        
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">Level</label>
                                                        <input type="text" id="subject_name" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                                    </div>
                                                </div>
                                               
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">* Program</label>
                                                        <select name="instructor_id" class="form-control select clearable" id="instructor">
                                                            <option value="">- select instructor -</option>
                                                        
                                                        </select>     
                                                    </div>                                       
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">College</label>
                                                        <input type="text" id="subject_name" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                                    </div>
                                                </div>
                                              

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">* Curriculum</label>
                                                        <select name="instructor_id" class="form-control select clearable" id="instructor">
                                                            <option value="">- select instructor -</option>
                                                        
                                                        </select>   
                                                    </div>                                         
                                                </div> 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="code"  class="m-0 font-weight-bold text-primary">* Year Level</label>
                                                        <select name="year_level" class="form-control" id="year_level">
                                                            <option value="">- select year -</option>
                                                            <option value="1">First Year</option>
                                                            <option value="2">Second Year</option>
                                                            <option value="3">Third Year</option>
                                                            <option value="4">Fourth Year</option>
                                                            <option value="5">Fifth Year</option>
                                                            <option value="6">Sixth Year</option>
                                                        </select>      
                                                    </div>                                 
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="code"  class="m-0 font-weight-bold text-primary">* Section</label>
                                                        <select name="section" class="form-control" id="section">
                                                            <option value="">- select section -</option>
                                                        </select>        
                                                    </div>                                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                    
                                                <label for="code"  class="m-0 font-weight-bold text-primary">Deficiencies</label>
                                            
                                            <div id="deficiencies" class="border border-primary h-100">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group" id="button_group">
                                            <button type="submit" id="save_class" class="btn btn-success btn-icon-split mb-2" disabled>
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-save"></i>
                                                </span>
                                                <span class="text">Save Enrollment</span>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-icon-split mb-2" id="add_subjects">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-plus-square"></i>
                                                </span>
                                                <span class="text">(F2) Add Subjects</span>
                                            </button>
                                            <button type="button" id="delete" class="btn btn-danger btn-icon-split actions mb-2" disabled>
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                <span class="text">Delete Selected</span>
                                            </button>
                                            <button type="button" id="delete" class="btn btn-danger btn-icon-split actions mb-2" disabled>
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                <span class="text">Delete All Subjects</span>
                                            </button>
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
                        <h6 class="m-0 font-weight-bold text-primary">Registered Subjects</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                <thead class="">
                                    <tr>
                                        <th class="w20"></th>
                                        <th class="w50">Code</th>
                                        <th class="w120 mid">Subject</th>
                                        <th>Description</th>
                                        <th class="w40 mid">Units</th>
                                        <th class="w35 mid">Lec</th>
                                        <th class="w35 mid">Lab</th>
                                        <th class="w300 mid">Schedule</th>
                                        <th class="">Section</th>
                                        <th class="">Added By</th>
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
        {{-- DISPLAY CURRICULUM HERE --}}
        <div id="schedule_table">
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection