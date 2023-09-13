<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\InstructorService;
use App\Http\Requests\StoreOtherAssignmentRequest;
use App\Models\OtherAssignment;
use App\Models\Period;
use App\Services\ClassesService;
use App\Services\FacultyLoadService;

class FacultyLoadController extends Controller
{
    protected $facultyLoadService;
    public function __construct(FacultyLoadService $facultyLoadService)
    {
        $this->facultyLoadService = $facultyLoadService;
        Helpers::setLoad(['jquery_facultyload.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }
    
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $faculty_loads = $this->facultyLoadService->facultyload(session('current_period'));
        $faculty = $faculty_loads->unique('instructor_id');

        return view('facultyload.index', compact('periods', 'faculty_loads', 'faculty'));
    }

    public function filterfacultyload(Request $request)
    {
        $faculty_loads = $this->facultyLoadService->facultyload($request->period_id, $request->educational_level_id, $request->college_id, $request->faculty_id);
        $other_assignments = OtherAssignment::where('period_id', $request->period_id)->where('instructor_id', $request->faculty_id)->get() ?? [];

        return view('facultyload.return_facultyload', compact('faculty_loads', 'other_assignments'));
    }

    public function otherassignments(Period $period)
    {
        $instructors = (new InstructorService())->getInstructor();
        
        return view('facultyload.other_assignments', compact('instructors', 'period'));
    }

    public function saveotherassignment(StoreOtherAssignmentRequest $request)
    {
        $insert = $this->facultyLoadService->saveOtherAssignment($request);

        return response()->json($insert);
    }

    public function returnotherassignments(Request $request)
    {
        $other_assignments = OtherAssignment::where('period_id', $request->period_id)->where('instructor_id', $request->instructor_id)->get();
        
        return view('facultyload.return_otherassignments', compact('other_assignments'));
    }

    public function deleteotherassignment(OtherAssignment $otherassignment)
    {
        $delete = $this->facultyLoadService->deleteOtherAssignment($otherassignment);

        return response()->json($delete);
    }

    public function scheduletable(Request $request)
    {
        $class_schedules = (new ClassesService())->scheduletableClassSchedules('', $request->period_id, $request->instructor_id);
        $with_faculty = false;

        return view('class.schedule_table', compact('class_schedules', 'with_faculty'));
    }
}
