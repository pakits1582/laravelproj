@extends('layout')
@section('title') {{ 'Student\'s Schedule' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Student's Schedule</h1>
        <p class="mb-4">Student's enrolled subjects and schedule table</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row  align-items-center">
                    <div class="col-md-8">
                        <h6 class="font-weight-bold text-primary mb-0">Student's Schedule <span id="period_name">{{ session('periodname') }}</span></h6>
                    </div>
                    <div class="col-md-4 right">
                        <div class="m-0 font-weight-bold" id="status"></div>
                    </div>
                </div>
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
                                <form method="POST" action=""  role="form" id="form_studentschedule">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">* Assessment No.</label>
                                                <input type="text" name="assessment_id" id="assessment_id" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">* Enrollment No.</label>
                                                <input type="text" name="enrollment_id" id="enrollment_id" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="new"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="checkbox" class="checkbox" name="new" value="1" id="new" disabled> New</label>
                                                <label for="old"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="checkbox" class="checkbox" name="old" value="1" id="old" disabled> Old</label>
                                                <label for="returnee"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="checkbox" class="checkbox" name="returnee" value="1" id="returnee" disabled> Returnee</label>
                                                <label for="transferee"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="checkbox" class="checkbox" name="transferee" value="1" id="transferee" disabled> Transferee</label>
                                                <label for="cross"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="checkbox" class="checkbox" name="cross_enrollee" value="1" id="cross" disabled> Cross Enrollee</label>
                                                <label for="foreigner"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="checkbox" class="checkbox" name="foreigner" value="1" id="foreigner" disabled> Foreigner</label>
                                                <label for="probationary"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="checkbox" class="checkbox" name="probationary" value="1" id="probationary" disabled> Probationary</label>                                            
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">* Student</label>
                                                <select name="student_id" class="form-control select clearable" id="student">
                                                    <option value="">- search student -</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Level</label>
                                                <input type="text" id="educational_level" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">College</label>
                                                <input type="text" id="college" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Enrollment Date</label>
                                                <input type="text" id="enrollment_date" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Program</label>
                                                <input type="text" id="program" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Curriculum</label>
                                                <input type="text" id="curriculum" class="form-control text-uppercase clearable" readonly value="" placeholder="">

                                            </div>                                         
                                        </div> 
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">Year Level</label>
                                                <input type="text" id="year_level" class="form-control text-uppercase clearable" readonly value="" placeholder="">

                                            </div>                                 
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">Section</label>
                                                <input type="text" id="section" class="form-control text-uppercase clearable" readonly value="" placeholder="">

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
                        <h6 class="m-0 font-weight-bold text-primary">Registered Subjects</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                <thead class="">
                                    <tr>
                                        <th class="w50">Code</th>
                                        <th class="w120 mid">Subject</th>
                                        <th>Description</th>
                                        <th class="w40 mid">Units</th>
                                        <th class="w35 mid">Lec</th>
                                        <th class="w35 mid">Lab</th>
                                        <th class="w300 mid">Schedule</th>
                                        <th class="w100">Section</th>
                                        <th class="w100">Added By</th>
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