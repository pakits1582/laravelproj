<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use Illuminate\Support\Facades\DB;
use App\Services\InstructorService;
use App\Http\Requests\StoreOtherAssignmentRequest;
use App\Models\OtherAssignment;
use Illuminate\Support\Facades\Auth;

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

    public function filterfacultyload(Request $request)
    {
        $faculty_loads = $this->facultyload($request->period_id, $request->educational_level_id, $request->college_id, $request->faculty_id);
        
        return view('facultyload.return_facultyload', compact('faculty_loads'));
    }

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

    public function otherassignments()
    {
        $instructors = (new InstructorService())->getInstructor();

        return view('facultyload.other_assignments', compact('instructors'));
    }

    public function saveotherassignment(StoreOtherAssignmentRequest $request)
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
            return response()->json([
                'success' => true,
                'message' => 'Other assignment successfully added!',
                'alert' => 'alert-success',
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, other assignment already exists!']);
    
    }
}
