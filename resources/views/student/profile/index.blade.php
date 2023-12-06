@extends('layout')
@section('title') {{ 'Student\'s Profile' }} @endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Student's Profile</h1>
    <p class="mb-4">View and update personal, academic and other informations.</p>

    <div class="row align-items-start mb-2">
        @php
            $url = ($student->picture == NULL) ? 'image_uploads/def.png' : $student->picture;
        @endphp
        <div class="col-md-2 mb-1">
            <div id="picture_preview" class="image-upload-preview" style="background-image:url({{ Storage::url($url) }})">
                {{-- <img src="{{ Storage::url($url) }}" alt="Image"> --}}
                <div class="fileUpload">
                    <span>Change current photo</span>
                    <form method="post" enctype="multipart/form-data" id="changePhoto">
                        @csrf
                        <input type="file" class="upload" name="imagefile" id="file" accept="image/*" />
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card shadow">
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
        </div>
    </div>

    <form method="POST" id="form_student_profile" action="" enctype="multipart/form-data">
        @csrf        
        <div class="row m-0">
            <div class="col-lg-6 pl-0">
                @include('student.profile.personal_information')
            </div>
            <div class="col-lg-6 pr-0">
                @include('student.profile.contact_information')
            </div>
        </div>
        <div class="row m-0">
            <div class="col-lg-12 px-0">
                @include('student.profile.academic_information')
            </div>
        </div>
        <div class="row m-0">
            <div class="col-lg-12 px-0">
                @include('student.profile.family_information')
            </div>
        </div>
        <div class="row m-0">
            <div class="col-lg-12 px-0">
                @include('student.profile.other_information')
            </div>
        </div>
        <div class="row p-3">
            <div class="col-lg-12">
                <input type="hidden" name="id" id="" value="{{ $student->id ?? '' }}" >
                <input type="submit" name="" id="" class="btn btn-primary btn-user btn-block btn-lg" value="Update Profile">
            </div>
        </div>
    </form>
</div>
@endsection