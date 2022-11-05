<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Period;
use App\Models\Remark;
use App\Models\School;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Models\ExternalGrade;
use App\Services\Grade\ExternalGradeService;

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

        return view('gradeexternal.index', compact('periods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $schools = School::all()->sortBy('code');
        $programs = Program::all()->sortBy('code');
        $remarks = Remark::all();

        return view('gradeexternal.create', compact('schools', 'programs', 'remarks'));
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
     * @param  \App\Models\ExternalGrade  $externalGrade
     * @return \Illuminate\Http\Response
     */
    public function show(ExternalGrade $externalGrade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExternalGrade  $externalGrade
     * @return \Illuminate\Http\Response
     */
    public function edit(ExternalGrade $externalGrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExternalGrade  $externalGrade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExternalGrade $externalGrade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExternalGrade  $externalGrade
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExternalGrade $externalGrade)
    {
        //
    }
}
