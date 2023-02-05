<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\Fee;
use App\Models\FeeSetup;
use App\Models\FeeType;
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

    public function returnSetupFees($request)
    {
        $query = FeeSetup::with(['educlevel', 'college', 'program', 'subject', 'fee' => ['feetype']]);

        $query->when(filled($request->period_id), function ($q) use($request) {
            return $q->where('period_id', $request->period_id);
        });

        return $query->get();
    }

}
