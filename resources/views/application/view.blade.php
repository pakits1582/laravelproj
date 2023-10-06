@extends('layout')
@section('title') {{ 'View Application' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">View Application</h1>
        <p class="mb-4">Managing of student's applications.</p>
        
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-0">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Student's Application for {{ $applicant->entryperiod->name }}</h6>
                    </div>
                    <div class="card-body p-0">
                        @include('application.partials.manage_application')
                        
                        <div class="row m-0">
                            <div class="col-lg-6">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                                    </div>
                                    <div class="card-body" id="">
                                        <div class="form-group">
                                            <p class="font-italic font-weight-bold text-info">Note: LEGAL NAME (Name on Birth Certificate)</p>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-md-4">
                                                    <div id="picture_preview" class="image-upload-preview">
                                                        <img src="{{ Storage::url($applicant->picture) }}" alt="Image">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <h1 class="m-0 font-weight-bold text-black">{{ $applicant->name }}</h1>
                                                    {{-- <div class="col-md-12">
                                                        <label for="entry_period" class="m-0 font-weight-bold text-primary">Last Name</label>
                                                        <div class="displaydata">{{ $applicant->personal_info->civil_status ?? '' }}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="entry_period" class="m-0 font-weight-bold text-primary">First Name</label>
                                                        <div class="displaydata">{{ $applicant->personal_info->civil_status ?? '' }}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="entry_period" class="m-0 font-weight-bold text-primary">Middle Name</label>
                                                        <div class="displaydata">{{ $applicant->personal_info->civil_status ?? '' }}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="entry_period" class="m-0 font-weight-bold text-primary">Name Suffix</label>
                                                        <div class="displaydata">{{ $applicant->personal_info->civil_status ?? '' }}</div>
                                                    </div> --}}
                                                </div>
                                            </div>

                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="sex" class="m-0 font-weight-bold text-primary">* Sex</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ \App\Models\Student::SEX[$applicant->sex] }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="civil_status" class="m-0 font-weight-bold text-primary">* Civil Status</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->personal_info->civil_status ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="birth_date" class="m-0 font-weight-bold text-primary">* Birth Date</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->personal_info->birth_date ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="birth_place" class="m-0 font-weight-bold text-primary">Birth Place</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->personal_info->birth_place ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="nationality" class="m-0 font-weight-bold text-primary">* Nationality</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->personal_info->nationality ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="religion" class="m-0 font-weight-bold text-primary">* Religion</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ \App\Models\Student::RELIGIONS[$applicant->personal_info->religion] }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-5">
                                                <div class="col-md-3 col-sm-3"></div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->personal_info->religion_specify ?? '' }}</div>
                                                </div>
                                            </div>
                                
                                            <p class="font-italic font-weight-bold text-info">Please mark whether you have received the following sacraments: (Mark N/A if no applicable)</p>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-md-3 col-sm-3">
                                                    <label  class="m-0 font-weight-bold text-primary">Baptism</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ \App\Models\Student::SACRAMENTS[$applicant->personal_info->baptism] }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-md-3 col-sm-3">
                                                    <label  class="m-0 font-weight-bold text-primary">First Communion</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ \App\Models\Student::SACRAMENTS[$applicant->personal_info->communion] }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-md-3 col-sm-3">
                                                    <label  class="m-0 font-weight-bold text-primary">Confirmation</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ \App\Models\Student::SACRAMENTS[$applicant->personal_info->confirmation] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                                    </div>
                                    <div class="card-body" id="">
                                        <div class="form-group">
                                            <p class="font-italic font-weight-bold text-info mb-0">Note: Please provide your correct contact information and addresses.</p>
                                            <div class="row align-items-center mb-3">
                                                <div class="col-md-12">
                                                    <h6 class="m-0 font-weight-bold text-black">Current Address</h6>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="current_region" class="m-0 font-weight-bold text-primary">* Region</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->current_region ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="current_province" class="m-0 font-weight-bold text-primary">* Province</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->current_province ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="current_municipality" class="m-0 font-weight-bold text-primary">* City/Municipality</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->current_municipality ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="current_barangay" class="m-0 font-weight-bold text-primary">* Barangay</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->current_barangay ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="current_address" class="m-0 font-weight-bold text-primary">* House #, Street</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->current_address ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-3">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="current_zipcode" class="m-0 font-weight-bold text-primary">* Zip Code</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->current_zipcode ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-3">
                                                <div class="col-md-12">
                                                    <h6 class="m-0 font-weight-bold text-black">Permanent Address</h6>
                                                    <p class="font-italic font-weight-bold text-info mb-0">Note: (If not the same as current address)
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="permanent_region" class="m-0 font-weight-bold text-primary">Region</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->permanent_region ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="permanent_province" class="m-0 font-weight-bold text-primary">Province</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->permanent_province ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="permanent_municipality" class="m-0 font-weight-bold text-primary">City/Municipality</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->permanent_municipality ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="permanent_barangay" class="m-0 font-weight-bold text-primary">Barangay</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->permanent_barangay ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="permanent_address" class="m-0 font-weight-bold text-primary">House #, Street</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->permanent_address ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-5">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="permanent_zipcode" class="m-0 font-weight-bold text-primary">Zip Code</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->permanent_zipcode ?? '' }}</div>
                                                </div>
                                            </div>
                                
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="telno" class="m-0 font-weight-bold text-primary">Telephone No.</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->telno ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="mobileno" class="m-0 font-weight-bold text-primary">* Mobile No.</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->mobileno ?? '' }}</div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3 col-sm-3">
                                                    <label for="email" class="m-0 font-weight-bold text-primary">* E-mail Address</label>
                                                </div>
                                                <div class="col-md-9 col-sm-9">
                                                    <div class="displaydata">{{ $applicant->contact_info->email ?? '' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-lg-12">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Academic Information</h6>
                                    </div>
                                    <div class="card-body" id="">
                                        <div class="form-group" id="">
                                            <div class="row align-items-center mb-1">
                                                <div class="col-lg-1 d-none d-lg-block">
                                                    <h6 class="m-0 font-weight-bold text-black text-center">Level</h6>                
                                                </div>
                                                <div class="col-lg-3 d-none d-lg-block">
                                                    <h6 class="m-0 font-weight-bold text-black text-center">Program</h6>
                                                </div>
                                                <div class="col-lg-3 d-none d-lg-block">
                                                    <h6 class="m-0 font-weight-bold text-black text-center">Name of School</h6>
                                                </div>
                                                <div class="col-lg-3 d-none d-lg-block">
                                                    <h6 class="m-0 font-weight-bold text-black text-center">Address</h6>
                                                </div>
                                                <div class="col-lg-2 d-none d-lg-block">
                                                    <h6 class="m-0 font-weight-bold text-black text-center">Period Covered</h6>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-lg-1">
                                                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">Elementary</label>
                                                    <h6 class="d-lg-none mb-1 mt-3 font-weight-bold text-black">Elementary</h6>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="displaydata"></div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="elem_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->elem_school }}</div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="elem_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->elem_address }}</div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label for="elem_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->elem_period }}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="row align-items-center mb-1">
                                                <div class="col-lg-1">
                                                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">Junior High</label>
                                                    <h6 class="d-lg-none mb-1 mt-3 font-weight-bold text-black">Junior High</h6>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="displaydata"></div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="jhs_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->jhs_school }}</div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="jhs_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->jhs_address }}</div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label for="jhs_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->jhs_period }}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="row align-items-center mb-1">
                                                <div class="col-lg-1">
                                                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">Senior High</label>
                                                    <h6 class="d-lg-none mb-1 mt-3 font-weight-bold text-black">Senior High</h6>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-lg-7">
                                                            <label for="shs_strand" class="d-lg-none m-0 font-weight-bold text-primary">Strand</label>
                                                            <div class="displaydata">{{ $applicant->academic_info->shs_strand }}</div>   
                                                        </div>
                                                        <div class="col-lg-5 col-lg pl-lg-0">
                                                            <label for="shs_techvoc_specify" class="d-lg-none m-0 font-weight-bold text-primary">Tech-Voc Specify</label>
                                                            <div class="displaydata">{{ $applicant->academic_info->shs_techvoc_specify }}</div>
                                                        </div>
                                                    </div>            
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="shs_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->shs_school }}</div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="shs_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->shs_address }}</div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label for="shs_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->shs_period }}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="row align-items-center mb-1">
                                                <div class="col-lg-1">
                                                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">College</label>
                                                    <h6 class="d-lg-none mb-1 mt-3 font-weight-bold text-black">College</h6>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="college_program" class="d-lg-none m-0 font-weight-bold text-primary">Program</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->college_program }}</div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="college_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->college_school }}</div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="college_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->college_address }}</div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label for="college_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->college_period }}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="row align-items-center mb-1">
                                                <div class="col-lg-1">
                                                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">Graduate</label>
                                                    <h6 class="d-lg-none mb-1 mt-3 font-weight-bold text-black">Graduate Studies</h6>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="graduate_program" class="d-lg-none m-0 font-weight-bold text-primary">Program</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->graduate_program }}</div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="graduate_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->graduate_school }}</div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="graduate_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->graduate_address }}</div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label for="graduate_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                                                    <div class="displaydata">{{ $applicant->academic_info->graduate_period }}</div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-lg-12">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Family Information</h6>
                                    </div>
                                    <div class="card-body" id="">
                                        <div class="form-group" id="">
                                            <div class="row">
                                                <div class="col-lg-4 mb-3">
                                                    <div class="card border-left-info h-100">
                                                        <div class="card-body p-2">                    
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-5 col-sm-5">
                                                                    <h6 class="mb-2 font-weight-bold text-black">Father</h6>
                                                                </div>
                                                                <div class="col-lg-7 col-sm-7">
                                                                    <label for="fatheralive"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="father_alive" value="1" id="fatheralive"  @if(!isset($applicant) || $applicant->personal_info->father_alive == 1) checked @endif> Living </label>
                                                                    <label for="fatherdeceased"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="father_alive" value="2" id="fatherdeceased"  @if(isset($applicant) && $applicant->personal_info->father_alive == 2) checked @endif> Deceased </label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="father_name" class="m-0 font-weight-bold text-primary">Name</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->father_name }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="father_contactno" class="m-0 font-weight-bold text-primary">Contact Number</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->father_contactno }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="father_address" class="m-0 font-weight-bold text-primary">Home Address</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->father_address }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-2">
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-5 col-sm-5">
                                                                    <h6 class="mb-2 font-weight-bold text-black">Mother</h6>
                                                                </div>
                                                                <div class="col-lg-7 col-sm-7">
                                                                    <label for="motheralive" class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="mother_alive" value="1" id="motheralive"  @if(!isset($applicant) || $applicant->personal_info->mother_alive == 1) checked @endif> Living </label>
                                                                    <label for="motherdeceased" class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="mother_alive" value="2" id="motherdeceased" @if(isset($applicant) && $applicant->personal_info->mother_alive == 2) checked @endif> Deceased </label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="mother_name" class="m-0 font-weight-bold text-primary">Name</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->mother_name }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="mother_contactno" class="m-0 font-weight-bold text-primary">Contact Number</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->mother_contactno }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="mother_address" class="m-0 font-weight-bold text-primary">Home Address</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->mother_address }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <div class="card border-left-info h-100">
                                                        <div class="card-body p-2">
                                                            <h6 class="mb-2 font-weight-bold text-black">Guardian/Spouse (If married)</h6>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="guardian_name" class="m-0 font-weight-bold text-primary">Name</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->guardian_name }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="guardian_relationship" class="m-0 font-weight-bold text-primary">Relationship</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->guardian_relationship }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="guardian_contactno" class="m-0 font-weight-bold text-primary">Contact Number</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->guardian_contactno }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="guardian_address" class="m-0 font-weight-bold text-primary">Home Address</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->guardian_address }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="guardian_occupation" class="m-0 font-weight-bold text-primary">Occupation</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->guardian_occupation }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="guardian_employer" class="m-0 font-weight-bold text-primary">Company/Employer</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->guardian_employer }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="guardian_employer_address" class="m-0 font-weight-bold text-primary">Company Address</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->guardian_employer_address }}</div>
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <div class="card border-left-info h-100">
                                                        <div class="card-body p-2">
                                                            <h6 class="mb-2 font-weight-bold text-black">Work Details (If applicant is working)</h6>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="occupation" class="m-0 font-weight-bold text-primary">Present Occupation</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->occupation }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="employer" class="m-0 font-weight-bold text-primary">Company/Employer</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->employer }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="employer_address" class="m-0 font-weight-bold text-primary">Company Address</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->employer_address }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="employer_contact" class="m-0 font-weight-bold text-primary">Company Contact No.</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->employer_contact }}</div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="occupation_years" class="m-0 font-weight-bold text-primary">Years of Service</label>
                                                                    <div class="displaydata">{{ $applicant->personal_info->occupation_years }}</div>
                                                                </div>
                                                            </div>

                                                            <h6 class="mt-3 font-weight-bold text-black">Contact Details</h6>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label for="contact_email" class="m-0 font-weight-bold text-primary">* Contact E-mail</label>
                                                                    <div class="displaydata">{{ $applicant->contact_info->contact_email }}</div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="contact_no" class="m-0 font-weight-bold text-primary">* Contact Number</label>
                                                                    <div class="displaydata">{{ $applicant->contact_info->contact_no }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-lg-12">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Attached Requirements</h6>
                                    </div>
                                    <div class="card-body" id="">
                                        @if ($applicant->report_card)
                                            @php
                                                $reportcards = explode(',', $applicant->report_card);
                                            @endphp
                                           
                                            @foreach ($reportcards as $report_card)
                                                <div class="attached_credentials p-3">
                                                    <img src="{{ Storage::url($report_card) }}" alt="Image">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <!-- /.container-fluid -->
@endsection