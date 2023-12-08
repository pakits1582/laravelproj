<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Classes;
use App\Models\WeakPoint;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\OverallRate;
use App\Models\StrongPoint;
use App\Models\EnrolledClass;
use App\Models\FacultyEvaluation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ConfigurationSchedule;
use App\Models\Suggestion;
use Illuminate\Support\Facades\Route;

class FacultyEvaluationService
{

    public function classesForEvaluation($user, $period_id, $instructor_id = null, $limit = 100,  $all = false, $evaluation = null, $evaluation_by = null)
    {
        $query = Classes::query();
        $query->select(
            'classes.*',
            DB::raw("COALESCE(SUM(CASE WHEN enrollments.acctok = '1' AND enrollments.assessed = '1' THEN enrollments.assessed ELSE 0 END), 0) AS assessedinclass"),
            DB::raw("COALESCE(SUM(CASE WHEN enrollments.validated = '1' THEN 1 ELSE 0 END), 0) AS validatedinclass"),
            DB::raw("COALESCE(SUM(enrolled_classes.class_id = classes.id), 0) AS enrolledinclass"),
            'subjects.code as subject_code',
            'subjects.name as subject_name',
            'instructors.last_name',
            'instructors.first_name',
            'instructors.middle_name',
            'instructors.name_suffix',
            'schedules.schedule',
            'sections.code as section_code',
            DB::raw("CONCAT(instructors.last_name, ', ', instructors.first_name, ' ', instructors.name_suffix, ' ', instructors.middle_name) AS full_name"),
            'mergeclass.code AS mothercode',
            'mergeclass.id AS mothercodeid',
            'mergeclass.slots AS mothercodeslots',
            DB::raw("COALESCE(faculty_evaluations_count_subquery.count, 0) AS faculty_evaluations_count")
        );
        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id'); 
        
        $query->leftJoin('enrolled_classes', 'classes.id', '=', 'enrolled_classes.class_id');
        $query->leftJoin('enrollments', 'enrolled_classes.enrollment_id', '=', 'enrollments.id');
        $query->leftJoin('classes AS mergeclass', 'classes.merge', '=', 'mergeclass.id');
        $query->leftJoin(DB::raw('(SELECT class_id, COUNT(*) AS count FROM faculty_evaluations WHERE status = '.FacultyEvaluation::FACULTY_EVAL_FINISHED.' GROUP BY class_id) faculty_evaluations_count_subquery'), function ($join) {
            $join->on('classes.id', '=', 'faculty_evaluations_count_subquery.class_id');
        });

        $query->where('classes.period_id', $period_id ?? session('current_period'));
        $query->where('classes.dissolved', '!=', 1);
        //$query->where('classes.merge', '=', NULL);

        if ($user->utype === User::TYPE_INSTRUCTOR) 
        {
            $user->load('instructorinfo');
            $programIds = [];
            $department_id = null; 

            switch ($user->instructorinfo->designation) {
                case Instructor::TYPE_PROGRAM_HEAD:
                    $programs = (new ProgramService())->programHeadship($user);
                    $programIds = $programs->pluck('id')->toArray();
                    break;

                case Instructor::TYPE_DEAN:
                    $programs = (new ProgramService())->programDeanship($user);
                    $programIds = $programs->pluck('id')->toArray();
                    break;

                case Instructor::TYPE_DEPARTMENT_HEAD:
                    $department_id =  $user->instructorinfo->department_id;
                    break;
            }

            $accessible_programs = [];

            if ($user->accessibleprograms->count()) {
                $accessible_programs = $user->accessibleprograms->load('program');
                $programIds = array_merge($programIds, $accessible_programs->pluck('program.id')->toArray());
            }

            $query->when(isset($programIds), function ($query) use ($programIds) {
                $query->whereIn('programs.id', $programIds);
            });

            $query->when(isset($department_id), function ($query) use ($department_id) {
                $query->orWhere('subjects.department_id', $department_id);
            });

            $query->when(isset($evaluation_by) && !empty($evaluation_by), function ($query) use ($evaluation_by) {
                $query->where('classes.evaluated_by', $evaluation_by);
            });
        }

        $query->when(isset($instructor_id) && !empty($instructor_id), function ($query) use ($instructor_id) {
            $query->where('classes.instructor_id', $instructor_id);
        });

        $query->when(isset($evaluation) && !empty($evaluation), function ($query) use ($evaluation) {
            $query->where('classes.evaluation', $evaluation);
        });
       
        $query->groupBy('classes.id');
        $query->orderBy('subjects.code')
            ->orderBy('classes.id');

        if($all)
        {
            return $query->get();
        }

        return $query->get($limit);
    }

