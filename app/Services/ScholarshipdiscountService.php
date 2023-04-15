<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Enrollment;
use App\Models\Scholarshipdiscount;
use App\Models\ScholarshipdiscountGrant;
use App\Models\Studentledger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScholarshipdiscountService
{

    public function returnScholarshipAndDiscounts($request, $all = false)
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

    public function returnStudentScholarshipdiscountGrants($enrollment_id, $period_id = NULL)
    {
        $query = ScholarshipdiscountGrant::with(['scholarshipdiscount' => function ($q){
            $q->orderBy('description');
        }])->where('enrollment_id', $enrollment_id);

        if($period_id != NULL)
        {
            $query->whereHas('enrollment', function($query) use($period_id){
                $query->where('period_id', $period_id);
            });
        }

        return $query->get();
    }

    public function saveGrant($request)
    {
        DB::beginTransaction();

        $enrollment = Enrollment::with(
            [
                'assessment' => [
                    'breakdowns' => ['fee_type'],
                    'details'
                ]
            ])->find($request->enrollment_id);
        
        $grant = Scholarshipdiscount::find($request->scholarshipdiscount_id);

        if(empty($enrollment->assessment->breakdowns)) 
        {
            return [
                'success' => false,
                'message' => 'Something went wrong! Please re-assess student enrollment.',
                'alert' => 'alert-danger'
            ];
        }

        if(!isset($grant))
        {
            return [
                'success' => false,
                'message' => 'Something went wrong! Scholarship/Discount not found.',
                'alert' => 'alert-danger'
            ];
        }

       // return $enrollment->assessment->breakdowns;

        $assessed_totaltuition  = 0;
        $assessed_labfeetotal   = 0;
        $assessed_miscfeetotal  = 0;
        $assessed_otherfeetotal = 0;
        $assessed_totalassessment = 0;

        foreach ($enrollment->assessment->breakdowns as $key => $v) {
            $assessed_totalassessment += $v->amount;
            switch($v->fee_type->type) {
                case 'Tuition Fees':
                    $assessed_totaltuition += $v->amount;
                    break;
                case 'Miscellaneous Fees':
                    $assessed_miscfeetotal += $v->amount;
                    break;
                case 'Other Miscellaneous Fees':
                    $assessed_otherfeetotal += $v->amount;
                    break;
                case 'Laboratory Fees':
                    $assessed_labfeetotal += $v->amount;
                    break;
            }
        }
        
        $tuition         = 0;
        $miscellaneous   = 0;
        $othermisc       = 0;
        $laboratory      = 0;
        $totalassessment = 0;
        $totaldeduction  = 0;
        
        // TUITION COMPUTATION
        if ($grant->tuition != 0) {
            $tuition = ($grant->tuition_type == 'rate')
                ? $grant->tuition / 100 * $assessed_totaltuition
                : $grant->tuition;
        }

        // MISC COMPUTATION
        if ($grant->miscellaneous != 0) {
            $misc = ($grant->miscellaneous_type == 'rate')
                ? $grant->miscellaneous / 100 * $assessed_miscfeetotal
                : $grant->miscellaneous;
        }

        // OTHERMISC COMPUTATION
        if ($grant->othermisc != 0) {
            $othermisc = ($grant->othermisc_type == 'rate')
                ? $grant->othermisc / 100 * $assessed_otherfeetotal
                : $grant->othermisc;
        }

        // LABORATORY COMPUTATION
        if ($grant->laboratory != 0) {
            $lab = ($grant->laboratory_type == 'rate')
                ? $grant->laboratory / 100 * $assessed_labfeetotal
                : $grant->laboratory;
        }

        // TOTALASS COMPUTATION
        if ($grant->totalassessment != 0) {
            $totalass = ($grant->totalassessment_type == 'rate')
                ? $grant->totalassessment / 100 * $assessed_totalassessment
                : $grant->totalassessment;
        }

        $totaldeduction = $tuition+$miscellaneous+$othermisc+$laboratory+$totalassessment;

        $postData = [
            'enrollment_id' => $request->enrollment_id,
            'scholarshipdiscount_id' => $request->scholarshipdiscount_id,
            'tuition'         => $tuition,
            'miscellaneous'   => $miscellaneous,
            'othermisc'       => $othermisc,
            'laboratory'      => $laboratory,
            'totalassessment' => $totalassessment,
            'totaldeduction'  => $totaldeduction,
            'user_id' => Auth::user()->id,
        ];

        $insert = ScholarshipdiscountGrant::firstOrCreate($postData, $postData);

        if ($insert->wasRecentlyCreated) 
        {
            $grant_type = ($grant->type == 1) ? 'S' : 'D';

            $ledgerData = [
                'enrollment_id' => $request->enrollment_id,
                'source_id' => $insert->id,
                'type' => $grant_type,
                'amount' => '-'.$totaldeduction,
                'user_id' => Auth::user()->id
            ];

            $studentledger = Studentledger::firstOrCreate($ledgerData, $ledgerData);
            $ledgerDetails = (new StudentledgerService())->insertCreditStudentledgerDetails($studentledger, $enrollment, $totaldeduction);

            DB::commit();
                        
            return [
                'success' => true,
                'message' => 'Scholarship/Discount sucessfully granted!.',
                'alert' => 'alert-success'
            ];
        }

        return [
            'success' => false,
            'message' => 'Scholarship/Discount already granted!.',
            'alert' => 'alert-danger'
        ];
    }

    
   
}