<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Scholarshipdiscount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipdiscountService
{

    public function returnScholarhipAndDiscounts($request, $all = false)
    {
        $query = Scholarshipdiscount::orderBy('code');

        if($request->filled('keyword')) 
        {
            $query->where(function($query) use($request){
                $query->where('code', 'like', $request->keyword.'%')
                ->orWhere('description', 'like', $request->keyword.'%');
            });
        }
    
        if($request->filled('type'))
        {
            $query->where('type', $request->type);
        }

        if($all){
            return $query->get();
        }
    
        return $query->paginate(10);
    }

    public function saveGrant($request)
    {
        $enrollment = Enrollment::with(
            [
                'assessment' => [
                    'breakdowns' => ['fee_type']
                ]
            ])->find($request->enrollment_id);

        return $enrollment;
    }
   
}