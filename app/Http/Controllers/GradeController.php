<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeInformationRequest;
use App\Libs\Helpers;
use App\Models\Grade;
use App\Models\IssueingOffice;
use App\Models\School;
use App\Models\Program;
use App\Models\Soresolution;
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
    public function store(StoreGradeInformationRequest $request)
    {
        $grade_information = $this->gradeService->saveGradeInformationAndRemarks($request);

        return response()->json($grade_information);
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
        $soresolutions = Soresolution::all();
        $isseuing_offices = IssueingOffice::all();
        
        return view('grade.grade_information', compact('gradeinfo', 'schools', 'programs', 'soresolutions', 'isseuing_offices'));
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

    public function savesoresolution(Request $request)
    {
        $rules = [
            'conjunction' => 'required|string|max:255',
            'title' => 'required|string|max:255',
        ];
    
        $validatedData = $request->validate($rules);

        $insert = Soresolution::firstOrCreate(['conjunction' => $request->conjunction, 'title' => $request->title], $validatedData);

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'S.O./Resolution successfully added!',
                'alert' => 'alert-success',
                'soresolution_id' => $insert->id,
                'title' => strtoupper($validatedData['title'])
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, S.O./Resolution already exists!']);
    }

    public function saveissuedby(Request $request)
    {
        $rules = [
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ];
    
        $validatedData = $request->validate($rules);

        $insert = IssueingOffice::firstOrCreate(['code' => $request->code, 'name' => $request->name], $validatedData);

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Issueing Office successfully added!',
                'alert' => 'alert-success',
                'issueing_office_id' => $insert->id,
                'code' => strtoupper($validatedData['code'])
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, Issueing Office already exists!']);
    }

    // public function getgradeinfobystudentandperiod(Request $request)
    // {
    //     $grade = $this->gradeService->getGradeInfoByStudentAndPeriod($request->student, session('current_period'), $request->origin);

    //     return response()->json(['data' => $grade]);
    // }
}
