<?php

namespace App\Services;

use App\Models\User;
use App\Models\Classes;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;

class FacultyEvaluationService
{

    public function classesForEvaluation($user, $request, $limit = 10,  $all = false)
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

        $query->where('classes.period_id', 1);

        if ($user->utype === User::TYPE_INSTRUCTOR) {
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

        }

       
        $query->groupBy('classes.id');
        $query->orderBy('subjects.code')
            ->orderBy('classes.id');

        $classes = $query->get();

        return $classes;
    }
}
