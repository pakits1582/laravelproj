@extends('layout')
@section('title') {{ 'Update Department' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
              <div class="col-lg-7 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
                  <h1 class="h3 mb-0 text-primary font-weight-bold">Update Department</h1>
                  <p class="mb-2">Update record in the database</p>
                  <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('updatedepartment', ['department' => $department->id]) }}"  role="form">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                          <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                          <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ $department->code }}">
                        @error('code')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                            <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                            <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ $department->name }}">
                        @error('name')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                          <label for="username" class="m-0 font-weight-bold text-primary">Head</label>
                          <select name="head" class="form-control">
                              <option value="">- select department head -</option>
                              @if ($instructors)
                                  @foreach ($instructors as $instructor)
                                      <option value="{{ $instructor->id }}" {{ ($department->head == $instructor->id) ? 'selected' : '' }}>{{ $instructor->last_name.', '.$instructor->first_name }}</option>
                                  @endforeach
                              @endif
                          </select>    
                      
                      @error('head')
                          <p class="text-danger text-xs mt-1">{{$message}}</p>
                      @enderror
                      </div>
                        
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Update Department  </button>
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