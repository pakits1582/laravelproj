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
                    <div class="col-md-6">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->curriculumsubject->subjectinfo->name }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Code</label>
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-6">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->schedule->schedule }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Units</label>
                    </div>
                    <div class="col-md-2">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->units }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Faculty Name</label>
                    </div>
                    <div class="col-md-6">
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
                    <div class="col-md-2">
                        <div class="font-weight-bold text-black">
                            {{ $result['class']->sectioninfo->code }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
                <div class="card shadow mb-4">
                    <div class="card-header py-2">
                        <h1 class="h3 text-800 text-primary mb-0">Survey Questions</h1>
                    </div> 
                    <div class="card-body">
                        {{-- <p class="font-italic text-info">Direction: Rate the performance of the faculty using a 4-point scale defined as follows:</p>
                        @if ($result['class']->curriculumsubject->subjectinfo->educational_level_id == \App\Models\Educationallevel::DEFAULT_EDUCATIONAL_LEVEL)
                            <div class="row">
                                <div class="col-md-3 text-black"><u>Always</u> - <i>All the time</i></div>
                                <div class="col-md-3 text-black"><u>Often</u> - <i>Most of the time</i></div>
                                <div class="col-md-3 text-black"><u>Sometimes</u> - <i>Once in a while</i></div>
                                <div class="col-md-3 text-black"><u>Never</u> - <i>Not at all</i></div>
                            </div>
                            @php
                                $choice1 = 'Always';
                                $choice2 = 'Often';
                                $choice3 = 'Sometimes';
                                $choice4 = 'Never';
                            @endphp
                        @else
                            <div class="row">
                                <div class="col-md-3 text-black"><u>Always</u></div>
                                <div class="col-md-3 text-black"><u>Sometimes</u></div>
                                <div class="col-md-3 text-black"><u>Rarely</div>
                                <div class="col-md-3 text-black"><u>No opportunity to observe</u></div>
                            </div>
                            @php
                                $choice1 = 'Always';
                                $choice2 = 'Sometimes';
                                $choice3 = 'Rarely';
                                $choice4 = 'No opportunity to observe';
                            @endphp
                        @endif
                        <h6 class="my-3 font-weight-bold text-black"><i>Select radio button which corresponds to the rating of your instructor.</i></h6> --}}
                        @if ($result['questions'] !== null && count($result['questions']) > 0)
                            @foreach ($result['questions'] as $question)
                                <h4 class="text-primary">{{ $question['category'] }}</h4>
                                @foreach ($question['subcategory'] as $subcategory)
                                    <h5 class="text-success font-italic">{{ $subcategory['subcategory'] }}</h5>
                                    @foreach ($subcategory['group'] as $group)
                                        <h6 class="text-black font-weight-bolder pl-5"><u>{{ $group['group'] }}</u></h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered table-striped">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Question</th>
                                                    <th>Rate</th>
                                                </tr>
                                                @foreach ($group['questions'] as $question)
                                                    <tr>
                                                        <td class="w30">{{ $loop->iteration }}</td>
                                                        <td>{{ $question['question'] }}</td>
                                                        <td class="mid w100">
                                                           
                                                        </td>
                                                    </tr>
                                                @endforeach
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
                                                
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @else
                            <h3 class="text-danger mid m-3">No survey questions to be displayed!</h3>
                        @endif
                    </div>
                </div>
                
    </div>
    <!-- /.container-fluid -->
@endsection