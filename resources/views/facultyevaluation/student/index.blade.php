@extends('layout')
@section('title') {{ 'Faculty Evaluation' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Faculty Performance Appraisal</h1>
        <p class="mb-4">Evaluate classes' faculty performance.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h1 class="h3 text-800 text-primary mb-0">Faculty Evaluation <span id="period_name">{{ session('periodname') }}</span></h1>
                    </div>
                    <div class="col-md-5 right"></div>
                </div>
            </div>
            <div class="card-body">
                <div id="">
                    <table id="scrollable_table_evaluation_results" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
                        <thead>
                            <tr>
                                <th class="w50">Code</th>
                                <th class="w120">Section</th>
                                <th class="w120">Subject Code</th>
                                <th class="w300">Subject Name</th>
                                <th class="w30">Units</th>
                                <th class="w200">Schedule</th>
                                <th class="w80">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @if (count($classeswithslots) > 0)
                                @foreach ($classeswithslots as $class)
                                    @if ($class['dissolved'] == 1)
                                        <tr class="dissolved">
                                    @elseif($class['tutorial'] == 1)
                                        <tr class="tutorial">
                                    @else
                                        <tr>
                                    @endif
                                        @php
                                            $faculty = '';
                                            if ($class['instructor_id'] != NULL)
                                            {
                                                $fname = explode(" ", $class['first_name']);
                                                $acronym = "";
                                                foreach ($fname as $w) {
                                                    $acronym .= $w[0];
                                                }
                                                $faculty = ($class['first_name'] == '(TBA)') ? 'TBA' : $acronym.'. '.$class['last_name'];
                                            }
                                        @endphp
                                        <td class="w50">{{ $class['class_code'] }}</td>
                                        <td class="w120">{{ $class['section_code'] }}</td>
                                        <td class="w120"><b>{{ ($class['mothercode'] != '') ? '('.$class['mothercode'].') ' : '' }}</b>{{ $class['subject_code'] }}</td>
                                        <td class="w300">{{ $class['subject_name'] }}</td>
                                        <td class="w30 mid">{{ $class['units'] }}</td>
                                        <td class="w200">{{ $class['schedule'] }}</td>
                                        <td class="w120">{{ $faculty }}</td>
                                        <td class="w30 mid">{{ $class['totalvalidated'] }}</td>
                                        <td class="w30 mid">{{ $class['totalrespondents'] }}</td>
                                        <td class="w30 mid">
                                            <a href="#" class="btn btn-primary btn-circle btn-sm view_respondents" id="{{ $class['class_id'] }}" title="View Respondents">
                                                <i class="fas fa-list"></i>
                                            </a>
                                        </td>
                                        <td class="mid">
                                            <a href="{{ route('facultyevaluations.viewresult', ['class' => $class['class_id']]) }}" target="_blank" class="btn btn-success btn-circle btn-sm" id="{{ $class['class_id'] }}" title="View Result">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-circle btn-sm reset_evaluation" id="{{ $class['class_id'] }}" title="Reset Evaulation">
                                                <i class="fas fa-undo"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th class="w50">&nbsp;</th>
                                    <th class="w120">&nbsp;</th>
                                    <th class="w120">&nbsp;</th>
                                    <th class="w300">&nbsp;</th>
                                    <th class="w30">&nbsp;</th>
                                    <th class="w200">&nbsp;</th>
                                    <th class="w120">&nbsp;</th>
                                    <th class="w30">&nbsp;</th>
                                    <th class="w30">&nbsp;</th>
                                    <th class="w30">&nbsp;</th>
                                    <th class="w30">&nbsp;</th>
                                </tr>
                            @endif --}}
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection