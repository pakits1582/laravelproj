<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodRequest;
use App\Http\Requests\StoreTermRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Libs\Helpers;
use App\Models\Period;
use App\Models\SetupPeriod;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriodController extends Controller
{
    public function __construct()
    {
        //$this->instructorService = $instructorService;
        Helpers::setLoad(['jquery_period.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = Period::all();

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
        $lastprioritylvl = Period::where('year', $request->validated('year'))->max('priority_lvl');
        $prioritylvl = ($lastprioritylvl) ? $lastprioritylvl + 1 : 1;

        $insert = Period::firstOrCreate(['code' => $request->code, 'term_id' => $request->term, 'year' => $request->year], array_merge($request->validated(), ['priority_lvl' => $prioritylvl]));

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Period  $period
     * @return \Illuminate\Http\Response
     */
    public function destroy(Period $period)
    {
        //
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
        $validated = $request->validate([
            'period' => 'required',
        ]);

        $period = Period::find($validated['period']);

        SetupPeriod::where('user_id', Auth::id())->delete();

        SetupPeriod::create([
            'user_id' => Auth::id(),
            'period_id' => $validated['period'],
        ]);

        session()->put('current_period', $period->id);
        session()->put('periodname', $period->name);

        return response()->json([
            'success' => true,
            'message' => 'Period successfully changed!',
            'alert' => 'alert-success',
        ], 200);
    }
}
