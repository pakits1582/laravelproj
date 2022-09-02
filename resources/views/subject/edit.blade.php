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
                        <h1 class="h3 mb-0 text-primary font-weight-bold">Update Subject</h1>
                        <p class="mb-2">Update record in the database</p>
                        <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                        <!-- credit card info-->
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('subjects.update', ['subject' => $subject->id]) }}"  role="form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                                <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ (old('code')) ? old('code') : $subject->code }}">
                                @error('code')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                                <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ (old('name')) ? old('name') : $subject->name }}">
                                @error('name')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="units" class="m-0 font-weight-bold text-primary">Units</label>
                                            <input type="text" name="units" placeholder="" class="form-control text-uppercase" value="{{ (old('units')) ? old('units') : $subject->units }}">
                                            @error('units')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="tfunits" class="m-0 font-weight-bold text-primary">Tuition</label>
                                            <input type="text" name="tfunits" placeholder="" class="form-control text-uppercase" value="{{ (old('tfunits')) ? old('tfunits') : $subject->tfunits }}">
                                            @error('tfunits')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="loadunits" class="m-0 font-weight-bold text-primary">Load</label>
                                            <input type="text" name="loadunits" placeholder="" class="form-control text-uppercase" value="{{ (old('loadunits')) ? old('loadunits') : $subject->loadunits }}">
                                            @error('loadunits')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="lecunits" class="m-0 font-weight-bold text-primary">Lecture</label>
                                            <input type="text" name="lecunits" placeholder="" class="form-control text-uppercase" value="{{ (old('lecunits')) ? old('lecunits') : $subject->lecunits }}">
                                            @error('lecunits')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="labunits" class="m-0 font-weight-bold text-primary">Lab</label>
                                            <input type="text" name="labunits" placeholder="" class="form-control text-uppercase" value="{{ (old('labunits')) ? old('labunits') : $subject->labunits }}">
                                            @error('labunits')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="hours" class="m-0 font-weight-bold text-primary">Hours</label>
                                            <input type="text" name="hours" placeholder="" class="form-control text-uppercase" value="{{ (old('hours')) ? old('hours') : $subject->hours }}">
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
                                            @include('partials.educlevels.dropdown', ['value' => $subject->educational_level])
                                            @error('educational_level')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                            @include('partials.colleges.dropdown', ['value' => $subject->college])
                                            @error('college')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="exclusive" type="checkbox" value="1" name="exclusive" {{ (old('exclusive') == 1) ? 'checked' : (($subject->exclusive == 1) ? 'checked' : '') }}>
                                    <label for="exclusive" class="m-0 font-weight-bold text-primary">Exclusive subject of college</label>
                                </div>
                                @error('exclusive')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="professional" type="checkbox" value="1" name="professional" {{ (old('professional') == 1) ? 'checked' : (($subject->professional == 1) ? 'checked' : '') }}>
                                    <label for="professional" class="m-0 font-weight-bold text-primary">Is professional subject</label>
                                </div>
                                @error('professional')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="laboratory" type="checkbox" value="1" name="laboratory" {{ (old('laboratory') == 1) ? 'checked' : (($subject->laboratory == 1) ? 'checked' : '') }} >
                                    <label for="laboratory" class="m-0 font-weight-bold text-primary">Is laboratory subject</label>                                 
                                </div>
                                @error('laboratory')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="notuition" type="checkbox" value="1" name="notuition" {{ (old('notuition') == 1) ? 'checked' : (($subject->notuition == 1) ? 'checked' : '') }} >
                                    <label for="notuition" class="m-0 font-weight-bold text-primary">No Tuition (Do not compute tuition fee)</label>
                                </div>
                                @error('notuition')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="nograde" type="checkbox" value="1" name="nograde" {{ (old('nograde') == 1) ? 'checked' : (($subject->nograde == 1) ? 'checked' : '') }} >
                                    <label for="nograde" class="m-0 font-weight-bold text-primary">No Grade (Do not display in OTR)</label>
                                </div>
                                @error('nograde')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="gwa" type="checkbox" value="1" name="gwa" {{ (old('gwa') == 1) ? 'checked' : (($subject->gwa == 1) ? 'checked' : '') }}>
                                    <label for="gwa" class="m-0 font-weight-bold text-primary">Exclude in computation of GWA</label>
                                </div>
                                @error('gwa')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Update Subject  </button>
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