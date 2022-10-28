<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeeRequest;
use App\Http\Requests\UpdateFeeRequest;
use App\Models\Fee;
use App\Libs\Helpers;
use App\Models\FeeType;
use App\Services\FeeService;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    protected $periodfeeServiceervice;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
        Helpers::setLoad(['jquery_fee.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Fee::with('feetype')->orderBy('code');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', '%'.$request->keyword.'%')
                ->orWhere('name', 'like', '%'.$request->keyword.'%');
            });
        }

        $fees =  $query->paginate(10);

        if($request->ajax())
        {
            return view('fee.return_fees', compact('fees'));
        }

        return view('fee.index', compact('fees'));
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
        $insert = Fee::firstOrCreate(['fee_type_id' => $request->fee_type_id, 'code' => $request->code, 'name' => $request->name], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'Fee sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, fee already exists!'])->withInput();
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
}
