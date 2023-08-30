@extends('layout')
@section('title') {{ 'Application Admission' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Admissions</h1>
        <p class="mb-4">Admit student's application.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 font-weight-bold text-primary mb-0">Application Admission <span id="period_name">{{ session('periodname') }}</span></h1> 
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow mb-4  h-100">
                            <div class="card-header py-3">
                                <h6 class="font-weight-bold  text-primary mb-0">Student Information</h6>  
                            </div>
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-8">
                                        <label for="idno" class="m-0 font-weight-bold text-primary">ID Number</label>
                                        <input type="text" name="idno" value="{{ old('idno', $applicant->user->idno ?? '') }}" placeholder="" class="biginput form-control" id="idno">
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#" class="btn btn-primary btn-icon-split" id="generate_idno">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-undo"></i>
                                            </span>
                                            <span class="text">Generate ID</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="idno" class="m-0 font-weight-bold text-primary">Name</label>
                                        <input type="text" name="name" value="{{ old('name', $applicant->name ?? '') }}" readonly class="biginput form-control" id="name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="idno" class="m-0 font-weight-bold text-primary">Classification</label>
                                        <input type="text" name="classification" value="{{ old('classification', \App\Models\Student::STUDENT_CLASSIFICATION[$applicant->classification] ?? '') }}" readonly class="form-control" id="classification">
                                        <h4 class="m-0 font-weight-bold text-black"></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="program_id" class="m-0 font-weight-bold text-primary">* Academic Program</label>
                                        <select name="program_id" required class="form-control text-uppercase" id="program_id">
                                            <option value="">- SELECT PROGRAM -</option>
                                            @php
                                                $groupName = '';
                                                $first = true;
                                            @endphp
                                            @if($programs)
                                                @foreach($programs as $program)
                                                    @if ($program->level->level != $groupName)
                                                        @php
                                                            $groupName = $program->level->level; // Just set the new group name
                                                        @endphp

                                                        @if (!$first) <!-- Add a closing tag when we change the group, but only if we're not in the first loop -->
                                                            </optgroup>
                                                        @else
                                                            @php
                                                                $first = false; // Make sure we don't close the tag first time, but do after the first loop
                                                            @endphp
                                                        @endif
                                                        <optgroup label="{{ $groupName }}">
                                                    @endif
                                                    <option value="{{ $program->id }}"
                                                        @if(old('program_id', $applicant->program_id ?? '') == $program->id) selected @endif
                                                    >
                                                        ({{ $program->code }}) - {{ $program->name }}
                                                    </option>
                                                @endforeach
                                                @if (!$first) <!-- Add a closing tag after the last loop -->
                                                    </optgroup>
                                                @endif
                                            @endif
                                        </select>
                                        @error('program_id')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                        <div id="error_program_id" class="errors"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="idno" class="m-0 font-weight-bold text-primary">Curriculum</label>
                                        <select name="program_id" required class="form-control text-uppercase" id="program_id">
                                            <option value="">- SELECT PROGRAM -</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow mb-4 h-100">
                            <div class="card-header py-3">
                                <h6 class="font-weight-bold text-primary mb-0">Admission Documents</h6>  
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                
                                    </div>
                                    <div class="col-md-6">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card border-left-info">
                            <div class="card-body p-2">
                                <h4 class="mb-2 text-primary">Personal Information</h4>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection