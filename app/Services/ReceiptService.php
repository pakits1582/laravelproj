<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\Enrollment;
use App\Models\ReceiptDetail;
use Illuminate\Support\Str;
use App\Models\Studentledger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReceiptService
{
    public function saveReceipt($request)
    {
        $inassessfeesArr  = [];
        $nonassessfeesArr = [];

        $nonAssessReceiptDetailsArray = [];
        $inAssessReceiptDetailsArray = [];
        $receiptArray = [];

        //SEPARATE INASSESS FEES AND NON ASSESS FEES
        $total_inassess = 0;
        
        foreach ($request->fees as $key => $fee) 
        {
            $receiptArray[] = [
                'period_id' => $request->period_id,
                'receipt_no' => $request->receipt_no,
                'fee_id' => $fee,
                'student_id' => $request->student_id,
                'bank_id' => $request->bank_id,
                'check_no' => $request->check_no,
                'deposit_date' => $request->deposit_date,
                'receipt_date' => $request->receipt_date,
                'amount' => str_replace(",", "", $request->amount[$key]),
                'in_assess' => $request->inassess[$key],
                'user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            if($request->inassess[$key] == 1)
            {   
                $inassessfeesArr[] = ['fee_id' => $fee, 'code' => $request->feecodes[$key], 'amount' => str_replace(",", "", $request->amount[$key]), 'inassess' => $request->inassess[$key]];
                $total_inassess += str_replace(",", "", $request->amount[$key]);
            }else{
                $nonassessfeesArr[] = ['fee_id' => $fee, 'code' => $request->feecodes[$key], 'amount' => str_replace(",", "", $request->amount[$key]), 'inassess' => $request->inassess[$key]];
                $nonAssessReceiptDetailsArray[] = ['receipt_no' => $request->receipt_no, 'fee_id' => $fee, 'amount' => str_replace(",", "", $request->amount[$key]), 'payor_name' => $request->payor_name];
            }
        }

        $enrollment = Enrollment::with(['student'])->where('student_id', $request->student_id)->where('period_id', $request->period_id)->first();
    
        if($enrollment)
        { 
            $result = strcmp(Str::of($request->payor_name)->replaceMatches('/ {2,}/', ' '), Str::of($enrollment->student->name)->replaceMatches('/ {2,}/', ' '));

            if($result !== 0)
            {
                return [
                    'success' => false,
                    'message' => 'The payor name is not equal to the student seleted! Please check entries!',
                    'alert' => 'alert-danger'
                ];
            }

            if(!empty($inassessfeesArr) && $enrollment->assessed == 0)
            {
                return [
                    'success' => false,
                    'message' => 'One of the fee selected is an assessed fee, Student enrollment is not yet assessed!',
                    'alert' => 'alert-danger'
                ];
            }
        }else{
            if(!empty($inassessfeesArr))
            {
                return [
                    'success' => false,
                    'message' => 'The student is not enrolled this semester and one of the fees selected is in assessment. If student has previous balance, please click on previous balance table!',
                    'alert' => 'alert-danger'
                ];
            }
        }

        DB::beginTransaction();

        if($enrollment && !empty($inassessfeesArr))
        {
            if($enrollment->validated == 0 && $enrollment->assessed == 1)
            {
                //VALIDATE ENROLLMENT
                $enrollment->load([
                    'studentledger_assessment',
                    'grade',
                    'enrolled_classes' => ['class'],
                    'assessment' => ['details']
                ]);

                $validation = (new ValidationService())->validation($enrollment);
            }

            $ledgerData = [
                'enrollment_id' => $enrollment->id,
                'source_id' => $request->receipt_no,
                'type' => 'R',
                'amount' => '-'.$total_inassess,
                'user_id' => Auth::user()->id
            ];

            $studentledger = Studentledger::firstOrCreate($ledgerData, $ledgerData);
            $ledgerDetails = (new StudentledgerService())->insertCreditStudentledgerDetails($studentledger, $enrollment, $total_inassess);
            
            if(!empty($ledgerDetails))
            {
                foreach ($ledgerDetails as $key => $fee) 
                {
                    $inAssessReceiptDetailsArray[] = ['receipt_no' => $request->receipt_no, 'source_id' => $enrollment->assessment->id, 'type' => 'A', 'fee_id' => $fee['fee_id'], 'amount' => str_replace(",", "", $fee['amount']), 'payor_name' => $request->payor_name];
                }
            }
        }

        Receipt::insert($receiptArray);
        ReceiptDetail::insert($nonAssessReceiptDetailsArray);
        ReceiptDetail::insert($inAssessReceiptDetailsArray);

        DB::commit();

        return [
            'success' => true,
            'message' => 'Payment sucessfully saved!',
            'alert' => 'alert-success'
        ];
    }


}
