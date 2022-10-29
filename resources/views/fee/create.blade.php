@extends('layout')
@section('title') {{ 'Add New Fee' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="container py-2">       
            <div class="row">
                <div class="col-lg-7 mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <h1 class="h3 mb-0 text-primary font-weight-bold">Add New Fee</h1>
                        <p class="mb-2">Add new record in the database</p>
                        <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                        <!-- credit card info-->
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            @if(Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                            @endif
                            <form method="POST" action="{{ route('fees.store') }}"  role="form">
                            @csrf
                            <div class="form-group">
                                <label for="term" class="m-0 font-weight-bold text-primary">* Type</label>
                                <select name="fee_type_id" class="form-control" id="fee_type">
                                    <option value="">- select type -</option>
                                    @if ($fee_types)
                                        @foreach ($fee_types as $fee_type)
                                            <option 
                                                value="{{ $fee_type->id }}"
                                                {{ (old('fee_type_id') == $fee_type->id) ? 'selected' : '' }}
                                            >{{ $fee_type->type }}</option>
                                        @endforeach
                                    @endif
                                    <option value="addnewtype" data-toggle="modal" data-target="#modal">- Click to add new type -</option>  
                                </select>
                                @error('fee_type_id')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                                <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ old('code') }}">
                                @error('code')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" id="iscompound" type="checkbox" value="1" name="iscompound" {{ (old('iscompound') == 1) ? 'checked' : '' }}>
                                    <label for="iscompound" class="m-0 font-weight-bold text-primary">Compound Fee</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                                <input type="text" name="name" id="name" placeholder="" class="form-control text-uppercase" value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name" class="m-0 font-weight-bold text-primary">Default Value</label>
                                <input type="text" name="default_value" placeholder="0.00" class="form-control text-uppercase" value="{{ old('default_value') }}">
                                @error('default_value')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Save Fee  </button>
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