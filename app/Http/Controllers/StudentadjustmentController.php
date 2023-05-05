<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Models\Studentledger;
use App\Services\PeriodService;
use App\Models\Studentadjusment;
use App\Models\Studentadjustment;
use Illuminate\Support\Facades\Auth;
use App\Services\StudentadjustmentService;
use App\Http\Requests\StoreStudentadjustmentRequest;

class StudentadjustmentController extends Controller
{

    protected $studentadjustmentService;

    public function __construct(StudentadjustmentService $studentadjustmentService)
    {
        $this->studentadjustmentService = $studentadjustmentService;
        Helpers::setLoad(['jquery_studentadjustment.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);

        return view('studentadjustment.index', compact('periods'));

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
    public function store(StoreStudentadjustmentRequest $request)
    {
        $adjustment = $this->studentadjustmentService->saveStudentadjustment($request);

        return response()->json(['data' => $adjustment]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Studentadjustment  $studentadjustment
     * @return \Illuminate\Http\Response
     */
    public function show(Studentadjustment $studentadjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Studentadjustment  $studentadjustment
     * @return \Illuminate\Http\Response
     */
    public function edit(Studentadjustment $studentadjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Studentadjustment  $studentadjustment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Studentadjustment $studentadjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Studentadjustment  $studentadjustment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Studentadjustment $studentadjustment)
    {
         $studentledger = Studentledger::where('enrollment_id', $studentadjustment->enrollment_id)
                        ->where('source_id', $studentadjustment->id)->where('type', 'SA')->firstOrFail();

        $studentledger->delete();
        $studentadjustment->delete();

        return response()->json(['data' =>[
                'success' => true,
                'message' => 'Student adjustment sucessfully deleted!',
                'alert' => 'alert-success'
            ]
        ]);
    }

    public function studentadjustments(Request $request)
    {
        $studentadjustments = $this->studentadjustmentService->returnStudentStudentadjustments($request->enrollment_id, $request->period_id);

        return view('studentadjustment.return_adjustments', compact('studentadjustments'));
    }
}
