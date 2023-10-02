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
                    <div class="col-md-5">
                        <div class="font-weight-bold text-black">
                            {{ $evaluate_class['class_info']->class->curriculumsubject->subjectinfo->subject_name }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Code</label>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-5">
                        <div class="font-weight-bold text-black">
                            {{ $evaluate_class['class_info']->class->schedule->schedule }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Subject Units</label>
                    </div>
                    <div class="col-md-3">
                        <div class="font-weight-bold text-black">
                            {{ $evaluate_class['class_info']->class->units }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Faculty Name</label>
                    </div>
                    <div class="col-md-5">
                        <div class="font-weight-bold text-black">
                            @if ($evaluate_class['class_info']->class->instructor_id)
                            {{ 
                                $evaluate_class['class_info']->class->instructor->last_name.', '.
                                $evaluate_class['class_info']->class->instructor->first_name.' '.
                                $evaluate_class['class_info']->class->instructor->middle_name
                            }}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="question"  class="m-0 font-weight-bold text-primary">Section</label>
                    </div>
                    <div class="col-md-3">
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
            <form method="POST" id="form_evaluateclass" role="form">
                @csrf
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
                        <h6 class="my-3 font-weight-bold text-black"><i>Select radio button which corresponds to the rating of your instructor.</i></h6>
                        @if ($evaluate_class['questions'] !== null && count($evaluate_class['questions']) > 0)
                            @foreach ($evaluate_class['questions'] as $question)
                                <h4 class="text-primary mt-2">&bull; {{ $question['category'] }}</h4>
                                @foreach ($question['subcategory'] as $subcategory)
                                    <h5 class="text-success font-italic">{{ $subcategory['subcategory'] }}</h5>
                                    @foreach ($subcategory['group'] as $group)
                                        <h6 class="text-primary font-weight-bolder pl-3 mt-3"><u>{{ $group['group'] }}</u></h6>
                                        @foreach ($group['questions'] as $question)
                                                <input type="hidden" name="question_ids[]" value="{{ $question['id'] }}">
                                                <div class="row">
                                                    <div class="col-md-12 text-black font-weight-bold pl-5">
                                                        {{ $loop->iteration }}. {{ $question['question'] }}
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-3 mid">
                                                        <label for="choice1_{{ $question['id'] }}" class="m-0 font-weight-bold text-primary"><input type="radio" class="radio_choices" name="choice[{{ $question['id'] }}]" data-id="{{ $question['id'] }}" value="1" id="choice1_{{ $question['id'] }}"> {{ $choice1 }} </label>
                                                    </div>
                                                    <div class="col-md-3 mid">
                                                        <label for="choice2_{{ $question['id'] }}" class="m-0 font-weight-bold text-primary"><input type="radio" class="radio_choices" name="choice[{{ $question['id'] }}]" data-id="{{ $question['id'] }}" value="2" id="choice2_{{ $question['id'] }}"> {{ $choice2 }} </label>
                                                    </div>
                                                    <div class="col-md-3 mid">
                                                        <label for="choice3_{{ $question['id'] }}" class="m-0 font-weight-bold text-primary"><input type="radio" class="radio_choices" name="choice[{{ $question['id'] }}]" data-id="{{ $question['id'] }}" value="3" id="choice3_{{ $question['id'] }}"> {{ $choice3 }} </label>
                                                    </div>
                                                    <div class="col-md-3 mid">
                                                        <label for="choice4_{{ $question['id'] }}" class="m-0 font-weight-bold text-primary"><input type="radio" class="radio_choices" name="choice[{{ $question['id'] }}]" data-id="{{ $question['id'] }}" value="4" id="choice4_{{ $question['id'] }}"> {{ $choice4 }} </label>
                                                    </div>
                                                    <input type="hidden" name="choice[{{ $question['id'] }}]" value="unchecked" checked >
                                                    <div class="errors pl-5" id="error_choice_{{ $question['id'] }}"></div>
                                                </div>
                                            
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                            <h4 class="text-primary mt-2">&bull; As a whole, how do you rate your instructor's performance?</h4>
                            <div class="row mb-2">
                                <div class="col-md-3 mid">
                                    <label for="overallrate1" class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="overallrate" value="1" id="overallrate1"> Outstanding </label>
                                </div>
                                <div class="col-md-3 mid">
                                    <label for="overallrate2" class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="overallrate" value="2" id="overallrate2"> Very Good </label>
                                </div>
                                <div class="col-md-3 mid">
                                    <label for="overallrate3" class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="overallrate" value="3" id="overallrate3"> Good </label>
                                </div>
                                <div class="col-md-3 mid">
                                    <label for="overallrate4" class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="overallrate" value="4" id="overallrate4"> Fair </label>
                                </div>
                                <div class="errors pl-5" id="error_overallrate"></div>
                            </div>
                        @else
                            <h3 class="text-danger mid m-3">No survey questions to be displayed!</h3>
                        @endif
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h1 class="h3 text-800 text-primary mb-0">Comments</h1>
                            </div>
                            <div class="col-md-5 right"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="font-italic text-info">Note: Please choose words carefully and ensure that your language is respectful, professional, and appropriate for our institutional setting.</p>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="strongpoint" class="m-0 font-weight-bold text-primary">Strong Points</label>
                                <textarea name="strongpoint" id="strongpoint" class="form-control" rows="3"></textarea>
                                <div class="errors" id="error_strongpoint"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="weakpoint" class="m-0 font-weight-bold text-primary">Weak Points</label>
                                <textarea name="weakpoint" id="weakpoint" class="form-control" rows="3"></textarea>
                                <div class="errors" id="error_weakpoint"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="suggestion" class="m-0 font-weight-bold text-primary">Suggestions for Improvement</label>
                                <textarea name="suggestion" id="suggestion" class="form-control" rows="3"></textarea>
                                <div class="errors" id="error_suggestion"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="studentservices" class="m-0 font-weight-bold text-primary">Other Student Services</label>
                                <textarea name="studentservices" id="studentservices" class="form-control" rows="3"></textarea>
                                <div class="errors" id="error_studentservices"></div>
                            </div>
                        </div>
                        <input type="hidden" name="faculty_evaluation_id" value="{{ $evaluate_class['class_info']->id }}" >
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Submit Evaluation</button>
                    </div>
                </div>
            </form>
        @endif
    </div>
    <!-- /.container-fluid -->
@endsection