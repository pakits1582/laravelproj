<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Grade;
use App\Models\School;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\Grade\GradeService;
use App\Services\Grade\ExternalGradeService;
use App\Services\Grade\InternalGradeService;

class GradeController extends Controller
{

    protected $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
        Helpers::setLoad(['jquery_grades.js', 'select2.full.min.js']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);

        return view('grade.index', compact('periods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gradeinfo = $this->gradeService->returnGradeInfo($id);
        $schools = School::all()->sortBy('code');
        $programs = Program::all()->sortBy('code');
        
        return view('grade.grade_information', compact('gradeinfo', 'schools', 'programs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function gradeinfo(Request $request)
    {
        $grade = Grade::oforigin($request->origin)->where('student_id', $request->student_id)->where('period_id', $request->period_id)->first();
        
        return response()->json($grade);
    }

    public function gradefile(Request $request)
    {
        $grade_files = $this->gradeService->returnGradeFiles($request);

        return view('grade.gradefile', compact('grade_files'));
    }


    // public function getgradeinfobystudentandperiod(Request $request)
    // {
    //     $grade = $this->gradeService->getGradeInfoByStudentAndPeriod($request->student, session('current_period'), $request->origin);

    //     return response()->json(['data' => $grade]);
    // }
}