    public function getClassesSlots($classes)
    {
        $classes_array = [];

        if($classes)
        {
            foreach ($classes as $key => $v) 
            {
                $totalrespondents  = 0;
                $totalassessed  = 0;
            	$totalvalidated = 0;
                $totalenrolled  = 0;
            	$remainingslot  = 0;

            	$classes_array[$key]['class_id']      = $v->id;
            	$classes_array[$key]['class_code']    = $v->code;
            	$classes_array[$key]['section_code']  = $v->section_code;
            	$classes_array[$key]['subject_code']  = $v->subject_code;
            	$classes_array[$key]['subject_name']  = $v->subject_name;
            	$classes_array[$key]['units']         = $v->units;
            	$classes_array[$key]['schedule']      = $v->schedule;
            	$classes_array[$key]['tutorial']      = $v->tutorial;
            	$classes_array[$key]['dissolved']     = $v->dissolved;
            	$classes_array[$key]['mothercode']    = $v->mothercode;
            	$classes_array[$key]['instructor_id'] = $v->instructor_id;
            	$classes_array[$key]['last_name']     = $v->last_name;
            	$classes_array[$key]['first_name']    = $v->first_name;
                $classes_array[$key]['evaluation']    = $v->evaluation;
                $classes_array[$key]['ismother']      = $v->ismother;
                $classes_array[$key]['merge']         = $v->merge;
            	
                $respondentsinclass  = ($v->faculty_evaluations_count == "") ? 0 : $v->faculty_evaluations_count;
                $assessedinclass  = ($v->assessedinclass == "") ? 0 : $v->assessedinclass;
            	$validatedinclass = ($v->validatedinclass == "") ? 0 : $v->validatedinclass;
                $enrolledinclass  = ($v->enrolledinclass == "") ? 0 : $v->enrolledinclass;

                if($v->ismother > 0){
					$classes_array[$key]['slots'] = $v->slots;
					$class_id = $v->id;

					$children = array_filter($classes->toArray(), function($record) use($class_id){
						return $record['mothercodeid'] == $class_id;
					});

					$sum_respondentsinclass  = 0;
					$sum_assessedinclass  = 0;
					$sum_validatedinclass = 0;
                    $sum_enrolledinclass  = 0;

					if($children)
					{
						foreach ($children as $child) 
						{
							$sum_respondentsinclass  += $child['faculty_evaluations_count'];
                            $sum_assessedinclass  += $child['assessedinclass'];
            				$sum_validatedinclass += $child['validatedinclass'];
                            $sum_enrolledinclass  += $child['enrolledinclass'];						
                        }
					}

					$totalrespondents = $respondentsinclass + $sum_respondentsinclass;
                    $totalassessed  = $assessedinclass + $sum_assessedinclass;
					$totalvalidated = $validatedinclass + $sum_validatedinclass;
                    $totalenrolled  = $enrolledinclass + $sum_enrolledinclass;
					$remainingslot  = $v->slots - $totalenrolled;

				}else{//not a mothercode
					/*
						IF SUBJECT MERGE IN != 0
						SUBJECT IS MERGED GET ENROLLED IN MOTHERCODE AND ALL MERGED CHILD
					*/
					if($v->merge != 0)
					{
						$motherclass = $v->merge;

						$mother = array_filter($classes->toArray(), function($record) use($motherclass){
							return $record['id'] == $motherclass;
						});

						$sum_mother_respondentsinclass  = 0;
						$sum_mother_validatedinclass = 0;
                        $sum_mother_assessedinclass  = 0;
                        $sum_mother_enrolledinclass  = 0;
						if($mother)
						{
							foreach ($mother as $m) 
							{
								$sum_mother_respondentsinclass  += $m['faculty_evaluations_count'];
	            				$sum_mother_assessedinclass  += $m['assessedinclass'];
	            				$sum_mother_validatedinclass += $m['validatedinclass'];
                                $sum_mother_enrolledinclass  += $m['enrolledinclass'];
							}
						}

						$children = array_filter($classes->toArray(), function($record) use($motherclass){
							return $record['mothercodeid'] == $motherclass;
						});

						$sum_respondentsinclass = 0;
                        $sum_assessedinclass  = 0;
						$sum_validatedinclass = 0;
                        $sum_enrolledinclass  = 0;

						if($children)
						{
							foreach ($children as $child) 
							{
								$sum_respondentsinclass  += $child['faculty_evaluations_count'];
                                $sum_assessedinclass  += $child['assessedinclass'];
	            				$sum_validatedinclass += $child['validatedinclass'];
                                $sum_enrolledinclass  += $child['enrolledinclass'];							}
						}
                        
						$totalrespondents  += $sum_respondentsinclass + $sum_mother_respondentsinclass;
                        $classes_array[$key]['slots'] = $v->mothercodeslots;
						$totalassessed  += $sum_assessedinclass + $sum_mother_assessedinclass;
						$totalvalidated += $sum_validatedinclass + $sum_mother_validatedinclass;
                        $totalenrolled  += $sum_enrolledinclass + $sum_mother_enrolledinclass;
                        $remainingslot  = $v->mothercodeslots - $totalenrolled;

					}else{
						$totalrespondents  = $respondentsinclass;
						$totalvalidated = $validatedinclass;
                        $classes_array[$key]['slots'] = $v->slots ?? 0;
						$totalassessed  = $assessedinclass;
						$totalvalidated = $validatedinclass;
                        $totalenrolled  = $enrolledinclass;
						$remainingslot  = $v->slots - $totalenrolled;
					}	
				}

                $classes_array[$key]['totalrespondents']  = $totalrespondents;
                $classes_array[$key]['totalassessed']  = $totalassessed;
                $classes_array[$key]['totalvalidated'] = $totalvalidated;
                $classes_array[$key]['totalenrolled']  = $totalenrolled;
                $classes_array[$key]['remainingslot']  = $remainingslot;            
            }
        }

        return $classes_array;
    }

