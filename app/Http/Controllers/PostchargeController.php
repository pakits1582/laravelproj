<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Libs\Helpers;
use App\Models\Enrollment;
use App\Models\Postcharge;
use App\Services\ClassesService;
use App\Services\Enrollment\EnrollmentService;
use App\Services\FeeService;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\ProgramService;
use App\Services\PostchargeService;

class PostchargeController extends Controller
{
    protected $postchargeService;

    public function __construct(PostchargeService $postchargeService)
    {
        $this->postchargeService = $postchargeService;
        Helpers::setLoad(['jquery_postcharge.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $programs = (new ProgramService)->returnAllPrograms(0, true, true);
        $additionalfees = Fee::additionalFees()->get();
        $classes = (new ClassesService)->offeredClassesOfPeriod(session('current_period'));

        return view('postcharge.index', compact('periods', 'programs', 'additionalfees', 'classes'));
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
     * @param  \App\Models\Postcharge  $postcharge
     * @return \Illuminate\Http\Response
     */
    public function show(Postcharge $postcharge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Postcharge  $postcharge
     * @return \Illuminate\Http\Response
     */
    public function edit(Postcharge $postcharge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Postcharge  $postcharge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Postcharge $postcharge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Postcharge  $postcharge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Postcharge $postcharge)
    {
        //
    }

    public function filterstudents(Request $request)
    {
        $filteredstudents = (new EnrollmentService)->filterEnrolledStudents($request->period_id, $request->program_id);

        //return $filteredstudents;
        return view('postcharge.return_students', compact('filteredstudents'));
    }
}
