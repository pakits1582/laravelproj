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
                <h1 class="h3 text-800 text-primary mb-0">Assessment Fees Setup <span id="period_name">{{ session('periodname') }}</span></h1>
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
                                <form method="POST" action=""  role="form" id="form_setup_fee" class="save_setupfee_form">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="period"  class="m-0 font-weight-bold text-primary">* Period</label>
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
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="new"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="hidden" value="0" name="new" >
                                                    <input type="checkbox" class="checkbox" name="new" value="1" id="new"> New</label>
                                                <label for="old"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="hidden" value="0" name="old" >
                                                    <input type="checkbox" class="checkbox" name="old" value="1" id="old"> Old</label>
                                                <label for="returnee"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="hidden" value="0" name="returnee" >
                                                    <input type="checkbox" class="checkbox" name="returnee" value="1" id="returnee"> Returnee</label>
                                                <label for="transferee"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="hidden" value="0" name="transferee" >
                                                    <input type="checkbox" class="checkbox" name="transferee" value="1" id="transferee"> Transferee</label>
                                                <label for="cross"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="hidden" value="0" name="cross_enrollee" >
                                                    <input type="checkbox" class="checkbox" name="cross_enrollee" value="1" id="cross"> Cross Enrollee</label>
                                                <label for="foreigner"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="hidden" value="0" name="foreigner" >
                                                    <input type="checkbox" class="checkbox" name="foreigner" value="1" id="foreigner"> Foreigner</label>
                                                <label for="professional"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="hidden" value="0" name="professional" >
                                                    <input type="checkbox" class="checkbox" name="professional" value="1" id="professional"> Professional</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="educational_level_id" class="m-0 font-weight-bold text-primary">* Level</label>
                                                @include('partials.educlevels.dropdown',['fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id'])
                                                <div id="error_educational_level_id"  class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                                @include('partials.colleges.dropdown', ['fieldname' => 'college_id', 'fieldid' => 'college_id', 'fieldclass' => 'clearable'])
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
                                                <select name="year_level" class="form-control clearable" id="year_level">
                                                    <option value="">- select year -</option>
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
                                                <select name="sex" class="form-control clearable" id="sex">
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
                                                <select name="fee_id" class="form-control select clearable" id="fee" required>
                                                    <option value="">- select fee -</option>
                                                    @if ($fees)
                                                        @foreach ($fees as $fee)
                                                            <option value="{{ $fee->id }}">{{ $fee->code }} - {{ $fee->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_fee_id" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="rate" class="m-0 font-weight-bold text-primary">* Fee Rate</label>
                                                <input type="text" name="rate" id="rate" placeholder="0.00" required class="form-control text-uppercase clearable" value="">
                                                <div id="error_rate" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="payment_scheme" class="m-0 font-weight-bold text-primary">* Payment Scheme</label>
                                                <select name="payment_scheme" class="form-control" required id="payment_scheme">
                                                    <option value="1">Fixed</option>
                                                    <option value="2">Per Units</option>
                                                    <option value="3">Per Subject</option>
                                                </select>
                                                <div id="error_payment_scheme" class="errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group" id="button_group">
                                                <button type="submit" id="save_setup_fee" class="btn btn-success btn-icon-split mb-2">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-save"></i>
                                                    </span>
                                                    <span class="text">Save</span>
                                                </button>
                                                <button type="button" id="edit" class="btn btn-primary btn-icon-split actions mb-2" disabled>
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <span class="text">Edit</span>
                                                </button>
                                                <button type="button" id="delete_selected" class="btn btn-danger btn-icon-split actions mb-2" disabled>
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                    <span class="text">Delete</span>
                                                </button>
                                                <button type="button" id="cancel" class="btn btn-danger btn-icon-split mb-2">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                    <span class="text">Cancel</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-7 right">
                                            <div class="form-group" id="button_group">
                                                <button type="button" id="print_setup_summary" class="btn btn-primary btn-icon-split mb-2">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-print"></i>
                                                    </span>
                                                    <span class="text">Print Setup Summary</span>
                                                </button>
                                                <button type="button" id="print_subject_fees" class="btn btn-primary btn-icon-split mb-2">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-print"></i>
                                                    </span>
                                                    <span class="text">Print Subject Fees</span>
                                                </button>
                                                <button type="button" id="copy_setup" class="btn btn-primary btn-icon-split mb-2">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-copy"></i>
                                                    </span>
                                                    <span class="text">Copy Setup</span>
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
                        <h6 class="m-0 font-weight-bold text-primary">Assessment Fees</h6>
                    </div>
                    <div class="card-body">
                        <div id="return_setup_fees">
                            @include('fee.setup.return_setupfees')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection