<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Fee;
use App\Libs\Helpers;
use App\Models\FeeType;
use App\Models\FeeSetup;
use App\Models\SetupPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class FeeService
{
    //

    public function returnFees($request, $all = false)
    {
        $query = Fee::with('feetype')->orderBy('code');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', '%'.$request->keyword.'%')
                ->orWhere('name', 'like', '%'.$request->keyword.'%');
            });
        }
        
        if($request->has('fee_type_id') && !empty($request->fee_type_id)) {
            $query->where('fee_type_id', $request->fee_type_id);
        }

        if($all){
            return $query->get();
        }
    
        return $query->paginate(10);
    }

    public function feeTypeInfo($fee_type_id)
    {
        return FeeType::findOrfail($fee_type_id);
    }

    public function storeFee($request)
    {
        if($request->iscompound){
            $fee_type_info = $this->feeTypeInfo($request->fee_type_id);

            if($fee_type_info->inassess === 1)
            {
                return back()->with(['alert-class' => 'alert-danger', 'message' => 'You can only compound non-assessed fees!'])->withInput();
            }
        }
        
        $insert = Fee::firstOrCreate(['fee_type_id' => $request->fee_type_id, 'code' => $request->code, 'name' => $request->name], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'Fee sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, fee already exists!'])->withInput();
    }

    public function returnSetupFees($period_id, $educational_level_id = '')
    {
        $query = FeeSetup::with(['educlevel', 'college', 'program', 'subject', 'fee' => ['feetype']]);

        $query->when(filled($period_id), function ($q) use($period_id) {
            return $q->where('period_id', $period_id);
        });

        $query->when(filled($educational_level_id), function ($q) use($educational_level_id) {
            return $q->where('educational_level_id', $educational_level_id);
        });


        return $query->get();
    }

    public function copysetupfees($request)
    {
        $setup_fees = FeeSetup::whereIn('id', $request->setup_fee_id)->get();
       
        if(!$setup_fees->isEmpty())
        {
            $setup_fees_arr = [];
            foreach ($setup_fees as $key => $setup_fee) {
                $setup_fees_arr[] = [
                    'period_id' => $request->period_copyto,
                    'educational_level_id' => $setup_fee->educational_level_id,
                    'college_id' => $setup_fee->college_id,
                    'program_id' => $setup_fee->program_id,
                    'year_level' => $setup_fee->year_level,
                    'subject_id' => $setup_fee->subject_id,
                    'new' => $setup_fee->new,
                    'old' => $setup_fee->old,
                    'transferee' => $setup_fee->transferee,
                    'cross_enrollee' => $setup_fee->cross_enrollee,
                    'foreigner' => $setup_fee->foreigner,
                    'returnee' => $setup_fee->returnee,
                    'professional' => $setup_fee->professional,
                    'sex' => $setup_fee->sex,
                    'fee_id' => $setup_fee->fee_id,
                    'rate' => $setup_fee->rate,
                    'payment_scheme' => $setup_fee->payment_scheme,
                    'user_id' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            FeeSetup::insert($setup_fees_arr);

            return [
                'success' => true,
                'message' => 'Assessment Fees Successfully Copied!',
                'alert' => 'alert-success',
                'status' => 200
            ];
        }

        return [
                'success' => false,
                'message' => 'Period has no fees, nothing to copy!',
                'alert' => 'alert-danger',
                'status' => 401
            ];
    }

}
