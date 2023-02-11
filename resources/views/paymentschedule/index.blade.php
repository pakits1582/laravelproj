@extends('layout')
@section('title') {{ 'Payment Schedules' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Payment Schedules</h1>
        <p class="mb-4">List and manage semestral payment schedules.</p>
        
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
                                <form method="POST" action=""  role="form" id="form_payment_schedule" class="save_paymentschedule_form">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="period"  class="m-0 font-weight-bold text-primary">* Period</label>
                                                <select name="period_id" class="form-control" id="period" required>
                                                    <option value="">- select period -</option>
                                                    @if ($periods)
                                                        @foreach ($periods as $period)
                                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_period_id" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payment_mode_id" class="m-0 font-weight-bold text-primary">* Mode of Payment</label>
                                                <select name="payment_mode_id" class="form-control select clearable" id="payment_mode" required>
                                                    <option value="">- select payment mode -</option>
                                                    @if ($payment_modes)
                                                        @foreach ($payment_modes as $payment_mode)
                                                            <option value="{{ $payment_mode->id }}">{{ $payment_mode->mode }}</option>
                                                        @endforeach
                                                    @endif
                                                    <option value="addpaymentmode" id="">- Click to add payment mode -</option>
                                                </select>
                                                <div id="error_payment_mode_id" class="errors"></div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tuition"  class="m-0 font-weight-bold text-primary">Tuition</label>
                                                <input type="text" name="tuition" id="tuition" placeholder="" class="form-control text-uppercase clearable" value="">
                                                <div id="error_tuition" class="errors"></div>
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
                                                <label for="period"  class="m-0 font-weight-bold text-primary">* Description</label>
                                                <input type="text" name="description" id="description" placeholder="" required class="form-control text-uppercase clearable" value="">
                                                <div id="error_description" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="miscellaneous"  class="m-0 font-weight-bold text-primary">Miscellaneous</label>
                                                <input type="text" name="miscellaneous" id="miscellaneous" placeholder="" class="form-control text-uppercase clearable" value="">
                                                <div id="error_miscellaneous" class="errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payment_type"  class="m-0 font-weight-bold text-primary">* Payment Type</label>
                                                <select name="payment_type" class="form-control clearable" id="payment_type">
                                                    <option value="1" {{ (old('payment_type') == 1) ? 'selected' : '' }}>Percentage</option>
                                                    <option value="2" {{ (old('payment_type') == 2) ? 'selected' : '' }}>Fixed</option>
                                                </select>
                                                <div id="error_period_id" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="others"  class="m-0 font-weight-bold text-primary">Others</label>
                                                <input type="text" name="others" id="others" placeholder="" class="form-control text-uppercase clearable" value="">
                                                <div id="error_others" class="errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group" id="button_group">
                                                <button type="submit" id="save_payment_schedule" class="btn btn-success btn-icon-split mb-2">
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
                        <h6 class="m-0 font-weight-bold text-primary">Payment Schedules</h6>
                    </div>
                    <div class="card-body">
                        {{-- <div class="table-responsive-sm">
                            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;"> --}}
                        <div class="table-responsive-sm col-xs-8 col-xs-offset-2 well">
                            <table class="table table-sm table-scroll table-striped table-bordered" style="font-size: 14px;">
                                <thead class="">
                                    <tr>
                                        <th class="w30"></th>
                                        <th class="w150">Level</th>
                                        <th class="w100">Year</th>
                                        <th class="">Mode</th>
                                        <th class="">Description</th>
                                        <th class="w100">Tuition</th>
                                        <th class="w120">Miscellaneous</th>
                                        <th class="w100">Others</th>
                                        <th class="w100">Type</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black" id="return_paymentschedules">
                                    @include('paymentschedule.return_paymentschedule')
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