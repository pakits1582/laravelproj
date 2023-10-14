@extends('layout')
@section('title') {{ 'Faculty Evaluation Result' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Faculty Evaluation Result</h1>
        <p class="mb-4">Tabulated result of evaluation survey answers.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h1 class="h3 text-800 text-primary mb-0">Class Evaluation Result for <span id="period_name">{{ session('periodname') }}</span></h1>
                    </div>
                    <div class="col-md-5 right"></div>
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
                <h1 class="h3 text-800 text-primary mb-0">Survey Questions</h1>
            </div> 
            <div class="card-body">
                @if ($result['questions'] !== null && count($result['questions']) > 0)
                    @php
                        $grand_total_answer_value = 0;
                        $grand_total_questions = 0;
                    @endphp

                    @foreach ($result['questions'] as $question)
                        <h4 class="text-primary">&bull; {{ $question['category'] }}</h4>
                        @foreach ($question['subcategory'] as $subcategory)
                            <h5 class="text-success font-italic">{{ $subcategory['subcategory'] }}</h5>
                            @foreach ($subcategory['group'] as $group)
                                <h6 class="text-black font-weight-bolder pl-5"><u>{{ $group['group'] }}</u></h6>
                                <div class="table-responsive">
                                    @php
                                        $question_count = count($group['questions']);
                                        $grand_total_questions += $question_count;
                                        $totalave = 0;
                                    @endphp
                                    <table class="table table-sm table-bordered table-striped">
                                        <tr>
                                            <th>#</th>
                                            <th>Question</th>
                                            <th>Rate</th>
                                        </tr>
                                        @foreach ($group['questions'] as $question)
                                            @php
                                                $total_question = 0;
                                                $total_respondents = count($question['answers']);
                                                $sum_answers = 0;

                                                foreach ($question['answers'] as $key => $answer) 
                                                {
                                                    $sum_answers += Helpers::transval($answer['answer'], $result['class']->curriculumsubject->subjectinfo->educlevel->code);
                                                }

                                                $ave = ($sum_answers != 0 && $total_respondents != 0) ? @($sum_answers/$total_respondents) : 0;
                                            @endphp
                                            <tr>
                                                <td class="w30">{{ $loop->iteration }}</td>
                                                <td>{{ $question['question'] }}</td>
                                                <td class="mid w100">
                                                    <h6 class="m-0 text-black font-weight-bold">{{ number_format($ave, 2) }}</h6>
                                                </td>
                                            </tr>
                                            @php
                                                $totalave += $ave;
                                                $grand_total_answer_value += $ave;
                                            @endphp
                                        @endforeach
                                        @php
                                            $qave = $totalave/$question_count;
                                        @endphp
                                        <tr>
                                            <td class="w30" colspan="2"></td>
                                            <td class="mid w100">
                                                <h5 class="m-0 text-success font-weight-bold">{{ number_format($qave, 2) }}</h5>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach
                    <h4 class="text-primary mt-2">&bull; As a whole, how do you rate your instructor's performance?</h4>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered table-striped">
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Rate</th>
                                </tr>
                                <tr>
                                    <td class="w30">1</td>
                                    <td>As a whole, how do you rate your instructor's performance?</td>
                                    <td class="mid w100">
                                        @php
                                            $overall_rate_count = count($result['overall_rate']);
                                            $total_answer_sum   = 0;
                                            $grand_total_questions += 1;
                                            foreach ($result['overall_rate'] as $key => $answer) 
                                            {
                                                $total_answer_sum += Helpers::transval($answer, $result['class']->curriculumsubject->subjectinfo->educlevel->code);
                                            }

                                            $ave = ($total_answer_sum != 0 && $overall_rate_count != 0) ? @($total_answer_sum/$overall_rate_count) : 0;
                                            $totalave += $ave;
                                            $grand_total_answer_value += $ave;
                                        @endphp
                                        <h6 class="m-0 text-black font-weight-bold">{{ number_format($ave, 2) }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w30" colspan="2"></td>
                                    <td class="mid w100">
                                        <h5 class="m-0 text-success font-weight-bold">{{ number_format($ave, 2) }}</h5>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @php
                        $overallrating = $grand_total_answer_value/$grand_total_questions;
                    @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('facultyevaluations.printresult', ['class' => $result['class']->id]) }}" target="_blank" class="btn btn-danger btn-icon-split actions mb-2">
                                <span class="icon text-white-50">
                                    <i class="fas fa-print"></i>
                                </span>
                                <span class="text">Print Result</span>
                            </a>
                            <a href="{{ route('facultyevaluations.commentsummary', ['class' => $result['class']->id]) }}" target="_blank" class="btn btn-success btn-icon-split actions mb-2">
                                <span class="icon text-white-50">
                                    <i class="fas fa-list"></i>
                                </span>
                                <span class="text">Comments Summary</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h1 class="right text-primary">Overall Rating: {{ number_format($overallrating, 2) }}</h1>
                        </div>
                    </div>
                @else
                    <h3 class="text-danger mid m-3">No survey result to be displayed!</h3>
                @endif
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection