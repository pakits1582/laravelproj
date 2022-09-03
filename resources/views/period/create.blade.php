@extends('layout')
@section('title') {{ 'Add New Period' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
              <div class="col-lg-12 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <h1 class="h3 mb-0 text-primary font-weight-bold">Add New Period</h1>
                    <p class="mb-2">Add new record in the database</p>
                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                        <form method="POST" action="{{ route('periods.store') }}"  role="form">
                        @csrf
                        <div class="form-group">
                            <label for="code"  class="m-0 font-weight-bold text-primary">* Code</label>
                            <input type="text" name="code" placeholder="" class="form-control text-uppercase" id="code" value="{{ old('code') }}">
                            @error('code')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="year" class="m-0 font-weight-bold text-primary">* Year</label>
                                        <input type="text" name="year" placeholder="" id="year" class="form-control text-uppercase" value="{{ old('year') }}">
                                        @error('year')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="term" class="m-0 font-weight-bold text-primary">* Term</label>
                                        <select name="term" class="form-control" id="term">
                                            <option value="">- select term -</option>
                                            @if ($terms)
                                                @foreach ($terms as $term)
                                                    <option 
                                                        value="{{ $term->id }}" 
                                                        data-type="{{ $term->type }}"
                                                        {{ (old('term') == $term->id) ? 'selected' : '' }}
                                                    >{{ $term->term }}</option>
                                                @endforeach
                                            @endif
                                            <option value="addterm" data-toggle="modal" data-target="#modalll">- Click to add new term -</option>  
                                        </select>
                                        @error('term')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="succeeding_year" class="m-0 font-weight-bold text-primary">
                                <input type="checkbox" name="succeeding_year" id="succeeding_year">
                                No Succeeding Year (E.g. 2000-2001)
                            </label>
                            <span class="font-italic text-info"> Note: By default all regular term succeeding year is auto added</span>
                        </div>
                        <div class="form-group">
                            <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                            <input type="text" name="name" placeholder="" id="name" class="form-control text-uppercase" readonly value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="class_start" class="m-0 font-weight-bold text-primary">* Class Start</label>
                                        <input type="text" name="class_start" id="class_start" placeholder="" class="form-control text-uppercase datepicker" value="{{ old('class_start') }}">
                                        @error('class_start')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="class_end" class="m-0 font-weight-bold text-primary">* Class End</label>
                                        <input type="text" name="class_end" id="class_end" placeholder="" class="form-control text-uppercase datepicker" value="{{ old('class_end') }}">
                                        @error('class_end')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="class_ext" class="m-0 font-weight-bold text-primary">* Class Extension</label>
                                        <input type="text" name="class_ext" id="class_ext" placeholder="" class="form-control text-uppercase datepicker" value="{{ old('class_ext') }}">
                                        @error('class_ext')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                              </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enroll_start" class="m-0 font-weight-bold text-primary">* Enroll Start</label>
                                        <input type="text" name="enroll_start" id="enroll_start" placeholder="" class="form-control text-uppercase datepicker" value="{{ old('enroll_start') }}">
                                        @error('enroll_start')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enroll_end" class="m-0 font-weight-bold text-primary">* Enroll End</label>
                                        <input type="text" name="enroll_end" id="enroll_end" placeholder="" class="form-control text-uppercase datepicker" value="{{ old('enroll_end') }}">
                                        @error('enroll_end')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enroll_ext" class="m-0 font-weight-bold text-primary">* Enroll Extension</label>
                                        <input type="text" name="enroll_ext" id="enroll_ext" placeholder="" class="form-control text-uppercase datepicker" value="{{ old('enroll_ext') }}">
                                        @error('enroll_ext')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                              </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="adddrop_start" class="m-0 font-weight-bold text-primary">* Add Drop Start</label>
                                        <input type="text" name="adddrop_start" id="adddrop_start" placeholder="" class="form-control text-uppercase datepicker" value="{{ old('adddrop_start') }}">
                                        @error('adddrop_start')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="adddrop_end" class="m-0 font-weight-bold text-primary">* Add Drop End</label>
                                        <input type="text" name="adddrop_end" id="adddrop_end" placeholder="" class="form-control text-uppercase datepicker" value="{{ old('adddrop_end') }}">
                                        @error('adddrop_end')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="adddrop_ext" class="m-0 font-weight-bold text-primary">* Add Drop Extension</label>
                                        <input type="text" name="adddrop_ext" id="adddrop_ext" placeholder="" class="form-control text-uppercase datepicker" value="{{ old('adddrop_ext') }}">
                                        @error('adddrop_ext')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                              </div>
                        </div>
                        <div class="form-group">
                            <label for="idmask" class="m-0 font-weight-bold text-primary">* ID Mask</label>
                            <input type="text" name="idmask" placeholder="Auto generated ID number prefix" id="idmask" class="form-control" value="{{ old('idmask') }}">
                            @error('idmask')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for=""  class="m-0 font-weight-bold text-primary">* Source</label>
                                </div>
                                <div class="col-md-10">
                                    <label for="internal"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="source" value="1" id="internal"> Internal </label>
                                    <label for="external"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="source" value="2" id="external" checked> External </label>    
                                </div>
                            </div>
                            @error('source')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="code"  class="m-0 font-weight-bold text-primary">* Display</label>
                                </div>
                                <div class="col-md-10">
                                    <label for="no"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="display" value="0" id="no" checked> No </label>
                                    <label for="yes"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="display" value="1" id="yes"> Yes </label>
                                </div>
                            </div>
                            @error('display')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Period</button>
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