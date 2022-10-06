<?php

namespace App\Services;

use App\Models\Classes;
use App\Models\CurriculumSubjects;


class ClassesService
{
    //
    public function filterCurriculumSubjects($curriculum_id, $term = '', $year_level = '', $section_id = '')
    {
        $query = CurriculumSubjects::with(['subjectinfo', 'prerequisites', 'corequisites', 'equivalents'])->where("curriculum_id", $curriculum_id);
        if($term !== '') {
            $query->where('term_id', $term);
        }

        if($year_level !== '') {
            $query->where('year_level', $year_level);
        }
        if($section_id !== '') {
            $query->whereNotIn('id',function($query) use ($section_id) {
                $query->select('curriculum_subject_id')->from('classes')
                        ->where('section_id', $section_id)
                        ->where('period_id', session('current_period'));
            });
        }

        return $query->get();
    }

    public function classSubjects($request)
    {
        $class_subjects = Classes::with(['curriculumsubject', 'instructor'])->where('section_id', $request->section)->where('period_id', session('current_period'))->get();
        
        return $class_subjects;
    }

    public function classSubject($class_id)
    {
        $class_subject = Classes::with(['sectioninfo', 'curriculumsubject', 'instructor', 'schedule'])->where('id', $class_id)->firstOrFail();
    }

}
