@extends('layout')
@section('title') {{ 'Update User' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Update User</h1>
        <p class="mb-4">Update existing record in the database</p>

        <div class="container py-2">       
            <div class="row">
              <div class="col-lg-12 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
          
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('updateuser', ['user' => $userdetails->id]) }}"  role="form">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="username">ID Number</label>
                            <input type="text" name="idno" placeholder="" class="form-control text-uppercase" value="{{ $userdetails->idno }}">
                            @error('idno')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="username">Name</label>
                            <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ $userdetails->info->name }}">
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="username">User Access</label>                            
                            @error('access')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        @php
                            $arrAccess = [];
                        @endphp
                        @if ($userdetails->access)
                            @foreach ($userdetails->access as $access)
                                @php    
                                    $arrAccess[] =  $access->access
                                @endphp    
                            @endforeach
                        @endif
                        @php
                            if (Helpers::userAccessArray()) {
                        @endphp
                                <section class="">
                                    <div class="">
                                        <div class="row g-4">
                                            @php
                                                $i = 0;
                                                foreach (Helpers::userAccessArray() as $key => $value) {
                                                    if($i%5 == 0 && $i != 0) { 
                                                    @endphp
                                                                </div>
                                                            </div>
                                                        </section>  
                                                        <section class="">
                                                            <div class="">
                                                                <div class="row g-4">  
                                                    @php
                                                    }

                                                    @endphp            
                                                    <div class="col-md">
                                                        <table class="table table-sm table-bordered table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">{{ $value['title'] }}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    foreach ($value['access'] as $k => $v) {
                                                                       @endphp
                                                                       <tr>
                                                                            <td>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="checkbox" name="access[]" class="uaccess" id="{{ $v['id'] }}" value="{{ $v['link'] }}" {{ ($userdetails->access) ? (in_array($v['link'], $arrAccess)) ? 'checked' : '' : '' }}>
                                                                                    <label class="form-check-label" for="{{ $v['id'] }}">
                                                                                    {{ $v['header'] }}
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                       @php
                                                                    }
                                                                @endphp
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @php
                                                    $i++;
                                                }
                                            @endphp
                                        </div>
                                    </div>
                                </section>
                        @php
                            }
                        @endphp
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Update User  </button>
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