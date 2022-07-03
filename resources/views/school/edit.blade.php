@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Edit School</h1>
        <p class="mb-4">Update record in the database</p>

        <div class="container py-2">       
            <div class="row">
              <div class="col-lg-7 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
          
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('updateschool', ['school' => $schoolinfo->id]) }}"  role="form">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                          <label for="username">School Code</label>
                          <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ $schoolinfo->code }}">
                        @error('code')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                            <label for="username">School Name</label>
                            <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ $schoolinfo->name }}">
                        @error('name')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                            <label for="username">School Address</label>
                            <input type="text" name="address" placeholder="" class="form-control text-uppercase" value="{{ $schoolinfo->address }}">
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