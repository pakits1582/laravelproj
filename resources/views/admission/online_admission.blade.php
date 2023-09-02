<!DOCTYPE html>
<html lang="en">

@section('title') {{ 'Online Admission' }} @endsection

@include('partials.header')

<body class="bg-gradient-primary">
    @include('partials.navbar',['dropdown' => false])

    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content" class="pt-3">
            <div class="my-auto mx-auto" style="width: 95% !important;">
                <!-- Outer Row -->
                <div class="row justify-content-center">
                    <div class="col-xl-12 col-lg-12 col-md-9">
                        <div class="card o-hidden border-0 shadow-lg my-5">
                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-primary">Online Admission for {{ $configuration->applicationperiod->name ?? '' }}</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="row mx-3">
                                    <div class="col-lg-12 my-3">
                                        <div class="card border-left-info h-100">
                                            <div class="card-body p-2">
                                                <h4 class="mb-2 font-weight-bold text-primary">Instructions</h4>
                                                <p class="pl-3 font-italic font-weight-bold text-info">Fill out this form carefully and type all information requested. Write N/A if the information is not applicable to you. Omissions can delay the processing of your application.</p>
                                                <p class="pl-3 font-italic font-weight-bold text-info">INCOMPLETE APPLICATION FORMS WILL NOT BE PROCESSED.</p>
                                                <p class="pl-3 font-italic font-weight-bold text-info">(*) Denotes required fields, you may opt to skip filling up fields without an asterisk.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" action="" id="form_admit_applicant">
                                    @csrf
                                    <div class="row p-4">
                                        <div class="col-md-8">
                                            <div class="card shadow mb-4">
                                                <div class="card-header py-3">
                                                    <h6 class="font-weight-bold  text-primary mb-0">Student Information</h6>  
                                                </div>
                                                <div class="card-body">
                                                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-3">
                                                            <label for="idno" class="m-0 font-weight-bold text-primary">* Application Number</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="text" name="application_no" value="" placeholder="Type application no. from your Status of Application" class="biginput form-control text-black" id="application_no">
                                                            <input type="hidden" value="" id="period_id">
                                                            @error('application_no')
                                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                            @enderror
                                                            <div id="error_application_no" class="errors"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-3">
                                                            <label for="" class="m-0 font-weight-bold text-primary">Student Name</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="displaydata" id=""></div>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-3">
                                                            <label for="" class="m-0 font-weight-bold text-primary">Classification</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="displaydata" id=""></div>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-3">
                                                            <label for="program_id" class="m-0 font-weight-bold text-primary">* Program</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="displaydata" id=""></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                
                                            <div class="card shadow">
                                                <div class="card-header py-3">
                                                    <h6 class="font-weight-bold  text-primary mb-0">Personal Information</h6>  
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="row  mb-2">
                                                                <div class="col-md-3"><label for="" class="m-0 font-weight-bold text-primary">Civil Status</label></div>
                                                                <div class="col-md-9">
                                                                    <div class="displaydata" id=""></div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-3"><label for="" class="m-0 font-weight-bold text-primary">Birth Date</label></div>
                                                                <div class="col-md-9">
                                                                    <div class="displaydata" id=""></div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-3"><label for="" class="m-0 font-weight-bold text-primary">Birth Place</label></div>
                                                                <div class="col-md-9">
                                                                    <div class="displaydata" id=""></div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-3"><label for="" class="m-0 font-weight-bold text-primary">Nationality</label></div>
                                                                <div class="col-md-9">
                                                                    <div class="displaydata" id=""></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row mb-2">
                                                                <div class="col-md-4"><label for="" class="m-0 font-weight-bold text-primary">Sex</label></div>
                                                                <div class="col-md-8">
                                                                    <div class="displaydata" id=""></div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4"><label for="" class="m-0 font-weight-bold text-primary">Email Address</label></div>
                                                                <div class="col-md-8">
                                                                    <div class="displaydata" id=""></div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4"><label for="" class="m-0 font-weight-bold text-primary">Mobile No.</label></div>
                                                                <div class="col-md-8">
                                                                    <div class="displaydata" id=""></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card shadow mb-4 h-100">
                                                <div class="card-header py-3">
                                                    <h6 class="font-weight-bold text-primary mb-0">Admission Documents</h6>  
                                                </div>
                                                <div class="card-body">
                                                    
                                                    <p class="font-italic text-info">Note: Please upload required documents and other documents. You can upload files with the ff. file type "jpg","JPG", "png", "pdf", "jpeg", "JPEG".</p>                                                    
                                                        <div id="admission_requirements">

                                                        </div>
                                                        @error('documents_submitted')
                                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                        @enderror
                                                        <div id="error_documents_submitted" class="errors"></div>
                                                    <input type="hidden" name="student_id" value="" >
                                                    <input type="submit" name="" id="" class="btn btn-primary btn-user btn-block btn-lg mt-3" value="Admit Applicant">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    
    @include('partials.footer')
    @include('partials.after_footer')
</body>

</html>