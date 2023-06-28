<?php

namespace App\Services;

use App\Models\Classes;

class ClassListService
{
   
    public function classlist($period_id, $criteria = '', $keyword = '', $limit = 0)
    {
        $query = Classes::query();
        $query->select(
            'classes.*',
            'subjects.code as subject_code',
            'subjects.name as subject_name',
            'instructors.last_name',
            'instructors.first_name',
            'instructors.middle_name',
            'instructors.name_suffix',
            'schedules.schedule',
            'sections.code as section_code',
        );

        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id'); 
        $query->where('classes.period_id', $period_id);

        $query->when(isset($criteria) && !empty($criteria) && !empty($keyword) , function ($query) use ($criteria, $keyword) 
        {
            switch ($criteria) 
            {
                case 'code':
                    $query->where('classes.code', $keyword);
                    break;
                case 'instructor':
                    $query->where('instructors.last_name', 'like', $keyword.'%')->orWhere('instructors.first_name', 'like', $keyword.'%');
                    break;
                case 'subject':
                    $query->where('subjects.code', 'like', $keyword.'%');
                    break;
            }
        });

        $query->orderBy('subjects.code')
            ->orderBy('classes.id');

        $query->when(isset($limit) && $limit != 0 , function ($query) use ($limit) 
        {
            $query->limit($limit);
        });
    
        $classes = $query->get();

        return $classes;
    }
}