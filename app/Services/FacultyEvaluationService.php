<?php

namespace App\Services;

use App\Models\User;
use App\Models\Classes;
use App\Models\Department;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class FacultyEvaluationService
{
    //

    public function classesForEvaluation($user, $request)
    {
//         $query = Classes::query();
//         $query->select(
//             'classes.*',
//             DB::raw("COALESCE(SUM(CASE WHEN enrollments.acctok = '1' AND enrollments.assessed = '1' THEN enrollments.assessed ELSE 0 END), 0) AS assessedinclass"),
//             DB::raw("COALESCE(SUM(CASE WHEN enrollments.validated = '1' THEN 1 ELSE 0 END), 0) AS validatedinclass"),
//             DB::raw("COALESCE(SUM(enrolled_classes.class_id = classes.id), 0) AS enrolledinclass"),
//             'subjects.code as subject_code',
//             'subjects.name as subject_name',
//             'instructors.last_name',
//             'instructors.first_name',
//             'instructors.middle_name',
//             'instructors.name_suffix',
//             'schedules.schedule',
//             'sections.code as section_code',
//             DB::raw("CONCAT(instructors.last_name, ', ', instructors.first_name, ' ', instructors.name_suffix, ' ', instructors.middle_name) AS full_name"),
//             'mergeclass.code AS mothercode',
//             'mergeclass.id AS mothercodeid',
//             'mergeclass.slots AS mothercodeslots',
//         );
//         $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
//         $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
//         $query->join('sections', 'classes.section_id', '=', 'sections.id');
//         $query->join('programs', 'sections.program_id', '=', 'programs.id');
//         $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
//         $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id'); 
        
//         $query->leftJoin('enrolled_classes', 'classes.id', '=', 'enrolled_classes.class_id');
//         $query->leftJoin('enrollments', 'enrolled_classes.enrollment_id', '=', 'enrollments.id');
//         $query->leftJoin('classes AS mergeclass', 'classes.merge', '=', 'mergeclass.id');

//         $query->where('classes.period_id', $request->period_id);
// // 
        if($user->utype === User::TYPE_INSTRUCTOR)
        {
            $user->load('instructorinfo');

            if($user->instructorinfo->designation === Instructor::TYPE_PROGRAM_HEAD)
            {
                $programs = (new ProgramService())->programHeadship($user);
            }

            if($user->instructorinfo->designation === Instructor::TYPE_DEAN)
            {
                $programs = (new ProgramService())->programDeanship($user);
            }

            if($user->accessibleprograms->count())
            {
                $programs = $user->accessibleprograms->load('program');
            }

            if($user->instructorinfo->designation === Instructor::TYPE_DEPARTMENT_HEAD)
            {
                return $user->instructorinfo->department_id;
            }
        }

    }
}
