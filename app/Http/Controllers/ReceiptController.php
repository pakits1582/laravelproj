<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Bank;
use App\Libs\Helpers;
use App\Models\Receipt;
use App\Services\FeeService;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use App\Services\ReceiptService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReceiptRequest;

class ReceiptController extends Controller
{
    protected $receiptService;

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
        Helpers::setLoad(['jquery_receipt.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $banks = Bank::all();
        $last_user_receiptno = DB::table('receipts')->select(DB::raw('MAX(receipt_no) AS last_receiptno'))->where('user_id', Auth::id())->get()[0];
        $last_user_receiptno = ($last_user_receiptno->last_receiptno) ? $last_user_receiptno->last_receiptno : 0;

        
        return view('receipt.index', compact('periods', 'banks', 'last_user_receiptno'));
    }

    public function storebank(Request $request)
    {
        $request->validate(['bank' => 'required']);

        $insert = Bank::firstOrCreate(['name' => $request->bank],['name' => $request->bank]);

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Bank successfully added!',
                'alert' => 'alert-success',
                'bank_id' => $insert->id,
                'bank' => $request->bank
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, bank already exists!']);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $fees = (new FeeService)->returnFees($request,true);
        $default_fee = Fee::with(['feetype'])->defaultFee()->first();
        
        return view('receipt.create', compact('fees', 'default_fee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReceiptRequest $request)
    {
        $saved_receipt = $this->receiptService->saveReceipt($request);

        return $saved_receipt;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipt $receipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt)
    {
        //
    }
}
