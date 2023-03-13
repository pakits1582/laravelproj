@extends('layout')
@section('title') {{ 'Add New Scholarship Discount' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
              <div class="col-lg-12 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <h1 class="h3 mb-0 text-primary font-weight-bold">Add New Scholarship Discount</h1>
                    <p class="mb-2">Add new record in the database</p>
                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                        <form method="POST" action="{{ route('scholarshipdiscounts.store') }}"  role="form">
                        @csrf
                        <div class="form-group">
                            <label for="code"  class="m-0 font-weight-bold text-primary">* Code</label>
                            <input type="text" name="code" placeholder="" class="form-control text-uppercase" maxlength="50" id="code" value="{{ old('code') }}" required>
                            @error('code')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="code"  class="m-0 font-weight-bold text-primary">* Description</label>
                            <input type="text" name="description" placeholder="" class="form-control text-uppercase" id="description" value="{{ old('description') }}" required>
                            @error('description')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year" class="m-0 font-weight-bold text-primary">Tuition Fees</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="rate_tuition"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="tuition_type" value="rate" id="rate_tuition" checked> Rate (%) </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="rate_fixed"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="tuition_type" value="fixed" id="fixed_tuition"> Fixed Amount </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" name="tuition" placeholder="" class="form-control text-uppercase" id="tuition" value="{{ old('tuition') }}" pattern="^[0-9]+(?:\.[0-9]{1,2})?$">
                                        @error('tuition')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year" class="m-0 font-weight-bold text-primary">Miscellaneous Fees</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="_tuimiscellaneoustion"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="miscellaneous_type" value="rate" id="miscellaneous_tuition" checked> Rate (%) </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="miscellaneous_fixed"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="miscellaneous_type" value="fixed" id="miscellaneous_tuition"> Fixed Amount </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" name="miscellaneous" placeholder="" class="form-control text-uppercase" id="miscellaneous" value="{{ old('miscellaneous') }}" pattern="^[0-9]+(?:\.[0-9]{1,2})?$">
                                        @error('miscellaneous')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year" class="m-0 font-weight-bold text-primary">Other Miscellaneous</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="othermisc_tuition"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="othermisc_type" value="rate" id="othermisc_tuition" checked> Rate (%) </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="othermisc_fixed"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="othermisc_type" value="fixed" id="othermisc_tuition"> Fixed Amount </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" name="othermisc" placeholder="" class="form-control text-uppercase" id="othermisc" value="{{ old('othermisc') }}" pattern="^[0-9]+(?:\.[0-9]{1,2})?$">
                                        @error('othermisc')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year" class="m-0 font-weight-bold text-primary">Laboratory Fees</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="laboratory_tuition"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="laboratory_type" value="rate" id="laboratory_tuition" checked> Rate (%) </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="laboratory_fixed"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="laboratory_type" value="fixed" id="laboratory_tuition"> Fixed Amount </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" name="laboratory" placeholder="" class="form-control text-uppercase" id="laboratory" value="{{ old('laboratory') }}" pattern="^[0-9]+(?:\.[0-9]{1,2})?$">
                                        @error('laboratory')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year" class="m-0 font-weight-bold text-primary">Total Assessment</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="totalassessment_tuition"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="totalassessment_type" value="rate" id="totalassessment_tuition" checked> Rate (%) </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="totalassessment_fixed"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="totalassessment_type" value="fixed" id="totalassessment_tuition"> Fixed Amount </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" name="totalassessment" placeholder="" class="form-control text-uppercase" id="totalassessment" value="{{ old('totalassessment') }}" pattern="^[0-9]+(?:\.[0-9]{1,2})?$">
                                        @error('totalassessment')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="code"  class="m-0 font-weight-bold text-primary">* Type</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="scholarship"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="type" value="1" id="scholarship" checked> Scholarship </label>
                                </div>
                                <div class="col-md-7">
                                    <label for="discount"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="type" value="2" id="discount"> Discount </label>
                                </div>
                            </div>
                            @error('type')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Scholarship Discount</button>
                      </form>
                    </div>
                    <!-- End -->
                </div>
              </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection