{{-- {{ dd($evaluation) }} --}}
@extends('layout')
@section('title') {{ 'Enrollment and Registration' }} @endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Enrollment and Registration</h1>
    <p class="mb-4">Register classes, enrollment and assessment.</p>
    
    @if (isset($errors) && count($errors) > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="font-weight-bold text-primary m-0">Student Information</h6>
            </div> 
            <div class="card-body">
                <div class="row">
                    <div class="col-md-1">
                        <label for="" class="m-0 font-weight-bold text-primary">ID No.</label>
                    </div>
                    <div class="col-md-5">
                        {{ $student->user->idno }}
                        <input type="hidden" id="student_id" value="{{ $student->id }}" />
                    </div>
                    <div class="col-md-1">
                        <label for="" class="m-0 font-weight-bold text-primary">Year</label>
                    </div>
                    <div class="col-md-2">
                        {{ Helpers::yearLevel($student->year_level) }}
                    </div>
                    <div class="col-md-1">
                        <label for="" class="m-0 font-weight-bold text-primary">Curriculum</label>
                    </div>
                    <div class="col-md-2">
                        {{ $student->curriculum->curriculum }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <label for="" class="m-0 font-weight-bold text-primary">Name</label>
                    </div>
                    <div class="col-md-5">
                        {{ $student->name }}
                    </div>
                    <div class="col-md-1">
                        <label for="" class="m-0 font-weight-bold text-primary">Level</label>
                    </div>
                    <div class="col-md-2">
                        {{ $student->program->level->code }}
                    </div>
                    <div class="col-md-1">
                        <label for="" class="m-0 font-weight-bold text-primary">College</label>
                    </div>
                    <div class="col-md-2">
                        {{ $student->program->collegeinfo->code }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <label for="" class="m-0 font-weight-bold text-primary">Program</label>
                    </div>
                    <div class="col-md-11">
                        ({{ $student->program->code }}) {{ $student->program->name }}
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="font-weight-bold text-primary m-0">Enrollment Information</h6>
            </div> 
            <div class="card-body">
                <ul>
                    @if (isset($errors) && count($errors) > 0)
                        @foreach ($errors as $error)
                            <li><h4 class="text-danger m-0">{{ $error }}</h4></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="font-weight-bold text-primary m-0">Enrollment Information</h6>
            </div> 
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <label for="" class="m-0 font-weight-bold text-primary">ID No.</label>
                    </div>
                    <div class="col-md-3">
                        {{ $student->user->idno }}
                    </div>
                    <div class="col-md-2">
                        <label for="" class="m-0 font-weight-bold text-primary">Name</label>
                    </div>
                    <div class="col-md-5">
                        {{ $student->name }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="" class="m-0 font-weight-bold text-primary">Enrollment Period</label>
                    </div>
                    <div class="col-md-3">
                        {{ session('periodname') }}
                    </div>
                    <div class="col-md-2">
                        <label for="" class="m-0 font-weight-bold text-primary">Program</label>
                    </div>
                    <div class="col-md-2">
                        {{ $enrollment->program->code }}
                    </div>
                    <div class="col-md-1">
                        <label for="" class="m-0 font-weight-bold text-primary">Year Level</label>
                    </div>
                    <div class="col-md-2">
                        {{ Helpers::yearLevel($enrollment->year_level) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="" class="m-0 font-weight-bold text-primary">Enrollment No.</label>
                    </div>
                    <div class="col-md-3">
                        {{ $enrollment->id }}
                    </div>
                    <div class="col-md-2">
                        <label for="" class="m-0 font-weight-bold text-primary">Units Allowed</label>
                    </div>
                    <div class="col-md-2">
                        {{ ($registration['section']['allowed_units'] == 0) ? $registration['allowed_units'] : $registration['section']['allowed_units'] }}
                    </div>
                    <div class="col-md-1">
                        <label for="" class="m-0 font-weight-bold text-primary">Curriculum</label>
                    </div>
                    <div class="col-md-2">
                        {{ $enrollment->curriculum->curriculum }}
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <label for="" class="m-0 font-weight-bold text-primary">Enrollment Section</label>
                    </div>
                    <div class="col-md-3">
                        {{ $enrollment->section->code }}
                    </div>
                    <div class="col-md-7">
                        <!-- Additional content for the third column (if any) -->
                    </div>
                </div>
                
            </div>
        </div>

        <form method="POST" id="form_add_offerings">
            @csrf
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Enrollment Section Classes Offerings</h6>
                                </div>
                                <div class="col-md-3">
                                    <select name="section_id" class="form-control" id="section">
                                        @if ($registration['sections'])
                                            @foreach ($registration['sections'] as $section)
                                                <option value="{{ $section['section_id'] }}" {{ ($section['section_id'] == $enrollment->section_id) ? 'selected' : '' }}>
                                                    {{ $section['section_name'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>    
                                </div>
                                <div class="col-md-6">
                                    <p class="m-0 font-italic text-info" style="">
                                        Note: You can change the section to check for available classes.
                                    </p>
                                </div>
                            </div>                        
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-sm">
                                <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                    <thead class="">
                                        <tr>
                                            <th class="w20"></th>
                                            <th class="w50">Code</th>
                                            <th class="w120 mid">Subject</th>
                                            <th>Description</th>
                                            <th class="w40 mid">Units</th>
                                            <th class="w35 mid">Lec</th>
                                            <th class="w35 mid">Lab</th>
                                            <th class="w300 mid">Schedule</th>
                                            <th class="">Section</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black" id="return_section_subjects">
                                        @include('registration.section_subjects')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <input type="hidden" name="units_allowed" id="units_allowed" value="{{ ($registration['section']['allowed_units'] == 0) ? $registration['allowed_units'] : $registration['section']['allowed_units'] }}" />
                            <input type="hidden" name="student_id" id="student_id" value="{{ $student->id }}" />
                            <input type="hidden" name="enrollment_id" id="enrollment_id" value="{{ $enrollment->id }}" />
                            <input type="hidden" name="curriculum_id" id="curriculum_id" value="{{ $enrollment->curriculum->id }}" />

                            <button type="submit" id="save_selected" class="btn btn-sm btn-primary btn-icon-split actions mb-2">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-square"></i>
                                </span>
                                <span class="text">Save Selected Subjects</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Registered Subjects</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                <thead class="">
                                    <tr>
                                        <th class="w20"></th>
                                        <th class="w50">Code</th>
                                        <th class="w120 mid">Subject</th>
                                        <th>Description</th>
                                        <th class="w40 mid">Units</th>
                                        <th class="w35 mid">Lec</th>
                                        <th class="w35 mid">Lab</th>
                                        <th class="w300 mid">Schedule</th>
                                        <th class="">Section</th>
                                        <th class="w100">Added By</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black" id="return_enrolled_subjects">
                                    @include('enrollment.enrolled_class_subjects')
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" id="assess_enrollment" class="btn btn-sm btn-success btn-icon-split actions mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Assess Enrollment</span>
                        </button>
                        <button type="button" id="add_subjects" class="btn btn-sm btn-primary btn-icon-split actions mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-square"></i>
                            </span>
                            <span class="text">Add Subjects</span>
                        </button>
                        <button type="button" id="delete_selected" class="btn btn-sm btn-danger btn-icon-split actions mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Delete Selected</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- DISPLAY SCHEDULE TABLE --}}
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Schedule Table</h6>
                    </div>
                    <div class="card-body">
                        <div id="schedule_table">
                            @include('class.schedule_table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
   
</div>
@endsection