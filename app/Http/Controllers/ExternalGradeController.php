<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExternalGradeRequest;
use App\Http\Requests\UpdateExternalGradeRequest;
use App\Libs\Helpers;
use App\Models\Period;
use App\Models\Remark;
use App\Models\School;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Models\ExternalGrade;
use App\Services\Grade\ExternalGradeService;
use App\Services\Grade\GradeService;

class ExternalGradeController extends Controller
{
    protected $externalGradeService;

    public function __construct(ExternalGradeService $externalGradeService)
    {
        $this->externalGradeService = $externalGradeService;
        Helpers::setLoad(['jquery_externalgrade.js', 'select2.full.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = Period::all()->sortBy('year')->sortBy('priority_lvl');
        $schools = School::all()->sortBy('code');
        $programs = Program::all()->sortBy('code');
        $remarks = Remark::all();

        return view('gradeexternal.index', compact('periods', 'schools', 'programs', 'remarks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // $schools = School::all()->sortBy('code');
        // $programs = Program::all()->sortBy('code');
        // $remarks = Remark::all();

        // return view('gradeexternal.create', compact('schools', 'programs', 'remarks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExternalGradeRequest $request)
    {
        $return = $this->externalGradeService->storeExternalGrade($request);

        return response()->json(['data' => $return]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExternalGrade  $externalGrade
     * @return \Illuminate\Http\Response
     */
    public function show(ExternalGrade $externalGrade)
    {
        dd($externalGrade);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExternalGrade  $externalGrade
     * @return \Illuminate\Http\Response
     */
    public function edit(ExternalGrade $gradeexternal)
    {
        $gradeexternal->load(['gradeinfo' => fn($query) => $query->with('school','program')]);

        return response()->json(['data' => $gradeexternal]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExternalGrade  $externalGrade
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExternalGradeRequest $request, ExternalGrade $gradeexternal)
    {
        $return = $this->externalGradeService->updateExternalGrade($request, $gradeexternal);

        return response()->json(['data' => $return]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExternalGrade  $externalGrade
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExternalGrade $gradeexternal)
    {
        $return = $this->externalGradeService->deleteExternalGrade($gradeexternal, $gradeexternal->grade_id);

        return response()->json(['data' => $return]);
    }

    public function getallexternalgrade($student_id, $period_id)
    {
        $gradeService = new GradeService();

        $grades = $gradeService->getAllExternalGrades($student_id, $period_id);

        return response()->json(['data' => $grades]);
    }

    public function externalgradesubjects($grade_id)
    {
        $external_subjects = $this->externalGradeService->getExternalGradeSubjects($grade_id);

        return view('gradeexternal.return_externalgrades', compact('external_subjects'));
    }
}
