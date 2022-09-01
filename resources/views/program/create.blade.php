@extends('layout')
@section('title') {{ 'Add New Program' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
                <div class="col-lg-7 mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <h1 class="h3 mb-0 text-primary font-weight-bold">Add New Program</h1>
                        <p class="mb-2">Add new record in the database</p>
                        <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                        <!-- credit card info-->
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('programs.store') }}"  role="form">
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
                                <label for="years" class="m-0 font-weight-bold text-primary">* Years</label>
                                <input type="text" name="years" placeholder="" class="form-control text-uppercase" value="{{ old('years') }}">
                                @error('years')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">* Level</label>
                                <select name="educational_level" class="form-control">
                                    <option value="">- select level -</option>
                                    @if ($educlevels)
                                        @foreach ($educlevels as $educlevel)
                                            <option value="{{ $educlevel->id }}">{{ $educlevel->level }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('educational_level')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="college" class="m-0 font-weight-bold text-primary">* College</label>
                                <select name="college" class="form-control">
                                    <option value="">- select college -</option>
                                    @if ($colleges)
                                        @foreach ($colleges as $college)
                                            <option value="{{ $college->id }}">{{ $college->code }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('college')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="head" class="m-0 font-weight-bold text-primary">Head</label>
                                <select name="head" class="form-control">
                                    <option value="">- select head -</option>
                                    @if ($heads)
                                        @foreach ($heads as $head)
                                            <option value="{{ $head->id }}">{{ $head->last_name.', '.$head->first_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('head')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="code"  class="m-0 font-weight-bold text-primary">Source</label>
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
                                        <label for="code"  class="m-0 font-weight-bold text-primary">Active</label>
                                    </div>
                                    <div class="col-md-10">
                                        <label for="no"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="active" value="0" id="no" checked> No </label>
                                        <label for="yes"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="active" value="1" id="yes"> Yes </label>
                                    </div>
                                </div>
                                @error('active')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Save Program  </button>
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