@extends('layout')
@section('title') {{ 'System Configuration' }} @endsection
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">System Configuration</h1>
    <p class="mb-4">System general information, schedules and deadlines.</p>

    <div class="row">

        <div class="col-lg-6">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            <form method="POST" action="{{ route('configurations.update', ['configuration' => $configuration->id ?? '']) }}"  role="form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Circle Buttons -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">General Information</h6>
                    </div>
                    <div class="card-body">
                        <p class="font-italic text-info">Note: School's basic infomation, details provided will be used for report generation.</p>
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="name"  class="m-0 font-weight-bold text-primary py-2">Logo</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="uploadfile signature card border-left-primary shadow" id="school_logo" style="background-image:url({{ ($configuration->logo != '') ? asset('images/'.$configuration->logo) : '' }})">
                                                &nbsp;
                                            </div>
                                            <input type="file" class="hidden" name="logo" id="school_logo_file" accept="image/*" />
                                            @error('logo')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="name"  class="m-0 font-weight-bold text-primary py-2">Name</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="name" placeholder="" class="form-control " value="{{ $configuration->name  ?? '' }}">
                                        </div>
                                    </div>
                                    @error('name')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="address"  class="m-0 font-weight-bold text-primary py-2">Address</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="address" placeholder="" class="form-control " value="{{ $configuration->address  ?? '' }}">
                                        </div>
                                    </div>
                                    @error('address')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="contactno"  class="m-0 font-weight-bold text-primary py-2">Contact #</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="contactno" placeholder="" class="form-control " value="{{ $configuration->contactno  ?? '' }}">
                                        </div>
                                    </div>
                                    @error('contactno')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="accronym"  class="m-0 font-weight-bold text-primary py-2">Accronym</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="accronym" placeholder="" class="form-control " value="{{ $configuration->accronym  ?? '' }}">
                                        </div>
                                    </div>
                                    @error('accronym')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="code"  class="m-0 font-weight-bold text-primary py-2">Period</label>
                                        </div>
                                        <div class="col-md-10">
                                            <select name="current_period" class="form-control">
                                                <option value="">- select period -</option>
                                                @if ($periods)
                                                    @foreach ($periods as $period)
                                                        <option value="{{ $period->id }}"
                                                        @isset($configuration->current_period)
                                                            {{ ($configuration->current_period) ?  ($configuration->current_period == $period->id) ? 'selected' : '' : '' }}
                                                        @endisset
                                                        >{{ $period->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            
                                        </div>
                                    </div>
                                    @error('current_period')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <!-- End -->
                    </div>
                </div>

                <!-- SIGNATORIES -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Signatories</h6>
                    </div>
                    <div class="card-body">
                        <p class="font-italic text-info">Note: Names and signatures provided will be used for report generation.</p>
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="m-0 font-weight-bold text-primary">Name</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="m-0 font-weight-bold text-primary">Initials</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="m-0 font-weight-bold text-primary">Signature</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="president" placeholder="President's Name" class="form-control " value="{{ $configuration->president  ?? '' }}">
                                        </div>
                                        @error('president')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" name="pres_initials" placeholder="" class="form-control " value="{{ $configuration->pres_initials  ?? '' }}">
                                        </div>
                                        @error('pres_initials')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="uploadfile signature card border-left-primary shadow h-100 py-2" id="pres_sig" style="background-image:url({{ ($configuration->pres_sig != '') ? asset('images/'.$configuration->pres_sig) : '' }})" >
                                                &nbsp;
                                            </div>
                                            <input type="file" class="hidden" name="pres_sig" id="pres_sig_file" accept="image/*" />
                                            @error('pres_sig')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="registrar" placeholder="Registrar's Name" class="form-control " value="{{ $configuration->registrar  ?? '' }}">
                                        </div>
                                        @error('registrar')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" name="reg_initials" placeholder="" class="form-control " value="{{ $configuration->reg_initials  ?? '' }}">
                                        </div>
                                        @error('reg_initials')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="uploadfile signature card border-left-primary shadow h-100 py-2" id="reg_sig" style="background-image:url({{ ($configuration->reg_sig != '') ? asset('images/'.$configuration->reg_sig) : '' }})">
                                                &nbsp;
                                            </div>
                                            <input type="file" class="hidden" name="reg_sig" id="reg_sig_file" accept="image/*" />
                                            @error('reg_sig')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="treasurer" placeholder="Tresurer's Name" class="form-control " value="{{ $configuration->treasurer  ?? '' }}">
                                        </div>
                                        @error('treasurer')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" name="tres_initials" placeholder="" class="form-control " value="{{ $configuration->tres_initials  ?? '' }}">
                                        </div>
                                        @error('tres_initials')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="uploadfile signature card border-left-primary shadow h-100 py-2" id="tres_sig" style="background-image:url({{ ($configuration->tres_sig != '') ? asset('images/'.$configuration->tres_sig) : '' }})">
                                                &nbsp;
                                            </div>
                                            <input type="file" class="hidden" name="tres_sig" id="tres_sig_file" accept="image/*" />
                                            @error('tres_sig')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- APPLICATION AND ENROLMENT SETTINGS -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Online Application, Enrolment, Assessment Settings</h6>
                    </div>
                    <div class="card-body">
                        <p class="font-italic text-info">Online application schedule</p>
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="code"  class="m-0 font-weight-bold text-primary py-2">Period</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="application_period" class="form-control">
                                            <option value="">- select period -</option>
                                            @if ($periods)
                                                @foreach ($periods as $period)
                                                    <option value="{{ $period->id }}"
                                                        @isset($configuration->application_period)
                                                            {{ ($configuration->application_period) ?  ($configuration->application_period == $period->id) ? 'selected' : '' : '' }}
                                                        @endisset
                                                    >{{ $period->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        
                                    </div>
                                </div>
                                @error('application_period')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="code"  class="m-0 font-weight-bold text-primary py-2">Schedule</label>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" name="datefrom" placeholder="" class="form-control datepicker" value="{{ $configuration->datefrom  ?? '' }}">
                                        </div>
                                        @error('datefrom')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" name="dateto" placeholder="" class="form-control datepicker" value="{{ $configuration->dateto  ?? '' }}">
                                        </div>
                                        @error('dateto')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="code"  class="m-0 font-weight-bold text-primary py-2">Status</label>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800 py-2">
                                            {{ (isset($configuration->status)) ?  ($configuration->status == 1) ? 'OPEN' : 'CLOSED' : 'CLOSED' }}
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        @if ($configuration->status == 0)
                                            <a href="#" id="open" data-id="{{ $configuration->id }}" class="applicationaction btn btn-primary btn-icon-split mx-2 align-middle">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-door-open"></i>
                                                </span>
                                                <span class="text">Open Application</span>
                                            </a>
                                        @else
                                            <a href="#" id="close" data-id="{{ $configuration->id }}" class="applicationaction btn btn-info btn-icon-split mx-2 align-middle">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-door-closed"></i>
                                                </span>
                                                <span class="text">Close Application</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                @error('current_period')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="balanceallowed" class="m-0 font-weight-bold text-primary">Balance Allowed</label>
                                        <input type="text" name="balanceallowed" placeholder="" class="form-control " value="{{ $configuration->balanceallowed  ?? '' }}">
                                        @error('balanceallowed')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="due" class="m-0 font-weight-bold text-primary">Due (Days)</label>
                                        <input type="text" name="due" placeholder="" class="form-control " value="{{ $configuration->due  ?? '' }}">
                                        @error('due')
                                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <p class="font-italic text-info">Note: Please use this tag DUE to add duedate on assessment note</p>
                            <div class="form-group">
                                <label for="note"  class="m-0 font-weight-bold text-primary">Assessment Note</label>
                                <textarea name="note" class="form-control" rows="3">{{ $configuration->note  ?? '' }}</textarea>
                                @error('note')
                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                @enderror
                            </div>
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save System Configuration</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-6">
            @if(Session::has('sched_message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('sched_message') }}</p>
            @endif
            <form method="POST" action="{{ route('configurations.store') }}"  role="form">
                @csrf
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Schedules and Deadlines</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="type" class="m-0 font-weight-bold text-primary">* Schedule Type</label>
                                    <select name="type" class="form-control">
                                        <option value="">- select type -</option>
                                        <option {{ (old('type') == 'enrolment') ? 'selected' : '' }} value="enrolment">Student Enrolment</option>
                                        <option {{ (old('type') == 'addingdropping') ? 'selected' : '' }} value="addingdropping">Adding Dropping</option>
                                        <option {{ (old('type') == 'student_registration') ? 'selected' : '' }} value="student_registration">Student Online Registration</option>
                                        <option {{ (old('type') == 'grade_posting') ? 'selected' : '' }} value="grade_posting" class="nodateto">Student Grade Viewing</option>
                                        <option {{ (old('type') == 'final_grade_submission') ? 'selected' : '' }} value="final_grade_submission" class="noyear">Faculty Final Grade Submission</option>
                                        <option {{ (old('type') == 'facultyload_posting') ? 'selected' : '' }} value="facultyload_posting" class="nodateto">Faculty Load Posting</option>
                                        <option {{ (old('type') == 'class_scheduling') ? 'selected' : '' }} value="class_scheduling" class="nodateto">Class Scheduling</option>
                                        <option {{ (old('type') == 'faculty_evaluation') ? 'selected' : '' }} value="faculty_evaluation" class="">Faculty Evaluation</option>
                                    </select>
                                    @error('type')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                    @include('partials.educlevels.dropdown')
                                    @error('educational_level')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                    @include('partials.colleges.dropdown')
                                    @error('college')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="year" class="m-0 font-weight-bold text-primary">Year Level</label>
                                    <select name="year" class="form-control">
                                        <option value="">- select year -</option>
                                        <option value="1" {{ (old('year') == 1) ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ (old('year') == 2) ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ (old('year') == 3) ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ (old('year') == 4) ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ (old('year') == 5) ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ (old('year') == 6) ? 'selected' : '' }}>6</option>
                                    </select>
                                    @error('year')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="date_from" class="m-0 font-weight-bold text-primary">* Date From</label>
                                    <input type="text" name="date_from" placeholder="" class="form-control datepicker" value="{{ old('date_from') }}">
                                    @error('date_from')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="date_to" class="m-0 font-weight-bold text-primary">Date To</label>
                                    <input type="text" name="date_to" placeholder="" class="form-control datepicker" value="{{ old('date_to') }}">
                                    @error('date_to')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Schedule</button>
                    </div>
                </div>
            </form>

            @foreach ($configgrouped as $type => $schedules)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ Helpers::getConfigschedtype($type) }}</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th scope="col">Level</th>
                            <th scope="col">College</th>
                            <th scope="col">Year</th>
                            <th scope="col">Date From</th>
                            <th scope="col">Date To</th>
                            <th scope="col" class="mid"><i class="fas fa-fw fa-cog text-primary"></i></th>
                          </tr>
                        </thead>
                        <tbody> 
                            @foreach($schedules as $schedule)
                                <tr>
                                    <th scope="row">{{ $schedule->level->level }}</th>
                                    <td>{{ $schedule->collegeinfo->code }}</td>
                                    <td>{{ $schedule->year }}</td>
                                    <td>{{ $schedule->date_from }}</td>
                                    <td>{{ $schedule->date_to }}</td>
                                    <td class="mid"><a href="#" id="{{ $schedule->id }}" class="deleteconfigsched btn btn-danger btn-circle btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
<!-- /.container-fluid -->
@endsection