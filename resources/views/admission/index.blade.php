@extends('layout')
@section('title') {{ 'Admissions' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Admissions</h1>
        <p class="mb-4">List and management of student admissions.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h3 text-800 text-primary mb-0">Admissions <span id="period_name">{{ session('periodname') }}</span></h1>
                    </div>
                    <div class="col-md-6 right">
                        <a href="{{ route('onlineadmissions') }}" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="text">Online Admissions</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="filter_form" data-field="admissions">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Period</label>
                                <select name="period_id" class="form-control dropdownfilter" id="period_id">
                                    @if ($periods)
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level', 'fieldid' => 'educational_level', 'fieldclass' => 'dropdownfilter'])
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="program_id" class="m-0 font-weight-bold text-primary">Program</label>
                                <select name="program_id" class="form-control dropdownfilter" id="program_id">
                                    <option value="">- select program -</option>
                                    @if ($programs)
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}">{{ $program->code }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="college" class="m-0 font-weight-bold text-primary">Keyword</label>
                                <input type="text" name="keyword" placeholder="Type keyword to search..." class="filter_item form-control" id="keyword">
                            </div>
                        </div>
                    </div>
                </form>
                <div id="">
                    @include('admission.return_applications')
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ $applicants->onEachSide(1)->links() }}
                        Showing {{ $applicants->firstItem() ?? 0 }} to {{ $applicants->lastItem() ?? 0 }} of total {{$applicants->total()}} entries
                    </div>
                    <div class="col-md-6">
                        <div class="right">
                            <h6 class="m-0 font-weight-bold text-primary">Total Applicants for Admission (<span id="totalcount">{{ count($applicants) ?? 0 }}</span>)</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection