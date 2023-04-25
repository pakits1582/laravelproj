@extends('layout')
@section('title') {{ 'Post Charges' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Post Charges</h1>
        <p class="mb-4">Manage student's post charges.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Post Charge <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <p class="font-italic text-info">Note: You can filter students to be displayed by using the form below.</p>
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" action=""  role="form" id="form_filterstudent">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">* Period</label>
                                                <select name="period_id" class="form-control" id="period" required>
                                                    @if ($periods)
                                                        @foreach ($periods as $period)
                                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_period_id" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Program</label>
                                                <select name="program_id" class="form-control" id="program">
                                                    <option value="">- select program -</option>
                                                    @if ($programs)
                                                        @foreach ($programs as $program)
                                                            <option value="{{ $program->id }}">{{ $program->code }} - {{ $program->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_program" class="errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Year Level</label>
                                                <select name="year_level" class="form-control" id="year_level">
                                                    <option value="">- select year level -</option>
                                                    <option value="1">First Year</option>
                                                    <option value="2">Second Year</option>
                                                    <option value="3">Third Year</option>
                                                    <option value="4">Fourth Year</option>
                                                    <option value="5">Fifth Year</option>
                                                </select>
                                                <div id="error_year_level" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Section</label>
                                                <select name="section_id" class="form-control" id="section">
                                                    <option value="">- select section -</option>
                                                    {{-- @if ($scholarshipdiscounts)
                                                        @foreach ($scholarshipdiscounts as $scholarshipdiscount)
                                                            <option value="{{ $scholarshipdiscount->id }}">{{ $scholarshipdiscount->description }}</option>
                                                        @endforeach
                                                    @endif --}}
                                                </select>
                                                <div id="error_section" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Class Subject</label>
                                                <select name="class_id" class="form-control" id="class">
                                                    <option value="">- select class subject -</option>
                                                    @if ($classes)
                                                        @foreach ($classes as $class)
                                                            <option value="{{ $class->id }}">{{ $class->class_code }} - {{ $class->subject_code }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_class" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">ID Number</label>
                                                <input type="text" name="idno" id="idno" class="form-control">
                                                <div id="error_idno" class="errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-end">
                                        <div class="col-md-6 right">
                                            <div class="form-group right" id="button_group">
                                                {{-- <button type="submit" id="save_grant" class="btn btn-success btn-icon-split actions" disabled>
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-save"></i>
                                                    </span>
                                                    <span class="text">Save Scholarship/Discount Grant</span>
                                                </button> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-6 right">
                                            <div class="form-group right" id="button_group">
                                                <button type="submit" id="filter_student" class="btn btn-success btn-icon-split actions">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-save"></i>
                                                    </span>
                                                    <span class="text">Filter Student</span>
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
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Manage Post Charge</h6>
                    </div>
                    <div class="card-body" id="">
                        <form method="POST" action=""  role="form" id="form_postcharge">
                            @csrf
                            <div class="row px-2">
                                <div class="col-md-6 p-0">
                                    <div class="form-group">
                                        <h6 class="m-0 font-weight-bold text-primary">Student List</h6>
                                        <p class="font-italic text-info text-small">Note: Click on checkbox to select student to be charged or removed.</p>
                                        <div class="col-xs-8 col-xs-offset-2 well">
                                            <table class="table table-scroll table-sm table-striped table-bordered text-medium" style="">
                                                <thead>
                                                    <tr>
                                                        <th class="w30 mid"><input type="checkbox" name="" id="checkallcheckbox"></th>
                                                        <th class="w50">#</th>
                                                        <th class="w100">ID No.</th>
                                                        <th>Student Name</th>
                                                        <th class="w100">Crs & Yr</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="return_filteredstudents" class="border"  style="height: 490px;">
                                                    <tr>
                                                        <td class="mid" colspan="5">No records to be displayed</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="form_container">
                                        @include('postcharge.create')
                                    </div>
                                    <div id="form_container">
                                        @include('postcharge.remove')
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
    <!-- /.container-fluid -->
@endsection