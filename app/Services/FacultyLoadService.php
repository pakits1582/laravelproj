<?php

namespace App\Services;

use App\Models\Classes;
use App\Models\OtherAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class FacultyLoadService
{
    //
    public function facultyload($period_id, $educational_level_id = '', $college_id = '', $instructor_id = '')
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
            'classes_schedules.day',
            'classes_schedules.from_time',
            DB::raw("CONCAT(instructors.last_name, ', ', instructors.first_name, ' ', instructors.name_suffix, ' ', instructors.middle_name) AS full_name")
        );
        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id');
        $query->leftJoin('classes_schedules', 'classes.id', '=', 'classes_schedules.class_id');
        $query->where('classes.period_id', $period_id)
            ->where('classes.dissolved', '!=', 1)
            ->whereNull('classes.merge')
            ->whereNotNull('classes.slots');
        
        $query->when(isset($educational_level_id) && !empty($educational_level_id), function ($query) use ($educational_level_id) {
            $query->where('programs.educational_level_id', $educational_level_id);
        });

        $query->when(isset($college_id) && !empty($college_id), function ($query) use ($college_id) {
            $query->where('programs.college_id', $college_id);
        });

        $query->when(isset($instructor_id) && !empty($instructor_id), function ($query) use ($instructor_id) {
            $query->where('classes.instructor_id', $instructor_id);
        });

        $query->groupBy('classes.id')
            ->orderBy('instructors.last_name')
            ->orderByRaw("FIELD(classes_schedules.day, 'M', 'T', 'W', 'TH', 'F', 'S', 'SU')")
            ->orderBy('classes_schedules.from_time');

        $classes = $query->get();

        return $classes;
    }

    public function saveOtherAssignment($request)
    {
        $insert = OtherAssignment::firstOrCreate([
            'period_id' => $request->term, 
            'instructor_id' => $request->type, 
            'assignment' => $request->assignment, 
            'units' => $request->units
            ],
             $request->validated()+['user_id' => Auth::id()]
        );

        if ($insert->wasRecentlyCreated) {
            return [
                'success' => true,
                'message' => 'Other assignment successfully added!',
                'alert' => 'alert-success',
            ];
        }

        return [
            'success' => false, 
            'alert' => 'alert-danger', 
            'message' => 'Duplicate entry, other assignment already exists!'
        ];

    }

    public function deleteOtherAssignment($otherassignment)
    {
        $otherassignment->delete();

        return [
            'success' => true,
            'message' => 'Other assignment sucessfully deleted!',
            'alert' => 'alert-success'
        ];
    }
    
}
