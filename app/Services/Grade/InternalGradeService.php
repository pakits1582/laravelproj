<?php

namespace App\Services\Grade;

use App\Libs\Helpers;
use App\Models\GradingSystem;
use App\Models\InternalGrade;

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

    public function getAllStudentPassedInternalGrades($student_id)
    {
        // $query = InternalGrade::with(['classinfo', 'gradeinfo', 'gradesystem', 'gradesystem.remark'])
        //             ->join('grades', 'internal_grades.grade_id', 'grades.id')->where('grades.student_id', $student_id);

        $query = InternalGrade::query();
        $query->select(
            'internal_grades.*', 
            'grading_systems.value AS grade', 
            'remarks.remark',
            'cgr.remark AS completion_remark',
            'cggs.value AS completion_grade',
            'classes.curriculum_subject_id',
            'curriculum_subjects.subject_id',
            'subjects.code',
            'subjects.units',
            'subjects.name',
            'grades.period_id'
        );
        $query->Join('grades', 'internal_grades.grade_id', 'grades.id');
        $query->leftJoin('classes', 'internal_grades.class_id', 'classes.id');
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'classes.curriculum_subject_id');
        $query->leftJoin('subjects', 'subjects.id', 'curriculum_subjects.subject_id');
        $query->leftJoin('grading_systems', 'internal_grades.grading_system_id', 'grading_systems.id');
        $query->leftJoin('remarks', 'grading_systems.remark_id', 'remarks.id');
        $query->leftJoin('grading_systems AS cggs', 'internal_grades.completion_grade', 'cggs.id');
        $query->leftJoin('remarks AS cgr', 'cggs.remark_id', 'cgr.id');
        $query->where(function($query){
            $query->where('remarks.remark', '=', 'PASSED')->orwhere('cgr.remark', '=', 'PASSED');
        });
        $query->where('grades.student_id', $student_id);

        return $query->get();
    }

    public function getInternalGradeInfo($grade_id)
    {
        $query = InternalGrade::query();
        $query->select(
            'internal_grades.*', 
            'grading_systems.value AS grade', 
            'remarks.remark',
            'cgr.remark AS completion_remark',
            'cggs.value AS completion_grade',
            'curriculum_subjects.subject_id'
        );
        $query->leftJoin('grades', 'internal_grades.grade_id', 'grades.id');
        $query->leftJoin('classes', 'internal_grades.class_id', 'classes.id');
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'classes.curriculum_subject_id');
        $query->leftJoin('grading_systems', 'internal_grades.grading_system_id', 'grading_systems.id');
        $query->leftJoin('remarks', 'grading_systems.remark_id', 'remarks.id');
        $query->leftJoin('grading_systems AS cggs', 'internal_grades.completion_grade', 'cggs.id');
        $query->leftJoin('remarks AS cgr', 'cggs.remark_id', 'cgr.id');
        $query->where(function($query){
            $query->where('remarks.remark', '=', 'PASSED')->orwhere('cgr.remark', '=', 'PASSED');
        });
        $query->where('internal_grades.id', $grade_id);

        return $query->first();
    }

    public function getAllBlankInternalGrades($student_id)
    {
        $query = InternalGrade::query();
        $query->select(
            'internal_grades.*', 
            'grading_systems.value AS grade', 
            'remarks.remark',
            'cgr.remark AS completion_remark',
            'cggs.value AS completion_grade',
            'curriculum_subjects.subject_id'
        );
        $query->leftJoin('grades', 'internal_grades.grade_id', 'grades.id');
        $query->leftJoin('classes', 'internal_grades.class_id', 'classes.id');
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'classes.curriculum_subject_id');
        $query->leftJoin('grading_systems', 'internal_grades.grading_system_id', 'grading_systems.id');
        $query->leftJoin('remarks', 'grading_systems.remark_id', 'remarks.id');
        $query->leftJoin('grading_systems AS cggs', 'internal_grades.completion_grade', 'cggs.id');
        $query->leftJoin('remarks AS cgr', 'cggs.remark_id', 'cgr.id');

        $query->whereNull('internal_grades.grading_system_id');
    
        $query->where('grades.student_id', $student_id);

        return $query->get();
    }

    public function getInternalGrades($grade_id)
    {
        $query = InternalGrade::query();
        $query->select(
            'internal_grades.*', 
            'grading_systems.value AS grade', 
            'remarks.remark',
            'cgr.remark AS completion_remark',
            'cggs.value AS completion_grade',
            'classes.curriculum_subject_id',
            'classes.code AS classcode',
            'classes.units',
            'classes.instructor_id',
            'curriculum_subjects.subject_id',
            'subjects.code AS subjectcode',
            'subjects.gwa',
            'subjects.name AS subjectname',
            'sections.code AS sectioncode',
            'instructors.last_name',
            'instructors.first_name',
            'instructors.middle_name',
            'instructors.name_suffix'
        );
        $query->leftJoin('grades', 'internal_grades.grade_id', 'grades.id');
        $query->leftJoin('classes', 'internal_grades.class_id', 'classes.id');
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'classes.curriculum_subject_id');
        $query->leftJoin('subjects', 'subjects.id', 'curriculum_subjects.subject_id');
        $query->leftJoin('sections', 'sections.id', 'classes.section_id');
        $query->leftJoin('instructors', 'instructors.id', 'classes.instructor_id');
        $query->leftJoin('grading_systems', 'internal_grades.grading_system_id', 'grading_systems.id');
        $query->leftJoin('remarks', 'grading_systems.remark_id', 'remarks.id');
        $query->leftJoin('grading_systems AS cggs', 'internal_grades.completion_grade', 'cggs.id');
        $query->leftJoin('remarks AS cgr', 'cggs.remark_id', 'cgr.id');
        
        $query->where('internal_grades.grade_id', $grade_id);

        return $query->get();
    }

    public function saveInlineUpdateGrade($request)
    {
        $internal_grade = InternalGrade::with('classinfo.curriculumsubject.subjectinfo')->find($request->internal_grade_id);

        if(!$internal_grade)
        {
            return [
                'success' => false,
                'message' => 'Something went wrong! Internal Grade can not be found!',
                'alert' => 'alert-danger'
            ];
        }

        $subject_educational_level_id = $internal_grade->classinfo->curriculumsubject->subjectinfo->educational_level_id;

        if($request->grade != '')
        {
            $grading_system = GradingSystem::where('educational_level_id', $subject_educational_level_id)->where('value', $request->grade)->first();

            if(!$grading_system)
            {
                return [
                    'success' => false,
                    'message' => 'Something went wrong! Grade entered does not exists in the grading system!',
                    'alert' => 'alert-danger'
                ];
            }
        }
        
        $internal_grade->update([
            'grading_system_id' => ($request->grade == '') ? NULL : $grading_system->id,
            'final' => 1
        ]);

        return [
            'success' => true,
            'message' => 'Internal grade updated successfully!',
            'alert' => 'alert-success'
        ];
    }
}

