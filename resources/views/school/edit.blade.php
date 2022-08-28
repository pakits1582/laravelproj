@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
              <div class="col-lg-7 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
                  <h1 class="h3 mb-0 text-primary font-weight-bold">Update School</h1>
                  <p class="mb-2">Update record in the database</p>
                  <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('updateschool', ['school' => $school->id]) }}"  role="form">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                          <label for="code" class="m-0 font-weight-bold text-primary">* School Code</label>
                          <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ $school->code }}">
                        @error('code')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                            <label for="name" class="m-0 font-weight-bold text-primary">* School Name</label>
                            <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ $school->name }}">
                        @error('name')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                            <label for="address" class="m-0 font-weight-bold text-primary">School Address</label>
                            <input type="text" name="address" placeholder="" class="form-control text-uppercase" value="{{ $school->address }}">
                        @error('address')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Update School  </button>
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