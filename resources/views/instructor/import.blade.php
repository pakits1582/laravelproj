@extends('layout')
@section('title') {{ 'Upload Instructors' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        {{-- @if(Session::has('errors'))
            {{ dd($errors) }}
        @endif --}}
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif
        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="container">
                <form method="POST" action="{{ route('instructors.uploadimport') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                                <button class="btn btn-primary" type="submit" id="customFileInput">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
