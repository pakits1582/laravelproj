<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Fee;
use App\Libs\Helpers;
use App\Models\Period;
use App\Models\FeeType;
use App\Models\Program;
use App\Models\FeeSetup;
use App\Services\FeeService;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\ProgramService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreFeeRequest;
use App\Http\Requests\UpdateFeeRequest;
use App\Http\Requests\StoreSetupFeeRequest;

class FeeController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
        Helpers::setLoad(['jquery_fee.js', 'select2.full.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fees = $this->feeService->returnFees($request);

        $fee_types = FeeType::all()->sortBy('order');

        if($request->ajax())
        {
            return view('fee.return_fees', compact('fees'));
        }

        return view('fee.index', compact('fees', 'fee_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fee_types = FeeType::all()->sortBy('order');

        return view('fee.create', compact('fee_types'));
    }

    public function storetype(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|unique:fee_types|max:255',
            'inassess' => '',
        ]);

        $insert = FeeType::firstOrCreate(['type' => $request->type], $validated);

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Type successfully added!',
                'alert' => 'alert-success',
                'values' => $insert
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, fee type already exists!']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFeeRequest $request)
    {
        $insert = $this->feeService->storeFee($request);

        return $insert;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function show(Fee $fee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function edit(Fee $fee)
    {
        $fee_types = FeeType::all()->sortBy('order');

        return view('fee.edit', compact('fee', 'fee_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFeeRequest $request, Fee $fee)
    {
        $fee->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Fee sucessfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fee $fee)
    {
        //
    }

    public function compoundfee()
    {
        $fees = Fee::join('fee_types', 'fees.fee_type_id', 'fee_types.id')->where('fee_types.inassess', 0)->where('fees.iscompound', '!=', 1)->get()->sortBy('fees.code');
    
        return view('fee.compoundfee', compact('fees'));

    }

    public function setupfees(Request $request)
    {
        $fees = $this->feeService->returnFees($request,true);
        $programs = Program::where('active', 1)->orderBy('code')->get();
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        
        $feessetups = $this->feeService->returnSetupFees($request);

        return view('fee.setup.index', compact('fees', 'programs', 'periods', 'feessetups'))->with('selectall', 0);
    }

    public function storesetupfee(StoreSetupFeeRequest $request)
    {
        $insert = FeeSetup::firstOrCreate($request->validated(), $request->validated()+['user_id' => Auth::id()]);

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Setup fee successfully added!',
                'alert' => 'alert-success'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Duplicate entry, fee already exists!',
            'alert' => 'alert-danger'
        ], 200);
    }

    public function editsetupfee($setupfee_id)
    {
        $setupFee = FeeSetup::with(['subject'])->findOrFail($setupfee_id);

        return response()->json(['data' => $setupFee]);
    }

    public function returnfeessetup(Request $request)
    {
        $feessetups = $this->feeService->returnSetupFees($request);

        return view('fee.setup.return_setupfees', compact('feessetups'))->with('selectall', $request->selectall);
    }

    public function deletefeessetup(FeeSetup $setupfee)
    {
        $setupfee->delete();

        return response()->json(['data' => [
            'success' => true,
            'message' => 'Selected setup fee successfully deleted!',
            'alert' => 'alert-success',
            'status' => 200
        ]]);
    }

    public function updatesetupfee(StoreSetupFeeRequest $request, FeeSetup $setupfee)
    {

        $setupfee->update($request->validated());

        return response()->json(['data' => [
            'success' => true,
            'message' => 'Selected setup fee successfully updated!',
            'alert' => 'alert-success',
            'status' => 200
        ]]);
    }

    public function copysetup(Period $period)
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);

        return view('fee.setup.copy_setup', compact('period', 'periods'));
    }

    public function savecopyfees(Request $request)
    {        
        $copy_setup = $this->feeService->copysetupfees($request);

        return response()->json(['data' => $copy_setup]);
    }
}
