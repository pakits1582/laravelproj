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
                                <form method="POST" action="{{ route('classes.store') }}"  role="form" id="form_enrollment">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">* Enrollment No.</label>
                                                <input type="text" id="enrollment_id" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="new"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3"><input type="checkbox" class="" name="new" value="1" id="new"> New</label>
                                                <label for="old"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3"><input type="checkbox" class="" name="old" value="1" id="old"> Old</label>
                                                <label for="returnee"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3"><input type="checkbox" class="" name="returnee" value="1" id="returnee"> Returnee</label>
                                                <label for="transferee"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3"><input type="checkbox" class="" name="transferee" value="1" id="transferee"> Transferee</label>
                                                <label for="cross"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3"><input type="checkbox" class="" name="cross_enrollee" value="1" id="cross"> Cross Enrollee</label>
                                                <label for="foreigner"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3"><input type="checkbox" class="" name="foreigner" value="1" id="foreigner"> Foreigner</label>
                                                <label for="probationary"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3"><input type="checkbox" class="" name="probationary" value="1" id="probationary"> Probationary</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">Units Allowed</label>
                                                <input type="text" id="units_allowed" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">* Student</label>
                                                        <select name="student_id" class="form-control select clearable" id="student">
                                                            <option value="">- search student -</option>
                                                            {{-- @if ($students)
                                                                @foreach ($students as $student)
                                                                    <option value="{{ $student->id }}">{{ $student->user->idno }} - {{ $student->name }}</option>
                                                                @endforeach
                                                            @endif --}}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">Level</label>
                                                        <input type="text" id="educational_level" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                                    </div>
                                                </div>
                                               
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">* Program</label>
                                                        @include('partials.programs.dropdown', ['fieldname' => 'program_id', 'fieldid' => 'program'])   
                                                    </div>                                       
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">College</label>
                                                        <input type="text" id="college" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                                    </div>
                                                </div>
                                              

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="term" class="m-0 font-weight-bold text-primary">* Curriculum</label>
                                                        <select name="curriculum_id" class="form-control select clearable" id="curriculum">
                                                            <option value="">- select curriculum -</option>
                                                        
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
                                                        <select name="section_id" class="form-control" id="section">
                                                            <option value="">- select section -</option>
                                                        </select>        
                                                    </div>                                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="code"  class="m-0 font-weight-bold text-primary">Deficiencies</label>
                                            <div id="deficiencies" class="border border-primary h-100"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group" id="button_group">
                                            <button type="submit" id="save_enrollment" class="btn btn-success btn-icon-splitactions mb-2" disabled>
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-save"></i>
                                                </span>
                                                <span class="text">Save Enrollment</span>
                                            </button>
                                            <button type="button" id="add_subjects" class="btn btn-primary btn-icon-split actions mb-2" disabled>
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-plus-square"></i>
                                                </span>
                                                <span class="text">(F2) Add Subjects</span>
                                            </button>
                                            <button type="button" id="delete_selected" class="btn btn-danger btn-icon-split actions mb-2" disabled>
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                <span class="text">Delete Selected</span>
                                            </button>
                                            <button type="button" id="delete_all" class="btn btn-danger btn-icon-split actions mb-2" disabled>
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
        {{-- DISPLAY SCHEDULE TABLE --}}
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Schedule Table</h6>
                    </div>
                    <div class="card-body">
                        <div id="schedule_table">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection