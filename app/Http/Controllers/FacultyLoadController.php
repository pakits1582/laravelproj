<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\PeriodService;

class FacultyLoadController extends Controller
{
    public function __construct()
    {
        Helpers::setLoad(['jquery_facultyload.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $faculty_loads = $this->facultyload(session('current_period'));
        $faculty = $faculty_loads->unique('instructor_id');

        return view('facultyload.index', compact('periods', 'faculty_loads', 'faculty'));
    }

    public function facultyload($period_id, $instructor_id = '')
    {
        // $query = Classes::with([
        //     'instructor' => function ($query) {
        //         $query->orderBy('last_name', 'ASC');
        //     },
        //     'schedule',
        //     'curriculumsubject.subjectinfo',
        //     'sectioninfo'
        // ])
        //     ->where('period_id', $period_id)
        //     ->where('dissolved', '!=', 1)
        //     ->whereNull('merge')
        //     ->whereNotNull('slots');
        
        // $query->when(isset($instructor_id) && !empty($instructor_id), function ($query) use ($instructor_id) {
        //     $query->whereHas('instructor', function ($query) use ($instructor_id) {
        //         $query->where('instructor_id', $instructor_id);
        //     });
        // });
        
        // $classes = $query->get();
        
        // return $classes;
        
        // $classes = Classes::join('instructors', 'instructors.id', '=', 'classes.instructor_id')
        //     ->leftJoin('schedules', 'schedules.id', '=', 'classes.schedule_id')
        //     ->join('curriculumsubjects', 'curriculumsubjects.id', '=', 'classes.curriculumsubject_id')
        //     ->join('subjectinfos', 'subjectinfos.id', '=', 'curriculumsubjects.subjectinfo_id')
        //     ->join('sectioninfos', 'sectioninfos.id', '=', 'classes.sectioninfo_id')
        //     ->where('classes.period_id', $period_id)
        //     ->where('classes.dissolved', '!=', 1)
        //     ->whereNull('classes.merge')
        //     ->whereNotNull('classes.slots')
        //     ->when(isset($instructor_id) && !empty($instructor_id), function ($query) use ($instructor_id) {
        //         $query->where('instructors.instructor_id', $instructor_id);
        //     })
        //     ->orderBy('instructors.last_name', 'ASC')
        //     ->get();

        // return $classes;

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
            'classes_schedules.from_time'
        );
        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id');
        $query->leftJoin('classes_schedules', 'classes.id', '=', 'classes_schedules.class_id');
        $query->where('classes.period_id', $period_id)
            ->where('classes.dissolved', '!=', 1)
            ->whereNull('classes.merge')
            ->whereNotNull('classes.slots')
            ->when(isset($instructor_id) && !empty($instructor_id), function ($query) use ($instructor_id) {
                $query->where('classes.instructor_id', $instructor_id);
            })
            ->groupBy('classes.id')
            ->orderBy('instructors.last_name')
            ->orderByRaw("FIELD(classes_schedules.day, 'M', 'T', 'W', 'TH', 'F', 'S', 'SU')")
            ->orderBy('classes_schedules.from_time');

        $classes = $query->get();

        return $classes;

    }
}
