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