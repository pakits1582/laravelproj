@extends('layout')
@section('title') {{ 'Update Section' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
                <div class="col-lg-7 mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <h1 class="h3 mb-0 text-primary font-weight-bold">Update Section</h1>
                        <p class="mb-2">Update record in the database</p>
                        <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                        <!-- credit card info-->
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('sections.update', ['section' => $section->id]) }}"  role="form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                                <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ (old('code')) ? old('code') : $section->code }}">
                            @error('code')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                                <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ (old('name')) ? old('name') : $section->name }}">
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="program" class="m-0 font-weight-bold text-primary">* Program</label>
                                <select name="program" class="form-control">
                                    <option value="">- select program -</option>
                                    @if ($programs)
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}" {{ (old('program') == $program->id) ? 'selected' : (($program->id == $section->program) ? 'selected' : '')  }}>{{ $program->code }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @error('program')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="year" class="m-0 font-weight-bold text-primary">* Year</label>
                                <input type="text" name="year" placeholder="" class="form-control text-uppercase" value="{{ (old('year')) ? old('year') : $section->year }}">
                            @error('year')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="minenrollee" class="m-0 font-weight-bold text-primary">* Min Enrollee</label>
                                <input type="text" name="minenrollee" placeholder="" class="form-control text-uppercase" value="{{ (old('minenrollee')) ? old('minenrollee') : $section->minenrollee }}">
                            @error('minenrollee')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            </div>
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Update Section  </button>
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