<?php

namespace App\Services;

use App\Models\User;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\FacultyEvaluation;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $query->where('classes.merge', '=', NULL);

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
            	$totalvalidated = 0;

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

            	
                $respondentsinclass  = ($v->faculty_evaluations_count == "") ? 0 : $v->faculty_evaluations_count;
            	$validatedinclass = ($v->validatedinclass == "") ? 0 : $v->validatedinclass;

                if($v->ismother > 0){
					$classes_array[$key]['slots'] = $v->slots;
					$class_id = $v->id;

					$children = array_filter($classes->toArray(), function($record) use($class_id){
						return $record['mothercodeid'] == $class_id;
					});

					$sum_respondentsinclass  = 0;
					$sum_validatedinclass = 0;

					if($children)
					{
						foreach ($children as $child) 
						{
							$sum_respondentsinclass  += $child['faculty_evaluations_count'];
            				$sum_validatedinclass += $child['validatedinclass'];
						}
					}

					$totalrespondents = $respondentsinclass + $sum_respondentsinclass;
					$totalvalidated   = $validatedinclass + $sum_validatedinclass;

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

						if($mother)
						{
							foreach ($mother as $m) 
							{
								$sum_mother_respondentsinclass  += $m['faculty_evaluations_count'];
	            				$sum_mother_validatedinclass += $m['validatedinclass'];
							}
						}

						$children = array_filter($classes->toArray(), function($record) use($motherclass){
							return $record['mothercodeid'] == $motherclass;
						});

						$sum_respondentsinclass = 0;
						$sum_validatedinclass = 0;

						if($children)
						{
							foreach ($children as $child) 
							{
								$sum_respondentsinclass  += $child['faculty_evaluations_count'];
	            				$sum_validatedinclass += $child['validatedinclass'];
							}
						}
                        
                        $classes_array[$key]['slots'] = $v->mothercodeslots;
						$totalrespondents  += $sum_respondentsinclass + $sum_mother_respondentsinclass;
						$totalvalidated += $sum_validatedinclass + $sum_mother_validatedinclass;

					}else{
						$classes_array[$key]['slots'] = $v->slots ?? 0;
						$totalrespondents  = $respondentsinclass;
						$totalvalidated = $validatedinclass;
					}	
				}

                $classes_array[$key]['totalrespondents']  = $totalrespondents;
                $classes_array[$key]['totalvalidated'] = $totalvalidated;
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
        $class->load('facultyevaluations.enrollment');


    }

    public function studentClassesForEvaluation($student)
    {
        DB::beginTransaction();

        $enrollment = Enrollment::with([
            'enrolled_classes.class' => function ($query) {
                $query->where('evaluation', 1);
            },
        ])->whereHas('student', function ($query) use ($student) {
                $query->where('user_id', $student);
            })->first();
    
        if($enrollment !== null)
        {
            $facultyevaluations = [];

            foreach ($enrollment as $key => $v) 
            {
                # code...
            }
            $enrollment->facultyevaluations()->saveMany();
        }
    
    }
}
