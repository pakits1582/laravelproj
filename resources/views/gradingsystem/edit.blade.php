@extends('layout')
@section('title') {{ 'Update Grade' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
                <div class="col-lg-7 mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <h1 class="h3 mb-0 text-primary font-weight-bold">Update Grade</h1>
                        <p class="mb-2">Update record in the database</p>
                        <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                        <!-- credit card info-->
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('gradingsystems.update', ['gradingsystem' => $gradingsystem->id]) }}"  role="form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">* Level</label>
                                @include('partials.educlevels.dropdown', ['value' => $gradingsystem->educational_level_id, 'fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id'])
                            </div>
                            <div class="form-group">
                                <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                                <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ (old('code')) ? old('code') : $gradingsystem->code }}">
                                @error('code')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name" class="m-0 font-weight-bold text-primary">* Value</label>
                                <input type="text" name="value" id="value" placeholder="" class="form-control text-uppercase" value="{{ (old('value')) ? old('value') : $gradingsystem->value }}">
                                @error('value')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="term" class="m-0 font-weight-bold text-primary">* Remark</label>
                                <select name="remark_id" class="form-control" id="remark">
                                    <option value="">- select remark -</option>
                                    @if ($remarks)
                                        @foreach ($remarks as $remark)
                                            <option 
                                                value="{{ $remark->id }}"
                                                {{ (old('remark_id') == $remark->id) ? 'selected' : '' }}
                                                {{ ($gradingsystem->remark_id == $remark->id) ? 'selected' : '' }}
                                            >{{ $remark->remark }}</option>
                                        @endforeach
                                    @endif
                                    <option value="addnewremark" data-toggle="modal" data-target="#modal">- Click to add new remark -</option>  
                                </select>
                                @error('remark_id')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Update Grade  </button>
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