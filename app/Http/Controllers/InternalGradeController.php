<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Models\InternalGrade;
use App\Services\InstructorService;
use App\Services\Grade\InternalGradeService;

class InternalGradeController extends Controller
{
    protected $internalGradeService;

    public function __construct(InternalGradeService $internalGradeService)
    {
        $this->internalGradeService = $internalGradeService;
        Helpers::setLoad(['jquery_internalgrade.js', 'select2.full.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('gradeinternal.index');
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
     * @param  \App\Models\InternalGrade  $internalGrade
     * @return \Illuminate\Http\Response
     */
    public function show(InternalGrade $internalGrade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InternalGrade  $internalGrade
     * @return \Illuminate\Http\Response
     */
    public function edit(InternalGrade $internalGrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InternalGrade  $internalGrade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InternalGrade $internalGrade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InternalGrade  $internalGrade
     * @return \Illuminate\Http\Response
     */
    public function destroy(InternalGrade $internalGrade)
    {
        //
    }

    public function internalgrades(Request $request)
    {
        $internal_grades = $this->internalGradeService->getInternalGrades($request->grade_id);

        return $internal_grades;
    }
}
