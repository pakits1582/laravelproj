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

        //return $request->student_id;

        // $enrollment = Enrollment::with(
        //     [
        //         'assessment' => [
        //             'breakdowns' => ['fee_type'],
        //             'details'
        //         ]
        //     ])->find($request->enrollment_id);

        $enrollment = Enrollment::with(
            [
                'assessment' => [
                    'breakdowns' => ['fee_type'],
                    'details'
                ]
            ])->where('student_id', $request['student_id'])->where('period_id', $request['period_id'])->first();

        if(!$enrollment)
        {
            return [
                'success' => false,
                'message' => 'Student is not enrolled in the selected semester!',
                'alert' => 'alert-danger'
            ];
        }

        switch ($request['type']) {
            case '1':
                $particular = 'CREDIT ADJUSTMENT - '.$request['particular'];
                break;
            case '2':
                $particular = 'DEBIT ADJUSTMENT - '.$request['particular'];
                break;
            case '3':
                $particular = 'DEBIT REFUND - '.$request['particular'];
                break;
        }

        $postData = [
            'enrollment_id' => $enrollment->id,
            'type' => $request['type'],
            'amount' => $request['amount'],
            'particular' => strtoupper($particular)
        ];

        $insert = Studentadjustment::firstOrCreate($postData, $postData+['user_id' =>  Auth::user()->id]);

        if ($insert->wasRecentlyCreated) {

            $amount = ($request['type'] == 1) ? '-'.$request['amount'] : $request['amount'];

            $ledgerData = [
                'enrollment_id' => $enrollment->id,
                'source_id' => $insert->id,
                'type' => 'SA',
                'amount' => $amount,
                'user_id' => Auth::user()->id
            ];

            $studentledger = Studentledger::firstOrCreate($ledgerData, $ledgerData);

            if($request['type'] == 1)
            {
                $ledgerDetails = (new StudentledgerService())->insertCreditStudentledgerDetails($studentledger, $enrollment, $request['amount']);
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