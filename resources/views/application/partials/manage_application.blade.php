<div class="row mx-3">
    <div class="col-lg-6 my-3">
        <div class="card border-left-info h-100">
            <div class="card-body p-2">
                <h4 class="mb-2 font-weight-bold text-primary">Instructions</h4>
                <div class="row">
                    <p class="pl-3 font-italic font-weight-bold text-info">Fill out this form carefully and type all information requested. Write N/A if the information is not applicable to you. Omissions can delay the processing of your application.</p>
                    <p class="pl-3 font-italic font-weight-bold text-info">INCOMPLETE APPLICATION FORMS WILL NOT BE PROCESSED.</p>
                    <p class="pl-3 font-italic font-weight-bold text-info">(*) Denotes required fields, you may opt to skip filling up fields without an asterisk.</p>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 mid">
                        <button type="button" id="delete" class="btn btn-danger btn-icon-split mb-2 application_action">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Delete</span>
                        </button>

                        <a href="{{ route('applications.edit', ['application' => $applicant->id ]) }}" id="{{ $applicant->id }}" target="_blank" class="btn btn-primary btn-icon-split mb-2" title="Edit Applicant">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="text">Edit</span>
                        </a>
                    </div>
                    <div class="col-md-6 mid">
                        @if($applicant->application_status == 1)
                            <button type="button" id="accept" class="btn btn-success btn-icon-split mb-2 application_action">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Accept</span>
                            </button>
                            <button type="button" id="reject" class="btn btn-danger btn-icon-split mb-2 application_action">
                                <span class="icon text-white-50">
                                    <i class="fas fa-trash"></i>
                                </span>
                                <span class="text">Reject</span>
                            </button>
                        @else
                            @if ($applicant->application_status == 2)
                                <h3 class="font-weight-bold m-0 text-success">Accepted</h3>
                            @elseif ($applicant->application_status == 3)
                                <h3 class=" font-weight-bold m-0 text-danger">Rejected</h3>
                            @endif

                            <h6 class="font-weight-bold m-0 text-primary">
                                @if ($applicant->assessedby->utype == 0)
                                    {{ $applicant->assessedby->info->name }}
                                @else
                                    {{ $applicant->assessedby->instructorinfo->name }}
                                @endif
                            </h6>
                            <p>
                                {{ \Carbon\Carbon::parse($applicant->assessed_date)->format('F d, Y') }}
                            </p>
                        @endif

                    </div>
                    <input type="hidden" name="applicant" value="{{ $applicant->id }}" id="applicant" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 my-3">
        <div class="card border-left-info h-100">
            <div class="card-body p-2 align-items-end">
                <p class="mb-3 font-italic font-weight-bold text-info">Please submit your old ID number if you are a graduate or returnee from this institution and you are applying to a new program.</p>
                <div class="row">
                    <div class="col-md-12">
                        <label for="application_no" class="m-0 font-weight-bold text-primary">* Application No.</label>
                        <input type="text"  name="application_no" 
                        value="{{ ($applicant->application_no) ? $applicant->application_no : $applicant->entryperiod->code.time() }}"
                         placeholder="" class="form-control text-black biginput" id="application_no">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="idno" class="m-0 font-weight-bold text-primary">ID Number</label>
                        <div class="displaydata">{{ $applicant->user->idno ?? '' }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="classification" class="m-0 font-weight-bold text-primary">* Classification</label>
                        <div class="displaydata">{{ \App\Models\Student::STUDENT_CLASSIFICATION[$applicant->classification] }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="program_id" class="m-0 font-weight-bold text-primary">* Academic Program</label>
                        <div class="displaydata">{{ $applicant->program->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>