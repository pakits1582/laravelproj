@extends('layout')
@section('title') {{ 'Add New Instructor' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
              <div class="col-lg-12 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <h1 class="h3 mb-0 text-primary font-weight-bold">Add New Student</h1>
                    <p class="mb-2">Add new record in the database</p>
                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('students.store') }}"  role="form">
                        @csrf
                        <div class="form-group">
                            <label for="idno"  class="m-0 font-weight-bold text-primary">* ID Number</label>
                            <input type="text" name="idno" placeholder="" class="form-control text-uppercase" value="{{ old('idno') }}">
                            @error('idno')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="m-0 font-weight-bold text-primary">* Last Name</label>
                                        <input type="text" name="last_name" placeholder="" class="form-control text-uppercase" value="{{ old('last_name') }}">
                                        @error('last_name')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="m-0 font-weight-bold text-primary">* First Name</label>
                                        <input type="text" name="first_name" placeholder="" class="form-control text-uppercase" value="{{ old('first_name') }}">
                                        @error('first_name')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_suffix" class="m-0 font-weight-bold text-primary">Name Suffix</label>
                                        <select name="name_suffix" class="form-control">
                                            <option value="">- select suffix -</option>
                                            <option value="JR" {{ (old('name_suffix') == 'JR') ? 'selected' : '' }}>JR</option>
                                            <option value="SR" {{ (old('name_suffix') == 'SR') ? 'selected' : '' }}>SR</option>
                                            @for ($x = 1; $x<=15; $x++)
                                                <option value="{{ $x }}" {{ (old('name_suffix') == $x) ? 'selected' : '' }}>{{ $x }}</option>
                                            @endfor
                                        </select>
                                        @error('name_suffix')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="middle_name" class="m-0 font-weight-bold text-primary">Middle Name</label>
                                        <input type="text" name="middle_name" placeholder="" class="form-control text-uppercase" value="{{ old('middle_name') }}">
                                        @error('middle_name')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1">
                                    <label for=""  class="m-0 font-weight-bold text-primary">* Sex</label>
                                </div>
                                <div class="col-md-11">
                                    <label for="male"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="sex" value="1" id="male" checked> Male </label>
                                    <label for="female"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="sex" value="2" id="female"> Female </label>    
                                </div>
                            </div>
                            @error('sex')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="program" class="m-0 font-weight-bold text-primary">* Program</label>
                            @include('partials.programs.dropdown', ['fieldname' => 'program_id', 'fieldid' => 'program_id'])
                        </div>
                        <div class="form-group">
                            <label for="curriculum" class="m-0 font-weight-bold text-primary">* Curriculum</label>
                            <select name="curriculum_id" class="form-control" id="curriculum" disabled>
                                <option value="">- select curriculum -</option>
                            </select>
                            @error('curriculum_id')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="year_level" class="m-0 font-weight-bold text-primary">* Year Level</label>
                                        <select name="year_level" class="form-control">
                                            <option value="">- select year level -</option>
                                            <option value="1" {{ (old('year_level') == 1) ? 'selected' : '' }}>First Year</option>
                                            <option value="2" {{ (old('year_level') == 2) ? 'selected' : '' }}>Second Year</option>
                                            <option value="3" {{ (old('year_level') == 3) ? 'selected' : '' }}>Third Year</option>
                                            <option value="4" {{ (old('year_level') == 4) ? 'selected' : '' }}>Fourth Year</option>
                                            <option value="5" {{ (old('year_level') == 5) ? 'selected' : '' }}>Fifth Year</option>
                                            <option value="6" {{ (old('year_level') == 6) ? 'selected' : '' }}>Sixth Year</option>
                                        </select>
                                        @error('year_level')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="academic_status" class="m-0 font-weight-bold text-primary">* Academic Status</label>
                                        <select name="academic_status" class="form-control">
                                            <option value="">- select status -</option>
                                            <option value="1" {{ (old('academic_status') == 1) ? 'selected' : '' }}>Old</option>
                                            <option value="2" {{ (old('academic_status') == 2) ? 'selected' : '' }}>New</option>
                                            <option value="3" {{ (old('academic_status') == 3) ? 'selected' : '' }}>Graduated</option>
                                            <option value="4" {{ (old('academic_status') == 4) ? 'selected' : '' }}>Expelled</option>
                                        </select>
                                        @error('academic_status')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Student</button>
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