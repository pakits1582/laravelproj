@extends('layout')
@section('title') {{ 'Assessment Fees Setup' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Assessment Fees Setup</h1>
        <p class="mb-4">Setup semestral assessment fees.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">{{ session('periodname') }}</h1>
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
                                <form method="POST" action=""  role="form" id="form_setup_fees">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">* Period</label>
                                                <select name="student_id" class="form-control select clearable" id="student">
                                                    <option value="">- select period -</option>
                                                    @if ($periods)
                                                        @foreach ($periods as $period)
                                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
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
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="educational_level_id" class="m-0 font-weight-bold text-primary">* Level</label>
                                                @include('partials.educlevels.dropdown',['fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id'])
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="college" class="m-0 font-weight-bold text-primary">* College</label>
                                                @include('partials.colleges.dropdown', ['fieldname' => 'college_id', 'fieldid' => 'college_id'])
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="subject" class="m-0 font-weight-bold text-primary">Subject</label>
                                                <select name="subject_id" class="form-control select clearable" id="subject">
                                                    <option value="">- search subject -</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="program" class="m-0 font-weight-bold text-primary">Program</label>
                                                <select name="program_id" class="form-control select clearable" id="program">
                                                    <option value="">- select program -</option>
                                                    @if ($programs)
                                                        @foreach ($programs as $program)
                                                            <option value="{{ $program->id }}">{{ $program->code }} - {{ $program->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="year_level" class="m-0 font-weight-bold text-primary">Year Level</label>
                                                <select name="year_level" class="form-control">
                                                    <option value="">- select year level -</option>
                                                    <option value="1" {{ (old('year_level') == 1) ? 'selected' : '' }}>First Year</option>
                                                    <option value="2" {{ (old('year_level') == 2) ? 'selected' : '' }}>Second Year</option>
                                                    <option value="3" {{ (old('year_level') == 3) ? 'selected' : '' }}>Third Year</option>
                                                    <option value="4" {{ (old('year_level') == 4) ? 'selected' : '' }}>Fourth Year</option>
                                                    <option value="5" {{ (old('year_level') == 5) ? 'selected' : '' }}>Fifth Year</option>
                                                    <option value="6" {{ (old('year_level') == 6) ? 'selected' : '' }}>Sixth Year</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="sex" class="m-0 font-weight-bold text-primary">Sex</label>
                                                <select name="sex" class="form-control">
                                                    <option value="">- select sex -</option>
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="fee" class="m-0 font-weight-bold text-primary">* Fee</label>
                                                <select name="fee_id" class="form-control select clearable" id="fee">
                                                    <option value="">- select fee -</option>
                                                    @if ($fees)
                                                        @foreach ($fees as $fee)
                                                            <option value="{{ $fee->id }}">{{ $fee->code }} - {{ $fee->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="amount" class="m-0 font-weight-bold text-primary">* Fee Amount</label>
                                                <input type="text" name="amount" id="amount" placeholder="" class="form-control text-uppercase" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="payment_scheme" class="m-0 font-weight-bold text-primary">* Payment Scheme</label>
                                                <select name="payment_scheme" class="form-control">
                                                    <option value="1">Fixed</option>
                                                    <option value="2">Per Units</option>
                                                    <option value="3">Per Subject</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group" id="button_group">
                                            <button type="submit" id="save_enrollment" class="btn btn-success btn-icon-split actions mb-2" disabled>
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
                                            <button type="button" id="delete_enrollment" class="btn btn-danger btn-icon-split actions mb-2" disabled>
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                <span class="text">Delete Enrollment</span>
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