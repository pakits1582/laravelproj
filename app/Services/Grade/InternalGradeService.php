<?php

namespace App\Services\Grade;

use App\Models\InternalGrade;
use App\Libs\Helpers;

class InternalGradeService
{
  
    public function checkIfPassedInternalGrade($student_id, $subject_id, $internal_grade_id = '')
    {
        $query = InternalGrade::query();
        $query->select(
            'internal_grades.id', 
            'grading_systems.value AS grade', 
            'cggs.value AS completion_grade');
        $query->leftJoin('grades', 'internal_grades.grade_id', 'grades.id');
        $query->leftJoin('classes', 'internal_grades.class_id', 'classes.id');
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'classes.curriculum_subject_id');
        $query->leftJoin('grading_systems', 'internal_grades.grading_system_id', 'grading_systems.id');
        $query->leftJoin('remarks', 'grading_systems.remark_id', 'remarks.id');
        $query->leftJoin('grading_systems AS cggs', 'internal_grades.completion_grade', 'cggs.id');
        $query->leftJoin('remarks AS cgr', 'cggs.remark_id', 'cgr.id');
        $query->where('curriculum_subjects.subject_id', $subject_id);
        $query->where('internal_grades.final', 1);
        $query->where('grades.student_id', $student_id);

        $query->where(function($query){
            $query->where('remarks.remark', '=', 'PASSED')->orwhere('cgr.remark', '=', 'PASSED');
        });

        return $query->first();

    }
}

