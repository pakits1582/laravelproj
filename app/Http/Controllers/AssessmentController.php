<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Assessment;
use App\Services\FeeService;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\PaymentSchedule;
use App\Services\PeriodService;
use App\Services\Assessment\AssessmentService;
use App\Services\Enrollment\EnrollmentService;

class AssessmentController extends Controller
{

    protected $assessmentService;

    public function __construct(AssessmentService $assessmentService)
    {
        $this->assessmentService = $assessmentService;
        Helpers::setLoad(['jquery_assessment.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('assessment.index');
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
    public function show(Assessment $assessment)
    {
        $assessment->load(['enrollment' =>['period', 'student' => ['user'], 'program', 'curriculum', 'section']]);
        $configuration = Configuration::take(1)->first();

        $enrolled_classes  = (new EnrollmentService())->enrolledClassSubjects($assessment->enrollment_id);
        $setup_fees        = (new FeeService())->returnSetupFees($assessment->period_id, $assessment->enrollment->program->educational_level_id);
        $payment_schedules = PaymentSchedule::with(['paymentmode'])->where('period_id', session('current_period'))->where('educational_level_id', $assessment->enrollment->program->educational_level_id)->get();
        //POSTCHARGES
        //PREVIOUS BALANCE
        
        return view('assessment.assessment_preview', compact('assessment','configuration','enrolled_classes', 'setup_fees', 'payment_schedules'));
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

  
}
