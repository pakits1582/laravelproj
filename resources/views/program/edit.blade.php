@extends('layout')
@section('title') {{ 'Update Program' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
                <div class="col-lg-7 mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <h1 class="h3 mb-0 text-primary font-weight-bold">Update Program</h1>
                        <p class="mb-2">Update record in the database</p>
                        <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                        <!-- credit card info-->
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('programs.update', ['program' => $program->id]) }}"  role="form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                                <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ (old('code')) ? old('code') : $program->code }}">
                                @error('code')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                                <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ (old('name')) ? old('name') : $program->name }}">
                                @error('name')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="years" class="m-0 font-weight-bold text-primary">* Years</label>
                                <input type="text" name="years" placeholder="" class="form-control text-uppercase" value="{{ (old('years')) ? old('years') : $program->years }}">
                                @error('years')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">* Level</label>
                                @include('partials.educlevels.dropdown', ['value' => $program->educational_level_id, 'fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id'])
                            </div>
                            <div class="form-group">
                                <label for="college" class="m-0 font-weight-bold text-primary">* College</label>
                                @include('partials.colleges.dropdown', ['value' => $program->college_id, 'fieldname' => 'college_id', 'fieldid' => 'college_id'])
                            </div>
                            <div class="form-group">
                                <label for="head" class="m-0 font-weight-bold text-primary">Head</label>
                                <select name="head" class="form-control">
                                    <option value="">- select head -</option>
                                    @if ($heads)
                                        @foreach ($heads as $head)
                                            <option value="{{ $head->id }}"
                                                {{ (old('head')) ? (old('head') == $head->id) ? 'selected' : '' : '' }}
                                                {{ ($program->head == $head->id) ? 'selected' : '' }}
                                                >{{ $head->last_name.', '.$head->first_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('head')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="type"  class="m-0 font-weight-bold text-primary">* Type</label>
                                <select name="type" required class="form-control" id="type">
                                    <option value="">- select type -</option>
                                    <option value="CERTIFICATE" {{ ($program->type == 'CERTIFICATE') ? 'selected' : '' }}>CERTIFICATE</option>
                                    <option value="UNDERGRADUATE" {{ ($program->type == 'UNDERGRADUATE') ? 'selected' : '' }}>UNDERGRADUATE</option>
                                    <option value="GRADUATE" {{ ($program->type == 'GRADUATE') ? 'selected' : '' }}>GRADUATE</option>
                                </select>
                                @error('type')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="code"  class="m-0 font-weight-bold text-primary">Source</label>
                                    </div>
                                    <div class="col-md-10">
                                        <label for="internal"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="source" value="1" id="internal" {{ ($program->source == 1) ? 'checked' : '' }}> Internal </label>
                                        <label for="external"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="source" value="2" id="external" {{ ($program->source == 2) ? 'checked' : '' }}> External </label>
                                    </div>
                                </div>
                                @error('source')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="code"  class="m-0 font-weight-bold text-primary">Active</label>
                                    </div>
                                    <div class="col-md-10">
                                        <label for="no_active"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="active" value="0" id="no_active" {{ ($program->active == 0) ? 'checked' : '' }}> No </label>
                                        <label for="yes_active"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="active" value="1" id="yes_active" {{ ($program->active == 1) ? 'checked' : '' }}> Yes </label>
                                    </div>
                                </div>
                                @error('active')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="display"  class="m-0 font-weight-bold text-primary">Display</label>
                                    </div>
                                    <div class="col-md-10">
                                        <label for="no_display"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="display" value="0" id="no_display" {{ ($program->display == 0) ? 'checked' : '' }}> No </label>
                                        <label for="yes_display"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="display" value="1" id="yes_display" {{ ($program->display == 1) ? 'checked' : '' }}> Yes </label>
                                    </div>
                                </div>
                                @error('display')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Update Program  </button>
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