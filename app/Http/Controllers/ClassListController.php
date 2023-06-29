<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Services\ClassesService;
use App\Services\ClassListService;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use Illuminate\Support\Facades\DB;

class ClassListController extends Controller
{
    protected $classlistService;

    public function __construct(ClassListService $classlistService)
    {
        $this->classlistService = $classlistService;
        Helpers::setLoad(['jquery_classlist.js', 'select2.full.min.js']);
    }

    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $classlist = $this->classlistService->classlist(session('current_period'), '', '', 100);

        return view('classlist.index', compact('periods', 'classlist'));
    }

    public function filterclasslist(Request $request)
    {
        $classlist = $this->classlistService->classlist($request->period_id, $request->criteria, $request->keyword);

        return view('classlist.return_classlist', compact('classlist'));
    }

    public function show(Classes $class)
    {
        $class_info = (new ClassesService())->displayEnrolledToClassSubject($class);

        return view('classlist.return_classlist_info', $class_info);
        
    }

    public function transferstudents(Request $request)
    {
        $class = Classes::with([
            'sectioninfo',
            'curriculumsubject.subjectinfo',
        ])->findOrfail($request->class_id);

        $students = Enrollment::with([
            'student',
            'student.user:id,idno',
            'program:id,code'
        ])->whereIn("id", $request->enrollment_ids)->get();
        
        return view('classlist.transfer', compact('class', 'students'));
    }
}
