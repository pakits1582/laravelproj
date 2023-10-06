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
                        <h6 class="font-weight-bold text-primary mb-0">Classes for Evaluation <span id="period_name">{{ session('periodname') }}</h6>
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
                                    @if (isset($classes_for_evaluation) && count($classes_for_evaluation) > 0)
                                        @foreach ($classes_for_evaluation as $class_for_evaluation)
                                            <tr>
                                                <td class="">{{ $class_for_evaluation->class->code }}</td>
                                                <td class="">{{ $class_for_evaluation->class->sectioninfo->code }}</td>
                                                <td class="">{{ $class_for_evaluation->class->curriculumsubject->subjectinfo->code }}</td>
                                                <td class="">{{ $class_for_evaluation->class->curriculumsubject->subjectinfo->name }}</td>
                                                <td class="mid">{{ $class_for_evaluation->class->units }}</td>
                                                <td class="">{{ $class_for_evaluation->class->schedule->schedule }}</td>
                                                <td class="mid">
                                                    @if($class_for_evaluation->status == 2)
                                                        <span class="h6 font-weight-bold text-success">Evaluated</span>
                                                    @else
                                                        <a href="{{ route('evaluateclass', ['facultyevaluation' => $class_for_evaluation->id]) }}" class="btn btn-sm btn-primary btn-sm btn-icon-split">
                                                            <span class="icon text-white-50">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            <span class="text">Evaluate</span>
                                                        </a>
                                                    @endif
                                                </td>
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