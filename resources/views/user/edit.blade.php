@extends('layout')
@section('title') {{ 'Update User' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class=" py-2">       
            <div class="row">
              <div class="col-lg-12 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <h1 class="h3 mb-0 text-primary font-weight-bold">Update User</h1>
                    <p class="mb-2">Update record in the database</p>
                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                    <!-- credit card info-->
                    <div id="nav-tab-card" class="tab-pane fade show active">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                      <form method="POST" action="{{ route('users.update', ['user' => $userdetails->id]) }}"  role="form" id="update_user_form">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="code" class="m-0 font-weight-bold text-primary">* ID Number</label>
                            <input type="text" name="idno" placeholder="" class="form-control text-uppercase" value="{{ $userdetails->idno }}">
                            @error('idno')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            <div id="error_idno"></div>
                        </div>
                        <div class="form-group">
                            <label for="code" class="m-0 font-weight-bold text-primary">* Name</label>
                            <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ ($userdetails->utype == 1) ? $userdetails->instructorinfo->name : $userdetails->info->name }}">
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            <div id="error_name"></div>
                        </div>
                        <div class="form-group">
                            <label for="code" class="m-0 font-weight-bold text-primary">Accessible Programs</label>
                            <p class="font-italic text-info">Note: Leaving this field blank denotes user has access to all programs. You can select multiple programs.</p>
                            <select class="form-control" name="accessible_programs[]" multiple="multiple" id="accessible_programs">
                                @if ($programs)
                                    @foreach ($programs as $program)
                                        <option value="{{ $program->id }}"
                                            {{ ($userdetails->accessibleprograms) ? (Helpers::is_column_in_array($program->id, 'program_id', $userdetails->accessibleprograms->toArray()) !== false) ? 'selected' : '' : '' }}       
                                        >({{ $program->code }}){{ $program->name }}</option>
                                    @endforeach
                                @endif
                              </select>
                            <div id="error_accessible_programs"></div>
                            @error('accessible_programs')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
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
                                        <input class="form-check-input" id="can_prerequisite" type="checkbox" value="can_prerequisite" name="permissions[]"
                                        {{ ($userdetails->permissions) ? (Helpers::is_column_in_array('can_prerequisite', 'permission', $userdetails->permissions->toArray()) !== false) ? 'checked' : '' : '' }}
                                        >
                                        <label for="can_prerequisite" class="m-0 font-weight-bold text-dark">Can accept pre-requisite deficiency</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_overloadunits" type="checkbox" value="can_overloadunits" name="permissions[]"
                                        {{ ($userdetails->permissions) ? (Helpers::is_column_in_array('can_overloadunits', 'permission', $userdetails->permissions->toArray()) !== false) ? 'checked' : '' : '' }}
                                        >
                                        <label for="can_overloadunits" class="m-0 font-weight-bold text-dark">Can accept overload units</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_withbalance" type="checkbox" value="can_withbalance" name="permissions[]"
                                        {{ ($userdetails->permissions) ? (Helpers::is_column_in_array('can_withbalance', 'permission', $userdetails->permissions->toArray()) !== false) ? 'checked' : '' : '' }}
                                        >
                                        <label for="can_withbalance" class="m-0 font-weight-bold text-dark">Can accept student with balance</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_conflicts" type="checkbox" value="can_conflicts" name="permissions[]"
                                        {{ ($userdetails->permissions) ? (Helpers::is_column_in_array('can_conflicts', 'permission', $userdetails->permissions->toArray()) !== false) ? 'checked' : '' : '' }}
                                        >
                                        <label for="can_conflicts" class="m-0 font-weight-bold text-dark">Can accept conflict schedule</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_zeroslot" type="checkbox" value="can_zeroslot" name="permissions[]"
                                        {{ ($userdetails->permissions) ? (Helpers::is_column_in_array('can_zeroslot', 'permission', $userdetails->permissions->toArray()) !== false) ? 'checked' : '' : '' }}
                                        >
                                        <label for="can_zeroslot" class="m-0 font-weight-bold text-dark">Can override zero remaining slot</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-solid">
                                        <input class="form-check-input" id="can_evaluatetwice" type="checkbox" value="can_evaluatetwice" name="permissions[]"
                                        {{ ($userdetails->permissions) ? (Helpers::is_column_in_array('can_evaluatetwice', 'permission', $userdetails->permissions->toArray()) !== false) ? 'checked' : '' : '' }}
                                        >
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
                            $arrAccess = [];
                            if ($userdetails->access)
                            {
                                foreach ($userdetails->access as $key => $access)
                                {
                                    $arrAccess[] = [
                                        'access' => $access->access,
                                        'read' => $access->read_only,
                                        'write' => $access->write_only
                                    ];
                                }
                            }
                        @endphp
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
                                                                <th class="text-center">R</th>
                                                                <th class="text-center">W</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    foreach ($value['access'] as $k => $v) {
                                                                       @endphp
                                                                       <tr>
                                                                            <td>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input item_{{ $key }} uaccess" type="checkbox" name="access[]" id="{{ $v['id'] }}" value="{{ $v['link'] }}" {{ ($userdetails->access) ? (Helpers::is_column_in_array($v['link'], 'access', $arrAccess) !== false) ? 'checked' : '' : '' }}>
                                                                                    <label class="form-check-label" for="{{ $v['id'] }}">
                                                                                    {{ $v['header'] }}
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                            <td class="mid"><input class="read_{{ $key }}" type="checkbox"  id="read_{{ $v['id'] }}" value="1" {{ ($userdetails->access) ? (Helpers::is_column_in_array($v['link'], 'access', $arrAccess) !== false) ? (($arrAccess[Helpers::is_column_in_array($v['link'], 'access', $arrAccess)]['read'] == 1) ? 'checked' : '') : '' : '' }}></td>
                                                                            <td class="mid"><input class="write_{{ $key }}" type="checkbox" id="write_{{ $v['id'] }}" value="1" {{ ($userdetails->access) ? (Helpers::is_column_in_array($v['link'], 'access', $arrAccess) !== false) ? (($arrAccess[Helpers::is_column_in_array($v['link'], 'access', $arrAccess)]['write'] == 1) ? 'checked' : '') : '' : '' }}></td>
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