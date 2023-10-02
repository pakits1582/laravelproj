@extends('layout')
@section('title') {{ 'Faculty Evaluation Comments Summary' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Faculty Evaluation Comments Summary</h1>
        <p class="mb-4">Summary of students' comments on faculty's performance.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <h1 class="h3 text-800 text-primary mb-0">Class Evaluation Comments Summary</h1>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Description</label>
                    </div>
                    <div class="col-md-5">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->curriculumsubject->subjectinfo->name }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Code</label>
                    </div>
                    <div class="col-md-3">
                        <div class="font-weight-bold text-black">
                            ({{ $result['class']->code }})
                            {{ $result['class']->curriculumsubject->subjectinfo->code }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Schedule</label>
                    </div>
                    <div class="col-md-5">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->schedule->schedule }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Units</label>
                    </div>
                    <div class="col-md-3">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->units }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Faculty Name</label>
                    </div>
                    <div class="col-md-5">
                        <div class="font-weight-bold text-black">
                        {{ 
                            $result['class']->instructor->last_name.', '.
                            $result['class']->instructor->first_name.' '.
                            $result['class']->instructor->middle_name
                        }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Section</label>
                    </div>
                    <div class="col-md-3">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->sectioninfo->code }}
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Class Size</label>
                    </div>
                    <div class="col-md-6">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->enrolled_and_merged_student_count_validated }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Respondents</label>
                    </div>
                    <div class="col-md-2">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->sectioninfo->code }}
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
       
        <div class="card shadow mb-4">
            <div class="card-header py-2">
                <h1 class="h3 text-800 text-primary mb-0">Comments Summary</h1>
            </div> 
            <div class="card-body">
                <div class="card border-left-success mb-3">
                    <div class="card-body p-2">
                        <h4 class="mb-2 font-weight-bold text-success">Strong Points</h4>
                        @if ($result['strong_points'] !== null && count($result['strong_points']) > 0)
                            <div class="row">
                                @foreach ($result['strong_points'] as $strong_point)
                                    <div class="col-md-6 text-black font-weight-bold">&bull; {{ $strong_point->comment }}</div>

                                    @if ($loop->iteration % 2 == 0)
                                        </div>
                                        <div class="row">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-left-danger mb-3">
                    <div class="card-body p-2">
                        <h4 class="mb-2 font-weight-bold text-danger">Weak Points</h4>
                        @if ($result['weak_points'] !== null && count($result['weak_points']) > 0)
                            <div class="row">
                                @foreach ($result['weak_points'] as $weak_point)
                                    <div class="col-md-6 text-black font-weight-bold">&bull; {{ $weak_point->comment }}</div>

                                    @if ($loop->iteration % 2 == 0)
                                        </div>
                                        <div class="row">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-left-info mb-3">
                    <div class="card-body p-2">
                        <h4 class="mb-2 font-weight-bold text-info">Suggestions for Improvement</h4>
                        @if ($result['suggestions'] !== null && count($result['suggestions']) > 0)
                            <div class="row">
                                @foreach ($result['suggestions'] as $suggestion)
                                    <div class="col-md-6 text-black font-weight-bold">&bull; {{ $suggestion->comment }}</div>

                                    @if ($loop->iteration % 2 == 0)
                                        </div>
                                        <div class="row">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection