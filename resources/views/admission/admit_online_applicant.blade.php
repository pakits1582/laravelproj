@extends('layout')
@section('title') {{ 'Online Admission Application' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Online Admission Application</h1>
        <p class="mb-4">Admit student's online admission application.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 font-weight-bold text-primary mb-0">Online Admission Application <span id="period_name">{{ session('periodname') }}</span></h1> 
            </div>
            <div class="card-body">
                <form method="POST" action="" id="form_admit_applicant">
                    @csrf
                    <div class="row mb-4 ">
                        <div class="col-md-8">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="font-weight-bold  text-primary mb-0">Student Information</h6>  
                                </div>
                                <div class="card-body">
                                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-3">
                                            <label for="idno" class="m-0 font-weight-bold text-primary">* ID Number</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="idno" value="{{ old('idno', $applicant->user->idno ?? '') }}" placeholder="" class="biginput form-control text-black" id="idno">
                                            <input type="hidden" value="{{ $applicant->entry_period }}" id="period_id">

                                            @error('idno')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                            <div id="error_idno" class="errors"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="#" class="btn btn-primary btn-icon-split mt-2" id="generate_idno">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-undo"></i>
                                                </span>
                                                <span class="text">Generate ID</span>
                                            </a>
                                        </div>
                                       
                                    </div>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-3">
                                            <label for="" class="m-0 font-weight-bold text-primary">Student Name</label>
                                        </div>
                                        <div class="col-md-9">
                                            <h3 class="m-0 font-weight-bold text-black">{{ old('name', $applicant->name ?? '') }}</h3>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-3">
                                            <label for="" class="m-0 font-weight-bold text-primary">Classification</label>
                                        </div>
                                        <div class="col-md-9">
                                            <h6 class="m-0 font-weight-bold text-black" id="">
                                                {{ old('classification', \App\Models\Student::STUDENT_CLASSIFICATION[$applicant->classification] ?? '') }}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-3">
                                            <label for="program_id" class="m-0 font-weight-bold text-primary">* Program</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select name="program_id" required class="form-control text-uppercase" id="program_id">
                                                <option value="">- SELECT PROGRAM -</option>
                                                @php
                                                    $groupName = '';
                                                    $first = true;
                                                @endphp
                                                @if($programs)
                                                    @foreach($programs as $program)
                                                        @if ($program->level->level != $groupName)
                                                            @php
                                                                $groupName = $program->level->level; // Just set the new group name
                                                            @endphp

                                                            @if (!$first) <!-- Add a closing tag when we change the group, but only if we're not in the first loop -->
                                                                </optgroup>
                                                            @else
                                                                @php
                                                                    $first = false; // Make sure we don't close the tag first time, but do after the first loop
                                                                @endphp
                                                            @endif
                                                            <optgroup label="{{ $groupName }}">
                                                        @endif
                                                        <option value="{{ $program->id }}"
                                                            @if(old('program_id', $applicant->program_id ?? '') == $program->id) selected @endif
                                                        >
                                                            ({{ $program->code }}) - {{ $program->name }}
                                                        </option>
                                                    @endforeach
                                                    @if (!$first) <!-- Add a closing tag after the last loop -->
                                                        </optgroup>
                                                    @endif
                                                @endif
                                            </select>
                                            @error('program_id')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                            <div id="error_program_id" class="errors"></div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-3">
                                            <label for="curriculum_id" class="m-0 font-weight-bold text-primary">* Curriculum</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select name="curriculum_id" required class="form-control text-uppercase" id="curriculum_id">
                                                @if ($applicant->program->curricula !== null && count($applicant->program->curricula) > 0)
                                                    @foreach ($applicant->program->curricula as $curriculum)
                                                        <option value="{{ $curriculum->id }}">{{ $curriculum->curriculum }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">- NO CURRICULUM -</option>
                                                @endif
                                            </select>
                                            @error('curriculum_id')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                            <div id="error_curriculum_id" class="errors"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="font-weight-bold text-primary mb-0">Admission Documents</h6>  
                                </div>
                                <div class="card-body">
                                    <p class="font-italic text-info">Note: (*) Please select all admission documents submitted by the applicant.</p>
                                    <h6 class="font-weight-bold text-primary mb-3">* Documents Submitted</h6>
                                    @if ($documents !== null && count($documents) > 0)
                                        @php
                                            $admission_documents = [];
                                            
                                            foreach ($documents as $key => $document) 
                                            {
                                                    if($document->program_id == NULL && $document->classification == NULL)
                                                    {
                                                        $admission_documents[] = $document;
                                                    }else{
                                                        if($applicant->program_id == $document->program_id && $document->classification == $applicant->classification)
                                                        {
                                                            $admission_documents[] = $document;
                                                        }

                                                        if($applicant->program_id == $document->program_id && $document->classification == NULL)
                                                        {
                                                            $admission_documents[] = $document;
                                                        }

                                                        if($document->program_id == NULL && $document->classification == $applicant->classification)
                                                        {
                                                            $admission_documents[] = $document;
                                                        }
                                                    }
                                            }
                                        @endphp
                                        @foreach ($admission_documents as $admission_document)
                                            <div>
                                                <label class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="checkbox" class="checkbox" name="documents_submitted[]" value="{{ $admission_document->id }}"> {{ $admission_document->description }}
                                                </label>
                                            </div>
                                        @endforeach
                                        @error('documents_submitted')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                        <div id="error_documents_submitted" class="errors"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-header py-3">
                                            <h6 class="font-weight-bold  text-primary mb-0">Personal Information</h6>  
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-3"><label for="" class="m-0 font-weight-bold text-primary">Civil Status</label></div>
                                                        <div class="col-md-9"><h6 class="font-weight-bold  text-black mb-0">{{ $applicant->personal_info->civil_status }}</h6></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3"><label for="" class="m-0 font-weight-bold text-primary">Birth Date</label></div>
                                                        <div class="col-md-9"><h6 class="font-weight-bold  text-black mb-0">{{ \Carbon\Carbon::parse($applicant->personal_info->birth_date)->format('F d, Y') }}</h6></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3"><label for="" class="m-0 font-weight-bold text-primary">Birth Place</label></div>
                                                        <div class="col-md-9"><h6 class="font-weight-bold  text-black mb-0">{{ $applicant->personal_info->birth_place }}</h6></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3"><label for="" class="m-0 font-weight-bold text-primary">Nationality</label></div>
                                                        <div class="col-md-9"><h6 class="font-weight-bold  text-black mb-0">{{ $applicant->personal_info->nationality }}</h6></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-4"><label for="" class="m-0 font-weight-bold text-primary">Sex</label></div>
                                                        <div class="col-md-8"><h6 class="font-weight-bold  text-black mb-0">{{ \App\Models\Student::SEX[$applicant->sex] ?? '' }}</h6></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4"><label for="" class="m-0 font-weight-bold text-primary">Email Address</label></div>
                                                        <div class="col-md-8"><h6 class="font-weight-bold  text-black mb-0">{{ $applicant->contact_info->email }}</h6></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4"><label for="" class="m-0 font-weight-bold text-primary">Mobile No.</label></div>
                                                        <div class="col-md-8"><h6 class="font-weight-bold  text-black mb-0">{{ $applicant->contact_info->mobileno }}</h6></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow">
                                        <div class="card-header py-3">
                                            <h6 class="font-weight-bold  text-primary mb-0">Attached Documents</h6>  
                                        </div>
                                        <div class="card-body">
                                            @if($applicant->online_documents_submitted)
                                                @foreach ($applicant->online_documents_submitted as $document)
                                                    <h6 class="font-weight-bold  text-primary mb-0">{{ $document->document->description }}</h6>  
                                                    <div class="row mb-2">
                                                        @php
                                                            $documents = explode(',', $document->path);
                                                        @endphp
                                                    
                                                        @foreach ($documents as $document)
                                                            <div class="col-md-6 attached_credentials p-3">
                                                                <img src="{{ Storage::url($document) }}" alt="Image">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="font-weight-bold text-primary mb-0">Contact Details</h6>  
                                </div>
                                <div class="card-body">
                                    <p class="font-italic text-info">Note: Before admitting applicant, please check contact details.</p>                                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="contact_email" class="m-0 font-weight-bold text-primary">* Contact E-mail</label>
                                            <input type="text" name="contact_email" value="{{ $applicant->contact_info->contact_email }}" placeholder="" class="form-control text-black" id="contact_email">
                                            @error('contact_email')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                            <div id="error_contact_email" class="errors"></div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="contact_no" class="m-0 font-weight-bold text-primary">* Contact Number</label>
                                            <input type="text" name="contact_no" value="{{ $applicant->contact_info->contact_no }}" placeholder="" class="form-control text-black" id="contact_no">
                                            @error('contact_no')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                            <div id="error_contact_no" class="errors"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="student" value="{{ $applicant->id }}" >
                                    <input type="submit" name="" id="" class="btn btn-primary btn-user btn-block btn-lg mt-3" value="Admit Applicant">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection