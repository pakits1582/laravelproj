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
                                            <a href="#" id="open" class="manageapplication btn btn-primary btn-icon-split mx-2 align-middle">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-door-open"></i>
                                                </span>
                                                <span class="text">Open Application</span>
                                            </a>
                                        @else
                                            <a href="#" id="close" class="manageapplication btn btn-info btn-icon-split mx-2 align-middle">
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

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Split Buttons with Icon</h6>
                </div>
                <div class="card-body">
                    <p>Works with any button colors, just use the <code>.btn-icon-split</code> class and
                        the markup in the examples below. The examples below also use the
                        <code>.text-white-50</code> helper class on the icons for additional styling,
                        but it is not required.</p>
                    <a href="#" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-flag"></i>
                        </span>
                        <span class="text">Split Button Primary</span>
                    </a>
                    <div class="my-2"></div>
                    <a href="#" class="btn btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="text">Split Button Success</span>
                    </a>
                    <div class="my-2"></div>
                    <a href="#" class="btn btn-info btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Split Button Info</span>
                    </a>
                    <div class="my-2"></div>
                    <a href="#" class="btn btn-warning btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        <span class="text">Split Button Warning</span>
                    </a>
                    <div class="my-2"></div>
                    <a href="#" class="btn btn-danger btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                        </span>
                        <span class="text">Split Button Danger</span>
                    </a>
                    <div class="my-2"></div>
                    <a href="#" class="btn btn-secondary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text">Split Button Secondary</span>
                    </a>
                    <div class="my-2"></div>
                    <a href="#" class="btn btn-light btn-icon-split">
                        <span class="icon text-gray-600">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text">Split Button Light</span>
                    </a>
                    <div class="mb-4"></div>
                    <p>Also works with small and large button classes!</p>
                    <a href="#" class="btn btn-primary btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-flag"></i>
                        </span>
                        <span class="text">Split Button Small</span>
                    </a>
                    <div class="my-2"></div>
                    <a href="#" class="btn btn-primary btn-icon-split btn-lg">
                        <span class="icon text-white-50">
                            <i class="fas fa-flag"></i>
                        </span>
                        <span class="text">Split Button Large</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
<!-- /.container-fluid -->
@endsection