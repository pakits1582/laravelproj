@extends('layout')
@section('title') {{ 'Add New User' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class=" py-2">       
            <div class="row">
              <div class="col-lg-12 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <h1 class="h3 mb-0 text-primary font-weight-bold">Add New User</h1>
                    <p class="mb-2">Add new record in the database</p>
                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('users.store') }}"  role="form" id="create_user_form">
                        @csrf
                        <div class="form-group">
                            <label for="code" class="m-0 font-weight-bold text-primary">* ID Number</label>
                            <input type="text" name="idno" placeholder="" class="form-control text-uppercase" value="{{ old('idno') }}">
                            @error('idno')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            <div id="error_idno"></div>
                        </div>
                        <div class="form-group">
                            <label for="code" class="m-0 font-weight-bold text-primary">* Name</label>
                            <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            <div id="error_name"></div>
                        </div>
                        <div class="form-group">
                            <label for="code" class="m-0 font-weight-bold text-primary">User Permissions</label>
                            {{-- <input type="text" name="address" placeholder="" class="form-control text-uppercase" value="{{ old('address') }}"> --}}
                            <div id="error_permissions"></div>
                            @error('permission')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_prerequisite" type="checkbox" value="can_prerequisite" name="permissions[]">
                                        <label for="can_prerequisite" class="m-0 font-weight-bold text-dark">Can accept pre-requisite deficiency</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_overloadunits" type="checkbox" value="can_overloadunits" name="permissions[]">
                                        <label for="can_overloadunits" class="m-0 font-weight-bold text-dark">Can accept overload units</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_withbalance" type="checkbox" value="can_withbalance" name="permissions[]">
                                        <label for="can_withbalance" class="m-0 font-weight-bold text-dark">Can accept student with balance</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_conflicts" type="checkbox" value="can_conflicts" name="permissions[]">
                                        <label for="can_conflicts" class="m-0 font-weight-bold text-dark">Can accept conflict schedule</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_zeroslot" type="checkbox" value="can_zeroslot" name="permissions[]">
                                        <label for="can_zeroslot" class="m-0 font-weight-bold text-dark">Can override zero remaining slot</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_evaluatetwice" type="checkbox" value="can_evaluatetwice" name="permissions[]">
                                        <label for="can_evaluatetwice" class="m-0 font-weight-bold text-dark">Can evaluate a subject twice in student grade file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="code" class="m-0 font-weight-bold text-primary">* User Access</label>
                            {{-- <input type="text" name="address" placeholder="" class="form-control text-uppercase" value="{{ old('address') }}"> --}}
                            <div id="error_access"></div>
                            @error('access')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                        </div>
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
                                                                <th scope="col" class="text-center">
                                                                    <input type="checkbox" class="form-check-input selectall" id="selectall_{{ $key }}" data-id="{{ $key }}" />
                                                                    <label class="form-check-label text-primary" for="selectall_{{ $key }}">
                                                                        {{ $value['title'] }}
                                                                    </label>
                                                                </th>
                                                                <th class="text-center text-dark">R</th>
                                                                <th class="text-center text-dark">W</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    foreach ($value['access'] as $k => $v) {
                                                                       @endphp
                                                                       <tr>
                                                                            <td>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input item_{{ $key }} uaccess" type="checkbox" name="access[]" id="{{ $v['id'] }}" value="{{ $v['link'] }}" >
                                                                                    <label class="form-check-label" for="{{ $v['id'] }}">
                                                                                        {{ $v['header'] }}
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                            <td class="mid"><input class="read_{{ $key }}" type="checkbox" id="read_{{ $v['id'] }}" value="1" ></td>
                                                                            <td class="mid"><input class="write_{{ $key }}" type="checkbox" id="write_{{ $v['id'] }}" value="1" ></td>
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
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Save User  </button>
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