    public function getUniqueInstructors($classes)
    {
        $instructors = $classes->unique('instructor_id')->map(function ($class) {
            return [
                'id' => $class->instructor_id,
                'full_name' => $class->full_name,
            ];
        });

        return $instructors->sortBy('full_name');;
    }

    public function evaluationAction($class, $action)
    {
        DB::beginTransaction();

        $action = ($action == 'open') ? 1 : 0;
        $evaluated_by = ($action == 'close') ? NULL : Auth::id();
        $class->update(['evaluation' => $action, 'evaluated_by' => $evaluated_by]);
        
        if($class->ismother == 1)
        {
            $class->merged()->update(['evaluation' => $action, 'evaluated_by' => $evaluated_by]);
        }
        
        DB::commit();

        return [
            'success' => true,
            'message' => 'Action successfully executed!',
            'alert' => 'alert-success'
        ];
    }

    public function resetEvaluation($class)
    {
        $facultyevaluation = FacultyEvaluation::where('class_id', $class->id)->delete();

        return [
            'success' => true,
            'message' => 'Action successfully executed!',
            'alert' => 'alert-success'
        ];
    }

    public function returnRespondents($class)
    {
        $class->load([
            'sectioninfo:id,code',
            'instructor',
            'schedule:id,schedule',
            'merged:id,merge',
            'mergetomotherclass:id,code',
            'curriculumsubject.subjectinfo:id,code,name',
        ]);

        $class_ids = [];

        if ($class->ismother == 1) 
        {
            $class_ids = $class->merged->pluck('id')->toArray();
            $class_ids[] = $class->id;

        }else if($class->merge !== null){

            $class_ids_of_all_merged = Classes::select('id','code')->where("merge", $class->mergetomotherclass->id)->get();
            $class_ids = $class_ids_of_all_merged->pluck('id')->toArray();
            $class_ids[] = $class->mergetomotherclass->id;

        }else{
            $class_ids[] = $class->id;
        }

        $enrolled_students = EnrolledClass::select(
            'classes.code AS class_code',
            'users.idno AS idno',
            'sections.code AS section_code',
            'enrollments.year_level',
            'programs.code AS program_code',
            DB::raw('(SELECT f.status FROM faculty_evaluations AS f WHERE enrolled_classes.class_id = f.class_id AND f.enrollment_id = enrolled_classes.enrollment_id) AS status'),
            DB::raw('CONCAT_WS(" ", students.last_name, students.first_name, students.name_suffix, students.middle_name) AS full_name')
        )
        ->join('enrollments', 'enrolled_classes.enrollment_id', '=', 'enrollments.id')
        ->join('classes', 'enrolled_classes.class_id', '=', 'classes.id')
        ->join('students', 'enrollments.student_id', '=', 'students.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->join('sections', 'enrollments.section_id', '=', 'sections.id')
        ->join('programs', 'students.program_id', '=', 'programs.id')
        ->whereIn('enrolled_classes.class_id', $class_ids)
        ->where('enrollments.validated', 1)
        ->orderBy('students.last_name', 'asc')
        ->get();

        return ['class' => $class, 'respondents' => $enrolled_students];
    }

    public function studentEnrollment($user_id)
    {
        $enrollment = Enrollment::
        // with([
        //     'facultyevaluations',
        //     'enrolled_classes.enrolledclass' => [
        //             'sectioninfo',
        //             'instructor', 
        //             'schedule',
        //             'curriculumsubject' => ['subjectinfo']
        //         ]
        // ])
        whereHas('student', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->where('period_id', session('current_period'))
        ->first();

        return $enrollment;
    }

    public function studentClassesForEvaluation($enrollment)
    {
        if($enrollment && $enrollment->facultyevaluations)
        {
            DB::beginTransaction();

            $faculty_evaluations = $enrollment->facultyevaluations;

            $classes_to_evaluate = EnrolledClass::where('enrollment_id', $enrollment->id)
            ->whereHas('class', function ($query) {
                $query->where('evaluation', FacultyEvaluation::CLASS_FOR_EVALUATION_TRUE);
            })->get();

            $classes_not_in_faculty_evaluations = [];

            foreach ($classes_to_evaluate as $class_to_evaluate) 
            {
                $classId = $class_to_evaluate->class_id;
                
                // Check if the class ID is not in faculty evaluations
                $classNotInFacultyEvaluations = !$faculty_evaluations
                    ->pluck('class_id')
                    ->contains($classId);

                if ($classNotInFacultyEvaluations) {
                    // If the class is not in faculty evaluations, add it to the result array
                    $classes_not_in_faculty_evaluations[] = $class_to_evaluate;
                }
            } 
            
            $insert_facultyevaluations = [];
            if($classes_not_in_faculty_evaluations)
            {
                foreach ($classes_not_in_faculty_evaluations as $key => $class_for_evaluation) 
                {
                    $insert_facultyevaluations[] = [
                        'enrollment_id' => $enrollment->id,
                        'class_id' => $class_for_evaluation->class_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }

                $enrollment->facultyevaluations()->insert($insert_facultyevaluations);
            }

            DB::commit();

            $enrollment->load([
                'facultyevaluations.class.sectioninfo',
                'facultyevaluations.class.instructor',
                'facultyevaluations.class.schedule',
                'facultyevaluations.class.curriculumsubject.subjectinfo'
            ]);

            return $enrollment->facultyevaluations;
        }
        
    }

    public function evaluateClass($facultyevaluation){

        $facultyevaluation->load([
            'class.sectioninfo' => function ($query) {
                $query->select('id', 'code AS section_code');
            },
            'class.instructor' => function ($query) {
                $query->select('id', 'last_name', 'first_name', 'middle_name', 'name_suffix');
            },
            'class.schedule' => function ($query) {
                $query->select('id', 'schedule');
            },
            'class.curriculumsubject.subjectinfo' => function ($query) {
                $query->select('id', 'code AS subject_code', 'name AS subject_name', 'educational_level_id', 'college_id');
            },
        ]);

        $is_open = $this->checkEvaluationSchedule($facultyevaluation);
        $survey_questions = $is_open ? (new QuestionService)->surveyQuestions($facultyevaluation->class->curriculumsubject->subjectinfo->educational_level_id) : [];

        return [
            'is_open' => $is_open,
            'class_info' => $facultyevaluation,
            'questions' => $survey_questions,
        ];
    }

    public function checkEvaluationSchedule($facultyevaluation)
    {
        $open = false;

        if($facultyevaluation->class->evaluation_status == 1)
        {
            return true;
        }

        $config_schedules = (new ConfigurationService())->configurationSchedule(session('current_period'), ConfigurationSchedule::SCHEDULE_FACULTY_EVALUATION);

        if($config_schedules->count() > 0)
        {
            $today = Carbon::today();

            foreach ($config_schedules as $key => $config) 
            {
                if ($config->educational_level_id === 0 || $config->educational_level_id === $facultyevaluation->class->curriculumsubject->subjectinfo->educational_level_id) 
                {
                    $startDate = Carbon::parse($config->date_from);
                    $endDate = Carbon::parse($config->date_to);
            
                    if ($today->between($startDate, $endDate)) {
                        $open = true;
                        break;
                    }
                }
            }
        }

        return $open;
    }

    public function saveEvaluationAnswers($request)
    {
        DB::beginTransaction();

        $facultyevaluation = FacultyEvaluation::findOrFail($request->faculty_evaluation_id);

        $answerArray = [];
        foreach ($request->question_ids as $key => $question_id) 
        {
            $answerArray[] = [
                'faculty_evaluation_id' => $facultyevaluation->id,
                'question_id'   => $question_id,
                'answer' => $request->choice[$question_id],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        $facultyevaluation->survey_answers()->insert($answerArray);
        $facultyevaluation->overall_rates()->updateOrCreate(['faculty_evaluation_id' => $facultyevaluation->id], ['answer' => $request->overallrate]);
        
        $request->filled('strongpoint') && $facultyevaluation->strongpoints()->updateOrCreate(
            ['faculty_evaluation_id' => $facultyevaluation->id], ['comment' => $request->strongpoint]
        );

        $request->filled('weakpoint') && $facultyevaluation->weakpoints()->updateOrCreate(
            ['faculty_evaluation_id' => $facultyevaluation->id], ['comment' => $request->weakpoint]
        );

        $request->filled('suggestion') && $facultyevaluation->suggestions()->updateOrCreate(
            ['faculty_evaluation_id' => $facultyevaluation->id], ['comment' => $request->suggestion]
        );

        $request->filled('studentservices') && $facultyevaluation->student_services()->updateOrCreate(
            ['faculty_evaluation_id' => $facultyevaluation->id], ['comment' => $request->studentservices]
        );

        $facultyevaluation->update(['date_taken' => Carbon::now()->toDateString(), 'status' => FacultyEvaluation::FACULTY_EVAL_FINISHED]);
        
        DB::commit();

        return [
            'success' => true,
            'message' => 'Evaluation answers successfully submitted!',
            'alert' => 'alert-success'
        ];
    }

    public function returnEvaluationResult($class_ids)
    {
        $faculty_evaluation_result = FacultyEvaluation::select(
            'faculty_evaluations.id',
            'faculty_evaluations.class_id',
            'faculty_evaluations.enrollment_id',
            'faculty_evaluations.status',
            'faculty_evaluations.date_taken',
            'survey_answers.answer',
            'questions.id AS question_id',
            'questions.question',
            'questions.educational_level_id',
            'question_categories.name AS category',
            'question_subcategories.name AS subcategory',
            'question_groups.name AS group'
        )
        ->join('survey_answers', 'faculty_evaluations.id', '=', 'survey_answers.faculty_evaluation_id')
        ->join('questions', 'survey_answers.question_id', '=', 'questions.id')
        ->leftJoin('question_categories', 'questions.question_category_id', '=', 'question_categories.id')
        ->leftJoin('question_subcategories', 'questions.question_subcategory_id', '=', 'question_subcategories.id')
        ->leftJoin('question_groups', 'questions.question_group_id', '=', 'question_groups.id')
        ->whereIn('faculty_evaluations.class_id', $class_ids)
        ->where('faculty_evaluations.status', FacultyEvaluation::FACULTY_EVAL_FINISHED)
        ->get();

        return $faculty_evaluation_result;
    }

    public function evaluationResult($class)
    {
        $class->load([
            'sectioninfo:id,code',
            'instructor',
            'schedule:id,schedule',
            'merged:id,merge',
            'mergetomotherclass:id,code',
            'curriculumsubject.subjectinfo',
            'curriculumsubject.subjectinfo.educlevel',
        ]);

        $class_ids = [];

        if ($class->ismother == 1) 
        {
            $class_ids = $class->merged->pluck('id')->toArray();
            $class_ids[] = $class->id;
        }else{
            $class_ids[] = $class->id;
        }

        $faculty_evaluation_result = $this->returnEvaluationResult($class_ids);

        $groupedAnswersAndQuestions = $this->groupSurveyResult($faculty_evaluation_result->toArray());
        $overall_rate = OverallRate::whereHas('facultyevaluation', function ($query) use ($class) {
            $query->where('class_id', $class->id);
        })->pluck('answer')->toArray();
        
        return [
            'class' => $class,
            'overall_rate' => $overall_rate,
            'questions' => $groupedAnswersAndQuestions,
        ];

    }
    
    public function groupSurveyResult($questions)
    {
        $grouped = [];

        foreach ($questions as $question) {
            $category     = $question['category'];
            $subcategory  = $question['subcategory'];
            $group        = $question['group'];
            $questionText = $question['question'];
            $question_id  = $question['question_id'];

            if (!isset($grouped[$category])) {
                $grouped[$category] = [
                    'category' => $category,
                    'subcategory' => [],
                ];
            }

            if (!isset($grouped[$category]['subcategory'][$subcategory])) {
                $grouped[$category]['subcategory'][$subcategory] = [
                    'subcategory' => $subcategory,
                    'group' => [],
                ];
            }

            if (!isset($grouped[$category]['subcategory'][$subcategory]['group'][$group])) {
                $grouped[$category]['subcategory'][$subcategory]['group'][$group] = [
                    'group' => $group,
                    'questions' => [],
                ];
            }

            if (!isset($grouped[$category]['subcategory'][$subcategory]['group'][$group]['questions'][$question_id])) {
                $grouped[$category]['subcategory'][$subcategory]['group'][$group]['questions'][$question_id] = [
                    'id' => $question['id'],
                    'question' => $questionText,
                    'answers' => []
                ];
            }

            $grouped[$category]['subcategory'][$subcategory]['group'][$group]['questions'][$question_id]['answers'][] = [
                'answer' => $question['answer']
            ];
        }

        $grouped = array_values($grouped);
        foreach ($grouped as &$group) {
            $group['subcategory'] = array_values($group['subcategory']);
            foreach ($group['subcategory'] as &$subcategory) {
                $subcategory['group'] = array_values($subcategory['group']);
                foreach ($subcategory['group'] as &$group) {
                    $group['questions'] = array_values($group['questions']);
                }
            }
        }

        return $grouped;

    }

    public function commentSummary($class)
    {
        $class->load([
            'sectioninfo:id,code',
            'instructor',
            'schedule:id,schedule',
            'merged:id,merge',
            'mergetomotherclass:id,code',
            'curriculumsubject.subjectinfo',
            'curriculumsubject.subjectinfo.educlevel',
        ]);

        $class_ids = [];

        if ($class->ismother == 1) 
        {
            $class_ids = $class->merged->pluck('id')->toArray();
            $class_ids[] = $class->id;
        }else{
            $class_ids[] = $class->id;
        }

        $strong_points = StrongPoint::whereHas('facultyevaluation', function ($query) use ($class_ids) {
            $query->whereIn('class_id', $class_ids);
        })->get();

        $weak_points = WeakPoint::whereHas('facultyevaluation', function ($query) use ($class_ids) {
            $query->whereIn('class_id', $class_ids);
        })->get();

        $suggestions = Suggestion::whereHas('facultyevaluation', function ($query) use ($class_ids) {
            $query->whereIn('class_id', $class_ids);
        })->get();
        

        return [
            'class' => $class,
            'strong_points' => $strong_points,
            'weak_points' => $weak_points,
            'suggestions' => $suggestions,
        ];
    }
}
