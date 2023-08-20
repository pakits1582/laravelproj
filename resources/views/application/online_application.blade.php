<!DOCTYPE html>
<html lang="en">

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
                                <h4 class="m-0 font-weight-bold text-primary">Online Application for {{ $configuration->applicationperiod->name ?? '' }}</h4>
                            </div>
                            <div class="card-body p-0">
                                <form method="POST" id="form_application" action="" enctype="multipart/form-data">
                                    @csrf
        
                                    @include('application.partials.instruction_classification')
                                    
                                    <div class="row m-0">
                                        <div class="col-lg-6">
                                            @include('application.partials.personal_information')
                                        </div>
                                        <div class="col-lg-6">
                                            @include('application.partials.contact_information')
                                        </div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="col-lg-12">
                                            @include('application.partials.academic_information')
                                        </div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="col-lg-12">
                                            @include('application.partials.family_information')
                                        </div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="col-lg-12">
                                            @include('application.partials.attach_requirements')
                                        </div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="col-lg-12">
                                            @include('application.partials.terms_and_condition')
                                        </div>
                                    </div>
                                    <div class="row p-3">
                                        <div class="col-lg-12">
                                            <input type="submit" name="" id="" class="btn btn-primary btn-user btn-block btn-lg" value="Submit Application">
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