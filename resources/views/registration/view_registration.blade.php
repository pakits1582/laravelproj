{{-- {{ dd($evaluation) }} --}}
@extends('layout')
@section('title') {{ 'Enrollment and Registration' }} @endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Enrollment and Registration</h1>
    <p class="mb-4">Register classes, enrollment and assessment.</p>

    {{-- <div class="card shadow mb-4">
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
    </div> --}}

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row  align-items-center">
                <div class="col-md-8">
                    <h6 class="font-weight-bold text-primary mb-0">Enrollment Information (<span id="period_name">{{ session('periodname') }}</span>)</h6>
                </div>
                <div class="col-md-4 right">
                    <div class="m-0 font-weight-bold" id="status"></div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label for="code"  class="m-0 font-weight-bold text-primary">Student</label>
                </div>
                <div class="col-md-5">
                    ({{ $student->user->idno }}) {{ $student->name }}
                </div>
                <div class="col-md-2">
                    <label for="code"  class="m-0 font-weight-bold text-primary">Enrollment No. & Date</label>
                </div>
                <div class="col-md-3">
                    {{ $enrollment->id }} - {{ \Carbon\Carbon::parse($enrollment->created_at)->format('F d, Y') }}
                </div>
            </div>
            {{-- <div class="row">                  
                <div class="col-md-2">
                    <label for="code"  class="m-0 font-weight-bold text-primary">Enrollment No. & Date</label>
                </div>
                <div class="col-md-3">
                    {{ $enrollment->id }} - {{ \Carbon\Carbon::parse($enrollment->created_at)->format('F d, Y') }}
                </div>
                <div class="col-md-7">
                    <label for="new"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                        <input type="checkbox" class="checkbox" name="new" value="1" id="new" disabled
                        {{ (isset($enrollment) && $enrollment->new == 1) ? 'checked' : '' }}
                        > New</label>
                    <label for="old"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                        <input type="checkbox" class="checkbox" name="old" value="1" id="old" disabled
                        {{ (isset($enrollment) && $enrollment->old == 1) ? 'checked' : '' }}> Old</label>
                    <label for="returnee"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                        <input type="checkbox" class="checkbox" name="returnee" value="1" id="returnee" disabled
                        {{ (isset($enrollment) && $enrollment->returnee == 1) ? 'checked' : '' }}> Returnee</label>
                    <label for="transferee"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                        <input type="checkbox" class="checkbox" name="transferee" value="1" id="transferee" disabled
                        {{ (isset($enrollment) && $enrollment->transferee == 1) ? 'checked' : '' }}> Transferee</label>
                    <label for="cross"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                        <input type="checkbox" class="checkbox" name="cross_enrollee" value="1" id="cross" disabled
                        {{ (isset($enrollment) && $enrollment->cross_enrollee == 1) ? 'checked' : '' }}> Cross Enrollee</label>
                    <label for="foreigner"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                        <input type="checkbox" class="checkbox" name="foreigner" value="1" id="foreigner" disabled
                        {{ (isset($enrollment) && $enrollment->foreigner == 1) ? 'checked' : '' }}> Foreigner</label>
                    <label for="probationary"  class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                        <input type="checkbox" class="checkbox" name="probationary" value="1" id="probationary" disabled
                        {{ (isset($enrollment) && $enrollment->probationary == 1) ? 'checked' : '' }}> Probationary</label>                                            
                </div>
            </div> --}}

            <div class="row">
                <div class="col-md-2">
                    <label for="" class="m-0 font-weight-bold text-primary">Assessment No.</label>
                </div>
                <div class="col-md-3">
                    {{ $enrollment->assessment->id }}
                </div>
                <div class="col-md-2">
                    <label for="" class="m-0 font-weight-bold text-primary">Year Level</label>
                </div>
                <div class="col-md-2">
                    {{ Helpers::yearLevel($enrollment->year_level) }}
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Curriculum</label>
                </div>
                <div class="col-md-2">
                    {{ $enrollment->curriculum->curriculum }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label for="" class="m-0 font-weight-bold text-primary">Section</label>
                </div>
                <div class="col-md-3">
                    {{ $enrollment->section->name }}
                </div>
                <div class="col-md-2">
                    <label for="" class="m-0 font-weight-bold text-primary">Level</label>
                </div>
                <div class="col-md-2">
                    {{ $enrollment->program->level->code }}
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">College</label>
                </div>
                <div class="col-md-2">
                    {{ $enrollment->program->collegeinfo->code }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label for="" class="m-0 font-weight-bold text-primary">Program</label>
                </div>
                <div class="col-md-10">
                    ({{ $enrollment->program->code }}) {{ $enrollment->program->name }}
                </div>
                
            </div>
        </div>
    </div>

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
                                    <th class="w50">Code</th>
                                    <th class="w120 mid">Subject</th>
                                    <th>Description</th>
                                    <th class="w40 mid">Units</th>
                                    <th class="w35 mid">Lec</th>
                                    <th class="w35 mid">Lab</th>
                                    <th class="w300 mid">Schedule</th>
                                    <th class="w100">Section</th>
                                    <th class="w100">Added By</th>
                                </tr>
                            </thead>
                            <tbody class="text-black" id="">
                                @include('enrollment.enrolled_class_subjects')
                            </tbody>
                        </table>
                    </div>
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
                    @include('class.schedule_table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection