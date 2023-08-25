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
                        <h4 class="m-0 font-weight-bold text-primary">Student's Application for {{ $applicant->entryperiod->name }}</h4>
                    </div>
                    <div class="card-body p-0">
                        @include('application.partials.manage_application')
                        
                        <div class="row m-0">
                            <div class="col-lg-6">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h4 class="m-0 font-weight-bold text-primary">Personal Information</h4>
                                    </div>
                                    <div class="card-body" id="">
                                        <div class="form-group">
                                            <p class="font-italic font-weight-bold text-info">Note: LEGAL NAME (Name on Birth Certificate)</p>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-md-3">
                                                    <div id="picture_preview" class="image-upload-preview">
                                                        <img src="{{ Storage::url('image_uploads/169293421714.png') }}" alt="Image">
                                                    </div>
                                                </div>
                                                <div class="col-md-9 p-0">
                                                    <div class="col-md-12">
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
                                                    </div>
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
                                        <h4 class="m-0 font-weight-bold text-primary">Contact Information</h4>
                                    </div>
                                    <div class="card-body" id="">
                                        <div class="form-group">
                                            <p class="font-italic font-weight-bold text-info mb-0">Note: Please provide your correct contact information and addresses.</p>
                                            <div class="row align-items-center mb-3">
                                                <div class="col-md-12">
                                                    <h5 class="m-0 font-weight-bold text-black">Current Address</h5>
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
                                                    <h5 class="m-0 font-weight-bold text-black">Permanent Address</h5>
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
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <!-- /.container-fluid -->
@endsection