@extends('layout')
@section('title') {{ 'Update College' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="container py-2">       
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <!-- Page Heading -->
                        <h1 class="h3 mb-0 text-primary font-weight-bold">Update College</h1>
                        <p class="mb-2">Update record in the database</p>
                        <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                        <!-- credit card info-->
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('colleges.update', ['college' => $college->id]) }}"  role="form">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                                    <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ $college->code }}">
                                    @error('code')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                                    <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ $college->name }}">
                                    @error('name')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="username" class="m-0 font-weight-bold text-primary">Dean</label>
                                    <select name="dean" class="form-control">
                                        <option value="">- select dean -</option>
                                        @if ($instructors)
                                            @foreach ($instructors as $instructor)
                                                <option value="{{ $instructor->id }}" {{ ($college->dean == $instructor->id) ? 'selected' : '' }}>{{ $instructor->last_name.', '.$instructor->first_name.' '.$instructor->middle_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>    
                                    @error('dean')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="class_code" class="m-0 font-weight-bold text-primary">* Class Code</label>
                                    <input type="text" name="class_code" placeholder="Must be unique single character, to be used in class offering" class="form-control text-uppercase" value="{{ $college->class_code }}">
                                    @error('class_code')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Update College  </button>
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