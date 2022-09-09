@extends('layout')
@section('title') {{ 'Add New Subject' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <h1 class="h3 mb-0 text-primary font-weight-bold">Add New Subject</h1>
                        <p class="mb-2">Add new record in the database</p>
                        <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                        <!-- credit card info-->
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('subjects.store') }}"  role="form">
                            @csrf
                            <div class="form-group">
                                <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                                <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ old('code') }}">
                                @error('code')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                                <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="units" class="m-0 font-weight-bold text-primary">Units</label>
                                            <input type="text" name="units" placeholder="" class="form-control text-uppercase" value="{{ old('units') }}">
                                            @error('units')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="tfunits" class="m-0 font-weight-bold text-primary">Tuition</label>
                                            <input type="text" name="tfunits" placeholder="" class="form-control text-uppercase" value="{{ old('tfunits') }}">
                                            @error('tfunits')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="loadunits" class="m-0 font-weight-bold text-primary">Load</label>
                                            <input type="text" name="loadunits" placeholder="" class="form-control text-uppercase" value="{{ old('loadunits') }}">
                                            @error('loadunits')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="lecunits" class="m-0 font-weight-bold text-primary">Lecture</label>
                                            <input type="text" name="lecunits" placeholder="" class="form-control text-uppercase" value="{{ old('lecunits') }}">
                                            @error('lecunits')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="labunits" class="m-0 font-weight-bold text-primary">Lab</label>
                                            <input type="text" name="labunits" placeholder="" class="form-control text-uppercase" value="{{ old('labunits') }}">
                                            @error('labunits')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="hours" class="m-0 font-weight-bold text-primary">Hours</label>
                                            <input type="text" name="hours" placeholder="" class="form-control text-uppercase" value="{{ old('hours') }}">
                                            @error('hours')
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
                                            <label for="educational_level" class="m-0 font-weight-bold text-primary">* Level</label>
                                            @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id'])
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                            @include('partials.colleges.dropdown', ['fieldname' => 'college_id', 'fieldid' => 'college_id'])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="flexCheckSolidChecked" type="checkbox" value="1" name="exclusive" {{ (old('exclusive')) ? 'cehcked' : '' }}>
                                    <label for="exclusive" class="m-0 font-weight-bold text-primary">Exclusive subject of college</label>
                                </div>
                                @error('exclusive')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="professional" type="checkbox" value="1" name="professional" {{ (old('professional')) ? 'cehcked' : '' }}>
                                    <label for="professional" class="m-0 font-weight-bold text-primary">Is professional subject</label>
                                </div>
                                @error('professional')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="laboratory" type="checkbox" value="1" name="laboratory" {{ (old('laboratory')) ? 'cehcked' : '' }} >
                                    <label for="laboratory" class="m-0 font-weight-bold text-primary">Is laboratory subject</label>                                 
                                </div>
                                @error('laboratory')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="notuition" type="checkbox" value="1" name="notuition" {{ (old('notuition')) ? 'cehcked' : '' }} >
                                    <label for="notuition" class="m-0 font-weight-bold text-primary">No Tuition (Do not compute tuition fee)</label>
                                </div>
                                @error('notuition')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="nograde" type="checkbox" value="1" name="nograde" {{ (old('nograde')) ? 'cehcked' : '' }} >
                                    <label for="nograde" class="m-0 font-weight-bold text-primary">No Grade (Do not display in OTR)</label>
                                </div>
                                @error('nograde')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="gwa" type="checkbox" value="1" name="gwa" {{ (old('gwa')) ? 'cehcked' : '' }}>
                                    <label for="gwa" class="m-0 font-weight-bold text-primary">Exclude in computation of GWA</label>
                                </div>
                                @error('gwa')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Save Subject  </button>
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