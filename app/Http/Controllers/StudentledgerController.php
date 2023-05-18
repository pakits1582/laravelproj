<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Studentledger;
use App\Services\PeriodService;
use App\Services\StudentService;
use App\Models\UserPaymentperiod;
use Illuminate\Support\Facades\Auth;
use App\Services\StudentledgerService;

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
        $soas = $this->studentledgerService->returnStatementOfAccounts($request->student_id, $request->period_id);

        if($soas && count($soas) > 1)
        {
            return view('studentledger.statementofaccounts', compact('soas'));
        }

        $has_adjustment = $request->has_adjustment;
        return view('studentledger.statementofaccount', compact('soas', 'has_adjustment'));
    }

    public function previousbalancerefund(Request $request)
    {
        $previous_balances = $this->studentledgerService->returnPreviousBalanceRefund($request->student_id, $request->period_id);

        return view('studentledger.previousbalance', compact('previous_balances'));
    }

    public function paymentschedules(Request $request)
    {
        $payment_schedule = $this->studentledgerService->returnPaymentSchedules(
            $request->student_id, 
            $request->period_id,
            $request->educational_level_id, 
            $request->enrollment
        );

        //return $payment_schedule;
        return view('studentledger.payment_schedule', compact('payment_schedule'));
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
       // return $request;

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
}
