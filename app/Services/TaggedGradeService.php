<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\TaggedGrades;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class TaggedGradeService
{
    public function getAllTaggedGrades($student_id)
    {
        $query = TaggedGrades::query();
        $query->select(
            'tagged_grades.*', 
            'curriculum_subjects.subject_id'
        );
        $query->leftJoin('curriculum_subjects', 'curriculum_subjects.id', 'tagged_grades.curriculum_subject_id');
        $query->where('tagged_grades.student_id', $student_id);

        return $query->get();
    }    
}
