<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Studentledger;
use App\Models\Studentadjustment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentadjustmentService
{
    public function returnStudentStudentadjustments($enrollment_id, $period_id = NULL)
    {
        $query = Studentadjustment::where('enrollment_id', $enrollment_id);

        if($period_id != NULL)
        {
            $query->whereHas('enrollment', function($query) use($period_id){
                $query->where('period_id', $period_id);
            });
        }

        return $query->get();
    }

    public function saveStudentadjustment($request)
    {
        DB::beginTransaction();

        $enrollment = Enrollment::with(
            [
                'assessment' => [
                    'breakdowns' => ['fee_type'],
                    'details'
                ]
            ])->find($request->enrollment_id);

        switch ($request->type) {
            case '1':
                $particular = 'CREDIT ADJUSTMENT - '.$request->particular;
                break;
            case '2':
                $particular = 'DEBIT ADJUSTMENT - '.$request->particular;
                break;
            case '3':
                $particular = 'DEBIT REFUND - '.$request->particular;
                break;
        }

        $postData = [
            'enrollment_id' => $request->validated()['enrollment_id'],
            'type' => $request->validated()['type'],
            'amount' => $request->validated()['amount'],
            'particular' => strtoupper($particular)
        ];

        $insert = Studentadjustment::firstOrCreate($postData, $postData+['user_id' =>  Auth::user()->id]);

        if ($insert->wasRecentlyCreated) {

            $amount = ($request->validated()['type'] == 1) ? '-'.$request->validated()['amount'] : $request->validated()['amount'];

            $ledgerData = [
                'enrollment_id' => $request->enrollment_id,
                'source_id' => $insert->id,
                'type' => 'SA',
                'amount' => $amount,
                'user_id' => Auth::user()->id
            ];

            $studentledger = Studentledger::firstOrCreate($ledgerData, $ledgerData);

            if($request->validated()['type'] == 1)
            {
                $ledgerDetails = (new StudentledgerService())->insertCreditStudentledgerDetails($studentledger, $enrollment, $amount);
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Student adjustment sucessfully added!',
                'alert' => 'alert-success'
            ];        
        }

        return [
            'success' => false,
            'message' => 'Duplicate entry, student adjustment already exists!',
            'alert' => 'alert-danger'
        ];
    }

}