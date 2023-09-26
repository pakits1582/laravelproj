@extends('layout')
@section('title') {{ 'Evaluate Class' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Evaluate Class</h1>
        <p class="mb-4">Evaluate faculty performance.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h1 class="h3 text-800 text-primary mb-0">Class Evaluation for <span id="period_name">{{ session('periodname') }}</span></h1>
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
                            {{ $evaluate_class['class_info']->class->curriculumsubject->subjectinfo->subject_name }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Code</label>
                    </div>
                    <div class="col-md-2">
                        <div class="font-weight-bold text-black">
                            ({{ $evaluate_class['class_info']->class->code }})
                            {{ $evaluate_class['class_info']->class->curriculumsubject->subjectinfo->subject_code }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Schedule</label>
                    </div>
                    <div class="col-md-6">
                        <div class="font-weight-bold text-black">
                            {{ $evaluate_class['class_info']->class->schedule->schedule }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Units</label>
                    </div>
                    <div class="col-md-2">
                        <div class="font-weight-bold text-black">
                            {{ $evaluate_class['class_info']->class->units }}
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
                            $evaluate_class['class_info']->class->instructor->last_name.', '.
                            $evaluate_class['class_info']->class->instructor->first_name.' '.
                            $evaluate_class['class_info']->class->instructor->middle_name
                        }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Section</label>
                    </div>
                    <div class="col-md-2">
                        <div class="font-weight-bold text-black">
                            {{ $evaluate_class['class_info']->class->sectioninfo->section_code }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!$evaluate_class['is_open'])
            <div class="card shadow mb-4">
                <div class="card-header py-2 mid">
                    <h1 class="h3 text-800 text-danger m-0">ACCESS DENIED</h1>
                </div> 
                <div class="card-body">
                    <h1 class="h3 text-800 m-0 mid text-danger">Evaluation of faculty is closed!</h1>
                </div>
            </div>
        @else
            <div class="card shadow mb-4">
                <div class="card-header py-2">
                    <h1 class="h3 text-800 text-primary mb-0">Survey Questions</h1>
                </div> 
                <div class="card-body">
                    <p class="font-italic text-info">Direction: Rate the performance of the faculty using a 4-point scale defined as follows:</p>
                    @if ($evaluate_class['class_info']->class->curriculumsubject->subjectinfo->educational_level_id == \App\Models\Educationallevel::DEFAULT_EDUCATIONAL_LEVEL)
                        <div class="row">
                            <div class="col-md-3 text-black"><u>Always</u> - <i>All the time</i></div>
                            <div class="col-md-3 text-black"><u>Often</u> - <i>Most of the time</i></div>
                            <div class="col-md-3 text-black"><u>Sometimes</u> - <i>Once in a while</i></div>
                            <div class="col-md-3 text-black"><u>Never</u> - <i>Not at all</i></div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-3 text-black"><u>Always</u></div>
                            <div class="col-md-3 text-black"><u>Sometimes</u></div>
                            <div class="col-md-3 text-black"><u>Rarely</div>
                            <div class="col-md-3 text-black"><u>No oppurtunity to observe</u></div>
                        </div>
                    @endif
                    <h6 class="my-3 font-weight-bold text-black"><i>Select radio button which corresponds to the rating of your instructor.</i></h6>
                    @if ($evaluate_class['questions'] !== null && count($evaluate_class['questions']) > 0)
                        @foreach ($evaluate_class['questions'] as $question)
                            <h4 class="text-primary mt-2">{{ $question['category'] }}</h4>
                            @foreach ($question['subcategory'] as $subcategory)
                                <h5 class="text-success font-italic">{{ $subcategory['subcategory'] }}</h5>
                                @foreach ($subcategory['group'] as $group)
                                    <h6 class="text-primary font-weight-bolder pl-3 mt-3"><u>{{ $group['group'] }}</u></h6>
                                    @foreach ($group['questions'] as $question)

                                            <div class="row">
                                                <div class="col-md-12 text-black font-weight-bold pl-5">
                                                    {{ $loop->iteration }}. {{ $question['question'] }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 text-black mid"><u>Always</u> - <i>All the time</i></div>
                                                <div class="col-md-3 text-black mid"><u>Often</u> - <i>Most of the time</i></div>
                                                <div class="col-md-3 text-black mid"><u>Sometimes</u> - <i>Once in a while</i></div>
                                                <div class="col-md-3 text-black mid"><u>Never</u> - <i>Not at all</i></div>
                                            </div>
                                        
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    @else
                        <h3 class="text-danger mid m-3">No survey questions to be displayed!</h3>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <!-- /.container-fluid -->
@endsection