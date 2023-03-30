<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Models\Scholarshipdiscount;
use App\Models\ScholarshipdiscountGrant;
use App\Services\ScholarshipdiscountService;
use App\Http\Requests\StoreScholarshipdiscount;
use App\Http\Requests\StoreScholarshipdiscountRequest;
use App\Http\Requests\UpdateScholarshipdiscountRequest;

class ScholarshipdiscountController extends Controller
{

    protected $scholarshipdiscountService;

    public function __construct(ScholarshipdiscountService $scholarshipdiscountService)
    {
        $this->scholarshipdiscountService = $scholarshipdiscountService;
        Helpers::setLoad(['jquery_scholarshipdiscount.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $scholarshipdiscounts = $this->scholarshipdiscountService->returnScholarhipAndDiscounts($request);

        if($request->ajax())
        {
            return view('scholarshipdiscount.return_scholarshipdiscount', compact('scholarshipdiscounts'));
        }

        return view('scholarshipdiscount.index', compact('scholarshipdiscounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('scholarshipdiscount.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScholarshipdiscountRequest $request)
    {
        $insert = Scholarshipdiscount::firstOrCreate($request->validated(), $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'Scholarship/Discount sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, scholarship/discount already exists!'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Scholarshipdiscount  $scholarshipdiscount
     * @return \Illuminate\Http\Response
     */
    public function show(Scholarshipdiscount $scholarshipdiscount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Scholarshipdiscount  $scholarshipdiscount
     * @return \Illuminate\Http\Response
     */
    public function edit(Scholarshipdiscount $scholarshipdiscount)
    {
        return view('scholarshipdiscount.edit', compact('scholarshipdiscount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scholarshipdiscount  $scholarshipdiscount
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScholarshipdiscountRequest $request, Scholarshipdiscount $scholarshipdiscount)
    {
        $scholarshipdiscount->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Scholarship/Discount sucessfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Scholarshipdiscount  $scholarshipdiscount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Scholarshipdiscount $scholarshipdiscount)
    {
        //
    }

    public function grant()
    {
        $scholarshipdiscounts = Scholarshipdiscount::orderBy('description')->get();
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        
        return view('scholarshipdiscount.grant.index', compact('scholarshipdiscounts', 'periods'));
    }

    public function scholarshipdiscountgrants(Request $request)
    {
        $scholarshipdiscountgrants = ScholarshipdiscountGrant::with(['scholarshipdiscount' => function ($q){
            $q->orderBy('description');
        }])->where('enrollment_id', $request->enrollment_id)->get();

        return view('scholarshipdiscount.grant.return_grants', compact('scholarshipdiscountgrants'));
    }

    public function savegrant(Request $request)
    {
        // return $request;
        $grant = $this->scholarshipdiscountService->saveGrant($request);

        return response()->json(['data' => $grant]);
    }
}
