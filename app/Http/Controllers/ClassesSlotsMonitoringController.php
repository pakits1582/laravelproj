<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\ClassesService;
use Illuminate\Support\Facades\DB;

class ClassesSlotsMonitoringController extends Controller
{
    protected $classesService;

    public function __construct(ClassesService $classesService)
    {
        $this->classesService = $classesService;
        Helpers::setLoad(['jquery_classesslotsmonitoring.js', 'select2.full.min.js']);
    }

    public function index()
    {

        return view('class.slotsmonitoring.index');
    }

    public function slotmonitoring($period_id, $educational_level_id = '', $college_id = '', $section_id = '', $keyword = '')
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
            DB::raw("CONCAT(instructors.last_name, ', ', instructors.first_name, ' ', instructors.name_suffix, ' ', instructors.middle_name) AS full_name")
        );
        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id');
        $query->where('classes.period_id', $period_id);

        $query->when(isset($educational_level_id) && !empty($educational_level_id), function ($query) use ($educational_level_id) {
            $query->where('programs.educational_level_id', $educational_level_id);
        });

        $query->when(isset($college_id) && !empty($college_id), function ($query) use ($college_id) {
            $query->where('programs.college_id', $college_id);
        });

        $query->when(isset($section_id) && !empty($section_id), function ($query) use ($section_id) {
            $query->where('classes.section_id', $section_id);
        });

    }
}
