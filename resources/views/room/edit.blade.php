@extends('layout')
@section('title') {{ 'Update Room' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
                <div class="col-lg-7 mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <h1 class="h3 mb-0 text-primary font-weight-bold">Update Room</h1>
                        <p class="mb-2">Update record in the database</p>
                        <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                        <!-- credit card info-->
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('rooms.update',['room' => $room->id]) }}"  role="form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                                <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ (old('code')) ? old('code') : $room->code }}">
                            @error('code')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                                <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ (old('name')) ? old('name') : $room->name }}">
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="capacity" class="m-0 font-weight-bold text-primary">* Capacity</label>
                                <input type="text" name="capacity" placeholder="" class="form-control text-uppercase" value="{{ (old('capacity')) ? old('capacity') : $room->capacity }}">
                            @error('capacity')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="excludechecking"  class="m-0 font-weight-bold text-primary">Exclude Conflict Checking</label>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="no"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="excludechecking" value="0" id="no" {{ ($room->excludechecking === 0) ? 'checked' : '' }}> No </label>
                                        <label for="yes"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="excludechecking" value="1" id="yes" {{ ($room->excludechecking === 1) ? 'checked' : '' }}> Yes </label>
                                    </div>
                                </div>
                                @error('source')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Save Room  </button>
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