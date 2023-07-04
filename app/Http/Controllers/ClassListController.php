<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\ClassesService;
use App\Services\ClassListService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function transfer(Request $request)
    {
        $class = Classes::with([
            'sectioninfo',
            'curriculumsubject.subjectinfo',
        ])->findOrfail($request->class_id);

        $enrollment_ids = array_column($request->students_to_transfer, 'enrollment_id');

        $enrollments = Enrollment::with([
            'student',
            'student.user:id,idno',
            'program:id,code'
        ])->whereIn("id", $enrollment_ids)->get();
        
        foreach ($enrollments as $enrollment) {
            foreach ($request->students_to_transfer as $transferStudent) {
                if ($enrollment->id == $transferStudent['enrollment_id']) {
                    $enrollment->class_id = $transferStudent['class_id'];
                    break;
                }
            }
        }

        return view('classlist.transfer', compact('class', 'enrollments'));
    }

    public function searchtransfertoclass(Request $request)
    {
        try {
            $class = Classes::with([
                'instructor:id,last_name,first_name,name_suffix,middle_name', 
                'schedule:id,schedule',
                'curriculumsubject.subjectinfo:id,code,name'
            ])->where('code', $request->keyword)->where('period_id', session('current_period'))->firstOrFail();
                
            return response()->json($class);

        } catch (\Exception $e) {
        
            return [
                'success' => false,
                'message' => 'No class subject found!',
                'alert' => 'alert-danger',
                'status' => 401
            ];
        }
    }

    public function savetransferstudents(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'enrollment_ids' => 'required',
            'class_ids' => 'required',
            'transferto_class_id' => 'required',
            'transferfrom_class_id' => 'required',
        ]);
    
        if ($validator->fails()) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Can not perform requested action!',
                'alert' => 'alert-danger'
            ]);
        }
        
        $validatedData = $validator->validated();

        $transfer = $this->classlistService->transferstudents($validatedData);

        return response()->json($transfer);
    }
}
