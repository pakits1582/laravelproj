<?php

namespace App\Services\Grade;

use App\Models\InternalGrade;
use App\Libs\Helpers;

class InternalGradeService
{
  
    public function checkIfPassedInternalGrade($student_id, $subject_id, $quota_grade = null, $internal_grade_id = null)
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
        $query->where('internal_grades.final', 1);
        $query->where(function($query){
            $query->where('remarks.remark', '=', 'PASSED')->orwhere('cgr.remark', '=', 'PASSED');
        });

        $query->where('curriculum_subjects.subject_id', $subject_id);
        $query->where('grades.student_id', $student_id);

        $isgrade_passed = $query->first();

        $ispassed = 0;

        if($isgrade_passed)
        {
            $ispassed = ($quota_grade !== null) ? $this->checkIfPassedQuotaGrade(
                $quota_grade, 
                $isgrade_passed->grade, 
                $isgrade_passed->completion_grade
            ) : 1;
        }

        return $ispassed;
    }

    public function checkIfPassedQuotaGrade($quotagrade, $grade, $completion_grade = null)
    {
        $passed = 0;
        if($quotagrade)
        {
            $passed = ($grade >= $quotagrade) ? 1 : (($completion_grade && $completion_grade >= $quotagrade) ? 1 : 0);
        }

        return $passed;
    }

    public function getAllStudentInternalGrades($student_id)
    {
        $query = InternalGrade::with(['classinfo', 'gradeinfo', 'gradesystem', 'gradesystem.remark'])
                    ->join('grades', 'internal_grades.grade_id', 'grades.id')->where('grades.student_id', $student_id);

        return $query->get();
    }
}

