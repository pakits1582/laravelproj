@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
              <div class="col-lg-7 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <h1 class="h3 mb-0 text-primary font-weight-bold">Add New Department</h1>
                    <p class="mb-2">Add new record in the database</p>
                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('savedepartment') }}"  role="form">
                        @csrf
                        <div class="form-group">
                          <label for="code" class="m-0 font-weight-bold text-primary">* Department Code</label>
                          <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ old('code') }}">
                        @error('code')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                            <label for="name" class="m-0 font-weight-bold text-primary">* Department Name</label>
                            <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                            <label for="head" class="m-0 font-weight-bold text-primary">Department Head</label>
                            <select name="head" class="form-control">
                                <option value="">- select department head -</option>
                                @if ($instructors)
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->last_name.', '.$instructor->first_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @error('head')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Save Department  </button>
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