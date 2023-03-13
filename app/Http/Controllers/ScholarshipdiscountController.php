<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Models\Scholarshipdiscount;
use App\Services\ScholarshipdiscountService;
use App\Http\Requests\StoreScholarshipdiscount;
use App\Http\Requests\StoreScholarshipdiscountRequest;

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
        dd($request->validated());
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scholarshipdiscount  $scholarshipdiscount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Scholarshipdiscount $scholarshipdiscount)
    {
        //
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
}
