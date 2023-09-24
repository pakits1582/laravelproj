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
                        <h1 class="h3 text-800 text-primary mb-0">Classes for Evaluation <span id="period_name">{{ session('periodname') }}</span></h1>
                    </div>
                    <div class="col-md-5 right"></div>
                </div>
            </div>
            <div class="card-body">
                @if ($enrollment)
                    @if($enrollment->assessed == 1 && $enrollment->validated == 1)
                        <div id="">
                            <table id="" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
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
                                    @php
                                        $classesforevaluation = (isset($enrollment->facultyevaluations)) ? $enrollment->facultyevaluations : $classes_for_evaluation;
                                    @endphp
                                    @dd($classesforevaluation)
                                    @if (isset($classesforevaluation) && count($classesforevaluation) > 0)
                                        @foreach ($classesforevaluation as $class)
                                            <tr>
                                                <td class="">{{ $class->code }}</td>
                                                <td class="">{{ $class->sectioninfo->code }}</td>
                                                <td class="">{{ $class->curriculumsubject->subjectinfo->code }}</td>
                                                <td class="">{{ $class->curriculumsubject->subjectinfo->name }}</td>
                                                <td class="mid">{{ $class->units }}</td>
                                                <td class="">{{ $class->schedule->schedule }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th class="">&nbsp;</th>
                                            <th class="">&nbsp;</th>
                                            <th class="">&nbsp;</th>
                                            <th class="">&nbsp;</th>
                                            <th class="">&nbsp;</th>
                                            <th class="">&nbsp;</th>
                                            <th class="">&nbsp;</th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>                    
                        </div>
                    @else
                        <h1 class="text-danger h3 mid">Student enrollment is not yet validated!</h1>
                    @endif
                @else
                    <h1 class="text-danger h3 mid">You are not currently enrolled this semester!</h1>
                @endif
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection