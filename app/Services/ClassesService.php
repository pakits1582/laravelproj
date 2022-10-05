<?php

namespace App\Services;

use App\Models\CurriculumSubjects;


class ClassesService
{
    //
    public function filterCurriculumSubjects($curriculum_id, $term = '', $year_level = '')
    {
        $query = CurriculumSubjects::with(['subjectinfo', 'prerequisites', 'corequisites', 'equivalents'])->where("curriculum_id", $curriculum_id);
        if($term !== '') {
            $query->where('term_id', $term);
        }

        if($year_level !== '') {
            $query->where('year_level', $year_level);
        }

        return $query->get();
    }

    public function classSubjects()
    {
        
    }

}
