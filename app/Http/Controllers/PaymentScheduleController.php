<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use App\Models\PaymentSchedule;
use App\Services\PeriodService;
use App\Http\Requests\StorePaymentScheduleRequest;
use App\Http\Requests\UpdatePaymentScheduleRequest;

class PaymentScheduleController extends Controller
{


    public function __construct()
    {
        Helpers::setLoad(['jquery_paymentschedule.js', 'select2.full.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $payment_modes = PaymentMode::all();
        $paymentschedules = PaymentSchedule::with(['educlevel', 'paymentmode'])->where('period_id', session('current_period'))->get();

        return view('paymentschedule.index', compact('periods', 'payment_modes', 'paymentschedules'));
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
    public function store(StorePaymentScheduleRequest $request)
    {
        $paymentschedules = PaymentSchedule::with(['educlevel', 'paymentmode'])
            ->where('period_id', $request->period_id)
            ->where('educational_level_id', $request->educational_level_id)
            ->where('year_level', $request->year_level)
            ->where('payment_mode_id', $request->payment_mode_id)
            ->where('payment_type', $request->payment_type)
        ->get();

        if(!$request->filled('tuition') && !$request->filled('miscellaneous') && !$request->filled('others'))
        {
            return response()->json([
                'success' => false,
                'message' => 'Please fill at least one of the following field (Tuition, Miscellaneous, Others)',
                'alert' => 'alert-danger'
            ], 200);
        }

        if($paymentschedules->count() >= 10)
        {
            return response()->json([
                'success' => false,
                'message' => 'You\'ve already reached the maximum number of 10 payment schedules!',
                'alert' => 'alert-danger'
            ], 200);
        }

        $current_tuition_percentage = 0;
        $current_miscellaneous_percentage = 0;
        $current_others_percentage = 0;
        $error = '';

        if($request->payment_type == 1)
        {
            foreach ($paymentschedules as $key => $v) {
                $current_tuition_percentage += $v->tuition;
                $current_miscellaneous_percentage += $v->miscellaneous;
                $current_others_percentage += $v->others;
            }

            if($current_tuition_percentage == 100 && $request->tuition != 0){
                $error = 'The total tuition fee percentage is already 100%!';	
            }else if($current_miscellaneous_percentage == 100 && $request->miscellaneous != 0){
                $error = 'The total miscellaneous fee percentage is already 100%!';	
            }else if($current_others_percentage == 100 && $request->others != 0){
                $error = 'The total other miscellaneous fee percentage is already 100%!';	
            }else{
                //check remaining percentage
                if($request->tuition > (100 - $current_tuition_percentage)){
                    $error = 'The tuition fee percentage entered is greater than the remaining '.(100 - $current_tuition_percentage).'% percentage!';
                }else if($request->miscellaneous > (100 - $current_miscellaneous_percentage)){
                    $error = 'The miscelleneous fee percentage entered is greater than the remaining '.(100 - $current_miscellaneous_percentage).'% percentage!';
                }else if($request->others > (100 - $current_others_percentage)){
                    $error = 'The other miscelleneous fee percentage entered is greater than the remaining '.(100 - $current_others_percentage).'% percentage!';
                }
            }
        }

        if($error != '')
        {
            return response()->json([
                'success' => false,
                'message' => $error,
                'alert' => 'alert-danger'
            ], 200);
        }

        $insert = PaymentSchedule::firstOrCreate($request->validated(), $request->validated());

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Payment schedule successfully added!',
                'alert' => 'alert-success'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Duplicate entry, payment schedule already exists!',
            'alert' => 'alert-danger'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentSchedule  $paymentSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentSchedule $paymentSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentSchedule  $paymentSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentSchedule $paymentschedule)
    {
        return response()->json($paymentschedule);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentSchedule  $paymentSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentScheduleRequest $request, PaymentSchedule $paymentschedule)
    {
        $paymentschedules = PaymentSchedule::with(['educlevel', 'paymentmode'])
            ->where('period_id', $request->period_id)
            ->where('educational_level_id', $request->educational_level_id)
            ->where('year_level', $request->year_level)
            ->where('payment_mode_id', $request->payment_mode_id)
            ->where('payment_type', $request->payment_type)
            ->where('id', '!=', $paymentschedule->id)
        ->get();

        if(!$request->filled('tuition') && !$request->filled('miscellaneous') && !$request->filled('others'))
        {
            return response()->json([
                'success' => false,
                'message' => 'Please fill at least one of the following field (Tuition, Miscellaneous, Others)',
                'alert' => 'alert-danger'
            ], 200);
        }

        if($paymentschedules->count() >= 10)
        {
            return response()->json([
                'success' => false,
                'message' => 'You\'ve already reached the maximum number of 10 payment schedules!',
                'alert' => 'alert-danger'
            ], 200);
        }

        $current_tuition_percentage = 0;
        $current_miscellaneous_percentage = 0;
        $current_others_percentage = 0;
        $error = '';

        if($request->payment_type == 1)
        {
            foreach ($paymentschedules as $key => $v) {
                $current_tuition_percentage += $v->tuition;
                $current_miscellaneous_percentage += $v->miscellaneous;
                $current_others_percentage += $v->others;
            }

            if($current_tuition_percentage == 100 && $request->tuition != 0){
                $error = 'The total tuition fee percentage is already 100%!';	
            }else if($current_miscellaneous_percentage == 100 && $request->miscellaneous != 0){
                $error = 'The total miscellaneous fee percentage is already 100%!';	
            }else if($current_others_percentage == 100 && $request->others != 0){
                $error = 'The total other miscellaneous fee percentage is already 100%!';	
            }else{
                //check remaining percentage
                if($request->tuition > (100 - $current_tuition_percentage)){
                    $error = 'The tuition fee percentage entered is greater than the remaining '.(100 - $current_tuition_percentage).'% percentage!';
                }else if($request->miscellaneous > (100 - $current_miscellaneous_percentage)){
                    $error = 'The miscelleneous fee percentage entered is greater than the remaining '.(100 - $current_miscellaneous_percentage).'% percentage!';
                }else if($request->others > (100 - $current_others_percentage)){
                    $error = 'The other miscelleneous fee percentage entered is greater than the remaining '.(100 - $current_others_percentage).'% percentage!';
                }
            }
        }

        if($error != '')
        {
            return response()->json([
                'success' => false,
                'message' => $error,
                'alert' => 'alert-danger'
            ], 200);
        }

        $paymentschedule->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payment schedule successfully added!',
            'alert' => 'alert-success'
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentSchedule  $paymentSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentSchedule $paymentschedule)
    {
        $paymentschedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment schedule successfully deleted!',
            'alert' => 'alert-success'
        ], 200);
    }

    public function storepaymentmode(Request $request)
    {
        $insert = PaymentMode::firstOrCreate(['mode' => $request->mode], ['mode' => $request->mode]);

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Mode successfully added!',
                'alert' => 'alert-success',
                'mode_id' => $insert->id,
                'mode' => $request->mode
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, mode already exists!']);
    }

    public function returnpaymentschedules($period_id)
    {
        $paymentschedules = PaymentSchedule::with(['educlevel', 'paymentmode'])->where('period_id', $period_id)->get();

        return view('paymentschedule.return_paymentschedule', compact('paymentschedules'));
    }
}
