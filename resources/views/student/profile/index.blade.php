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
                        <input type="file" class="upload" name="picture" id="picture" accept="image/*" />
                        <input type="hidden" name="id" value="{{ $student->id }}" />
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            @include('partials.student.information')
        </div>
    </div>

    <form method="POST" id="form_student_profile" action="">
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