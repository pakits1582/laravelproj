@extends('layout')
@section('title') {{ 'Classes Slots Monitoring' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Classes Slots Monitoring</h1>
        <p class="mb-4">List of all classes offering of current term and their size.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="font-weight-bold text-primary mb-0">Classes Slots Monitoring <span id="period_name">{{ session('periodname') }}</span></h6>
            </div>
            <div class="card-body">
                <form method="POST" id="form_filterslotmonitoring" action="">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level', 'fieldid' => 'educational_level', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                @include('partials.colleges.dropdown', ['fieldname' => 'college', 'fieldid' => 'college', 'fieldclass' => 'filter_item'])
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="program_id" class="m-0 font-weight-bold text-primary">Section</label>
                                <select name="section_id" class="form-control filter_item" id="section_id">
                                    <option value="">- select section -</option>
                                    @if ($sections_offered)
                                        @foreach ($sections_offered as $key => $section)
                                            <option value="{{ $section->section->id }}" >{{ $section->section->code }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">Keyword</label>
                                <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control" id="search_keyword">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">Dissolved</label>
                                <div class="legend dissolved"></div>   
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">Tutorial</label>
                                <div class="legend tutorial"></div>   
                            </div>
                        </div>
                        

                    </div>
                </form>
                <div id="return_slotmonitoring">
                    @include('class.slotsmonitoring.return_slotmonitoring')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Schedule Table</h6>
                    </div>
                    <div class="card-body">
                        <div id="schedule_table">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection