@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Add New College</h1>
        <p class="mb-4">Add new record in the database</p>

        <div class="container py-2">       
            <div class="row">
              <div class="col-lg-7 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
          
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('savecollege') }}"  role="form">
                        @csrf
                        <div class="form-group">
                          <label for="code">College Code</label>
                          <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ old('code') }}">
                        @error('code')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">College Name</label>
                            <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        <div class="form-group">
                            <label for="dean">College Dean</label>
                            <select name="dean" class="form-control">
                                <option value="">- select college dean -</option>
                                @if ($instructors)
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->lname.', '.$instructor->fname }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @error('dean')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        </div>
                        
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Save College  </button>
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