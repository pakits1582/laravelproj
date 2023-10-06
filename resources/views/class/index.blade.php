@extends('layout')
@section('title') {{ 'Class Offering Scheduling' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Class Offering Scheduling</h1>
        <p class="mb-4">Manage subjects offered for the class for the current period.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="font-weight-bold text-primary mb-0">Class Offering Scheduling <span id="period_name">{{ session('periodname') }}</span></h6>
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
                                <form method="POST" action=""  role="form" id="form_classoffering">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">* Program</label>
                                                @include('partials.programs.dropdown', ['fieldname' => 'program_id', 'fieldid' => 'program_id'])
                                                <div id="error_curriculum_id"></div>
                                                @error('curriculum_id')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="col-md-2">
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
                                            <div class="col-md-2">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">* Section</label>
                                                <select name="section" class="form-control" id="section">
                                                    <option value="">- select section -</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Subject Code</label>
                                                <input type="text" id="subject_code" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                            <div class="col-md-8">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Subject Description</label>
                                                <input type="text" id="subject_name" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row align-items-end">
                                            <div class="col-md-1">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Credited</label>
                                                <input type="text" name="units" id="units" class="form-control clearable" value="" placeholder="Units">
                                                <div id="error_units" class="errors"></div>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Tuition</label>
                                                <input type="text" name="tfunits" id="tfunits" class="form-control clearable" value="" placeholder="Units">
                                                <div id="error_tfunits" class="errors"></div>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Load</label>
                                                <input type="text" name="loadunits" id="loadunits" class="form-control clearable" value="" placeholder="Units">
                                                <div id="error_loadunits" class="errors"></div>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Lecture</label>
                                                <input type="text" name="lecunits" id="lecunits" class="form-control clearable" value="" placeholder="Units">
                                                <div id="error_lecunits" class="errors"></div>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Lab</label>
                                                <input type="text" name="labunits" id="labunits" class="form-control clearable" value="" placeholder="Units">
                                                <div id="error_labunits" class="errors"></div>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Hours</label>
                                                <input type="text" name="hours" id="hours" class="form-control clearable" value="" placeholder="Hours">
                                                <div id="error_hours" class="errors"></div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-check form-check-solid">
                                                    <input type="hidden" value="0" name="dissolved" >
                                                    <input class="form-check-input" id="dissolved" type="checkbox" value="1" name="dissolved" >
                                                    <label for="dissolved" class="m-0 font-weight-bold text-primary">Dissolved</label>            
                                                </div>        
                                            </div>
                                            <div class="col-md-1">
                                                <div class="legend dissolved"></div>            
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-check form-check-solid">
                                                    <input type="hidden" value="0" name="tutorial" >
                                                    <input class="form-check-input" id="tutorial" type="checkbox" value="1" name="tutorial" >
                                                    <label for="tutorial" class="m-0 font-weight-bold text-primary">Tutorial</label>         
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="legend tutorial"></div>   
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-check form-check-solid">
                                                    <input type="hidden" value="0" name="f2f" >
                                                    <input class="form-check-input" id="f2f" type="checkbox" value="1" name="f2f" >
                                                    <label for="f2f" class="m-0 font-weight-bold text-primary">F2F</label>                    
                                                </div>  
                                            </div>
                                            <div class="col-md-1">
                                                <div class="legend f2f"></div>        
                                            </div>
                                            {{-- <div class="col-md-1">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Search</label>
                                                <input type="text" id="findclass" class="form-control" value="" placeholder="Code">
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Instructor</label>
                                                <select name="instructor_id" class="form-control select clearable" id="instructor">
                                                    <option value="">- select instructor -</option>
                                                    @if ($instructors)
                                                        @foreach ($instructors as $instructor)
                                                            <option value="{{ $instructor->id }}">{{ $instructor->last_name.', '.$instructor->first_name.' '.$instructor->middle_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>                                            
                                            </div>
                                            <div class="col-md-6">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Schedule</label>
                                                <input type="text" name="schedule" id="schedule" class="form-control text-uppercase uppercase clearable" value="" placeholder="(time AM|PM-time AM|PM) (DAYS) (room), (time AM|PM-time AM|PM) (DAYS) (room)">
                                            </div>
                                            <div class="col-md-1">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Slots</label>
                                                <input type="text" name="slots" id="slots" class="form-control clearable" value="" placeholder="">
                                                <div id="error_slots" class="errors"></div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-check form-check-solid">
                                                    <input type="hidden" value="0" name="isprof" >
                                                    <input class="form-check-input" id="isprof" type="checkbox" value="1" name="isprof" >
                                                    <label for="isprof" class="m-0 font-weight-bold text-primary">Is_Prof</label>                    
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mid d-none" id="button_group">
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
                                            <span class="text">(F2) Edit</span>
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
                                        <button type="button" id="generatecode" class="btn btn-primary btn-icon-split mb-2">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-retweet"></i>
                                            </span>
                                            <span class="text">Generate Code</span>
                                        </button>
                                        <button type="button" id="copy_class" class="btn btn-primary btn-icon-split mb-2">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-copy"></i>
                                            </span>
                                            <span class="text">Copy Class</span>
                                        </button>
                                        <button type="button" id="display_enrolled" class="btn btn-primary btn-icon-split mb-2">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                            <span class="text">Display Enrolled</span>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-icon-split mb-2">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-print"></i>
                                            </span>
                                            <span class="text">Print Class</span>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-icon-split mb-2">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-print"></i>
                                            </span>
                                            <span class="text">Print All Class of Course</span>
                                        </button>
                                        <button type="button" class="btn btn-success btn-icon-split mb-2" id="add_subjects">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus-square"></i>
                                            </span>
                                            <span class="text">Add Subjects</span>
                                        </button>
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
                        <h6 class="m-0 font-weight-bold text-primary">Section Subjects Offering</h6>
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
                                        <th class="w35 mid">TF</th>
                                        <th class="w50 mid">Load</th>
                                        <th class="w35 mid">Lec</th>
                                        <th class="w35 mid">Lab</th>
                                        <th class="w35 mid">Hrs</th>
                                        <th class="w150 mid">Instructor</th>
                                        <th class="w250 mid">Schedule</th>
                                        <th class="w40">Slots</th>
                                        <th class="">Curr</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black" id="return_classsubjects">
                                    <tr><td class="mid" colspan="14">No records to be displayed!</td></tr>
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