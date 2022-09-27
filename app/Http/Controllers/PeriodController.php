<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodRequest;
use App\Http\Requests\StoreTermRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Libs\Helpers;
use App\Models\Period;
use App\Models\Term;
use App\Services\PeriodService;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    protected $periodService;

    public function __construct(PeriodService $periodService)
    {
        $this->periodService = $periodService;
        Helpers::setLoad(['jquery_period.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $periods = $this->periodService->returnPeriods($request);

        if($request->ajax())
        {
            return view('period.return_periods', compact('periods'));
        }

        return view('period.index', compact('periods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $terms = Term::all();

        return view('period.create', compact('terms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeriodRequest $request)
    {
        $insert = $this->periodService->insertPeriod($request);

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'Period sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, period already exists!'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Period  $period
     * @return \Illuminate\Http\Response
     */
    public function show(Period $period)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Period  $period
     * @return \Illuminate\Http\Response
     */
    public function edit(Period $period)
    {
        $terms = Term::all();

        return view('period.edit', compact('period', 'terms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Period  $period
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePeriodRequest $request, Period $period)
    {
        $period->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Period sucessfully updated!']);
    }

    public function storeterm(StoreTermRequest $request)
    {
        $insert = Term::firstOrCreate(['term' => $request->term, 'type' => $request->type], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Term successfully added!',
                'alert' => 'alert-success',
                'term_id' => $insert->id,
                'term' => $request->validated('term'),
                'type' => $request->validated('type'),
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, term already exists!']);
    }

    public function changeperiod()
    {
        return view('period.changeperiod');
    }

    public function saveperiod(Request $request)
    {
        $this->periodService->saveUserCurrentPeriod($request);
        
        return response()->json([
            'success' => true,
            'message' => 'Period successfully changed!',
            'alert' => 'alert-success',
        ], 200);
    }
}
