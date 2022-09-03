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
                    <h1 class="h3 mb-0 text-primary font-weight-bold">Add New Instructor</h1>
                    <p class="mb-2">Add new record in the database</p>
                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('instructors.store') }}"  role="form">
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
                                        <label for="name_prefix" class="m-0 font-weight-bold text-primary">Name Prefix</label>
                                        <input type="text" name="name_prefix" placeholder="" class="form-control text-uppercase" value="{{ old('name_prefix') }}">
                                        @error('name_prefix')
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
                                        <label for="middle_name" class="m-0 font-weight-bold text-primary">Middle Name</label>
                                        <input type="text" name="middle_name" placeholder="" class="form-control text-uppercase" value="{{ old('middle_name') }}">
                                        @error('middle_name')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
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
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="m-0 font-weight-bold text-primary">* Last Name</label>
                            <input type="text" name="last_name" placeholder="" class="form-control text-uppercase" value="{{ old('last_name') }}">
                            @error('last_name')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="college" class="m-0 font-weight-bold text-primary">* College</label>
                                        @include('partials.colleges.dropdown')
                                        @error('college')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="educational_level" class="m-0 font-weight-bold text-primary">* Level</label>
                                        @include('partials.educlevels.dropdown')
                                        @error('educational_level')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="department" class="m-0 font-weight-bold text-primary">Department</label>
                                        @include('partials.departments.dropdown')
                                        @error('department')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="designation" class="m-0 font-weight-bold text-primary">* Designation</label>
                                        <select name="designation" class="form-control">
                                            <option value="">- select designation -</option>
                                            <option value="1" {{ (old('designation') == 1) ? 'selected' : '' }}>Teacher</option>
                                            <option value="2" {{ (old('designation') == 2) ? 'selected' : '' }}>Program Head</option>
                                            <option value="3" {{ (old('designation') == 3) ? 'selected' : '' }}>Department Head</option>
                                            <option value="4" {{ (old('designation') == 4) ? 'selected' : '' }}>Dean</option>
                                            <option value="5" {{ (old('designation') == 5) ? 'selected' : '' }}>Professor</option>
                                            <option value="6" {{ (old('designation') == 6) ? 'selected' : '' }}>Others</option>
                                        </select>
                                        @error('designation')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                              </div>
                        </div>
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Instructor</button>
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