<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Period;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\Studentledger;
use App\Services\PeriodService;
use App\Services\StudentService;
use App\Models\UserPaymentperiod;
use Illuminate\Support\Facades\Auth;
use App\Services\StudentledgerService;
use App\Services\Enrollment\EnrollmentService;

class StudentledgerController extends Controller
{
    protected $studentledgerService;

    public function __construct(StudentledgerService $studentledgerService)
    {
        $this->studentledgerService = $studentledgerService;
        Helpers::setLoad(['jquery_studentledger.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);

        return view('studentledger.index', compact('periods'));
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
     * @param  \App\Models\Studentledger  $studentledger
     * @return \Illuminate\Http\Response
     */
    public function show(Studentledger $studentledger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Studentledger  $studentledger
     * @return \Illuminate\Http\Response
     */
    public function edit(Studentledger $studentledger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Studentledger  $studentledger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Studentledger $studentledger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Studentledger  $studentledger
     * @return \Illuminate\Http\Response
     */
    public function destroy(Studentledger $studentledger)
    {
        //
    }

    public function statementofaccounts(Request $request)
    {
        $all_soas = $this->studentledgerService->getAllStatementOfAccounts($request->student_id, $request->period_id);
        $soas = $this->studentledgerService->returnStatementOfAccounts($all_soas);

        if($soas && count($soas) > 1)
        {
            return view('studentledger.statementofaccounts', compact('soas'));
        }

        $has_adjustment = $request->has_adjustment;
        $forwardable = true;

        return view('studentledger.statementofaccount', compact('soas', 'has_adjustment', 'forwardable'));
    }

    public function previousbalancerefund(Request $request)
    {
        $all_soas = $this->studentledgerService->getAllStatementOfAccounts($request->student_id);
        $previous_balances = $this->studentledgerService->returnPreviousBalanceRefund($all_soas, $request->period_id);
        $forwardable = true;

        return view('studentledger.previousbalance', compact('previous_balances', 'forwardable'));
    }

    public function paymentschedules(Request $request)
    {
        $soas = $this->studentledgerService->getAllStatementOfAccounts($request->student_id, $request->period_id);

        $payment_schedule = $this->studentledgerService->returnPaymentSchedules(
            $soas,
            $request->period_id,
            $request->educational_level_id, 
            $request->enrollment
        );

        $with_checkbox = true;

        //return $payment_schedule;
        return view('studentledger.payment_schedule', compact('payment_schedule', 'with_checkbox'));
    }

    public function defaultpayperiod(Request $request)
    {
        $pay_period = UserPaymentperiod::updateOrCreate(['user_id' => Auth::id()], ['pay_period' => $request->pay_period]);

        return response()->json(['data' =>[
                'success' => true,
                'message' => 'Default pay period saved!',
                'alert' => 'alert-success'
            ]
        ]);
    }

    public function computepaymentsched(Request $request)
    {
        $payment_schedule = $this->studentledgerService->computePaymentSchedule($request->student_id, $request->period_id, $request->pay_period, $request->enrollment);
        
        return response()->json($payment_schedule);
    }

    public function forwardbalance(Request $request)
    {
        $student = Student::with(['user'])->where('id', $request->student_id)->first();
        
        $soas = $this->studentledgerService->getAllStatementOfAccounts($request->student_id,'');
        $soas_balances = $this->studentledgerService->soaBalance($soas);

        $forward_period = $request->period_id;
        $soa_to_forward = array_values(array_filter($soas_balances, function ($soa) use($forward_period) {
            return $soa['period_id'] == $forward_period;
        }));

        $studentledgers = array_values(array_filter($soas_balances, function ($soa) use($forward_period) {
            return $soa['period_id'] != $forward_period;
        }));

        return view('studentledger.forwardbalance', compact('student', 'soa_to_forward', 'studentledgers'));
    }

    public function saveforwardedbalance(Request $request)
    {
        $save_forward = $this->studentledgerService->saveForwardedBalance($request);

        //return $save_forward;
        return response()->json($save_forward);
    }

    public function studentaccountledger()
    {
        $current_period = session('current_period');

        $student = (new StudentService)->studentInformationByUserId(Auth::id());
        //$enrollment = (new EnrollmentService)->studentEnrollment($student->id, $current_period ,1);

        $enrollment = Enrollment::with(['assessment' => ['exam', 'breakdowns', 'details']])
            ->where('student_id', $student->id)
            ->where('period_id', $current_period)
            ->where('acctok', 1)->first();

        $all_soas = $this->studentledgerService->getAllStatementOfAccounts($student->id);
        $soas = $this->studentledgerService->returnStatementOfAccounts($all_soas, $current_period);

        $previous_balances = $this->studentledgerService->returnPreviousBalanceRefund($all_soas, $current_period);
        $previous_soas = $this->studentledgerService->returnStatementOfAccounts($all_soas, $current_period, '!=');

        $current_soa = array_filter($all_soas, function ($soa) use($current_period) {
            return $soa['period_id'] == $current_period;
        });

        $payment_schedule = $this->studentledgerService->returnPaymentSchedules(
            $current_soa,
            $current_period,
            $student->program->level->id, 
            $enrollment->toArray()
        );

        $has_adjustment = false;
        $forwardable = false;
        $with_checkbox = false;

        return view('studentledger.student.index', compact(
            'student', 
            'previous_balances',
            'soas',
            'has_adjustment',
            'forwardable',
            'with_checkbox',
            'previous_soas',
            'payment_schedule'
        ));
    }
}
