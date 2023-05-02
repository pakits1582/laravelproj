<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Models\Studentledger;
use App\Services\PeriodService;
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

        if($soas){
            if(count($soas) > 1)
            {
                return view('studentledger.statementofaccounts', compact('soas'));
            }

            $has_adjustment = $request->has_adjustment;
            return view('studentledger.statementofaccount', compact('soas', 'has_adjustment'));
        }

        return response()->json(['data' =>[
                'success' => false,
                'message' => 'No records to be displayed!',
                'alert' => 'alert-danger'
            ]
        ]);
    }

    public function previousbalancerefund(Request $request)
    {
        $previous_balances = $this->studentledgerService->returnPreviousBalanceRefund($request->student_id, $request->period_id);

        return view('studentledger.previousbalance', compact('previous_balances'));
        
    }
}
