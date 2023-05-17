<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Receipt;
use App\Models\Enrollment;
use Illuminate\Support\Str;
use App\Models\ReceiptDetail;
use App\Models\Studentledger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReceiptService
{
    public function saveReceipt($request)
    {
        $receipt = Receipt::where('receipt_no', $request->receipt_no)->first();

        if($receipt)
        {
            return [
                'success' => false,
                'message' => 'Receipt already used!',
                'alert' => 'alert-danger'
            ];
        }
        
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
                'receipt_date' => Carbon::create($request->receipt_date)->setTimeFromTimeString(Carbon::now()->toTimeString()),
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
                $nonAssessReceiptDetailsArray[] = [
                    'receipt_no' => $request->receipt_no, 
                    'fee_id' => $fee, 
                    'amount' => str_replace(",", "", $request->amount[$key]), 
                    'payor_name' => strtoupper($request->payor_name),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
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
                    $inAssessReceiptDetailsArray[] = [
                        'receipt_no' => $request->receipt_no, 
                        'source_id' => $enrollment->assessment->id, 
                        'type' => 'A', 'fee_id' => $fee['fee_id'], 
                        'amount' => str_replace(",", "", $fee['amount']), 
                        'payor_name' => $request->payor_name,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
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

    public function receiptInfo($receipt_no)
    {
        $receipt_info = Receipt::with(['period','student' => ['user'], 'fee' => ['feetype'], 'bank', 'details'])->where('receipt_no', $receipt_no)->get();
        
        if($receipt_info->isEmpty())
        {
            return false;
        }

        $receipt_details = [];
        $total_amount = 0;

        foreach ($receipt_info as $key => $receipt) 
        {
            $receipt_details[] = [
                'id' => $receipt->id,
                'fee_id' => $receipt->fee_id,
                'fee_code' => $receipt->fee->code,
                'fee_name' => $receipt->fee->name,
                'fee_type' => $receipt->fee->feetype->type,
                'amount' => $receipt->amount,
                'inassess' => $receipt->inassess
            ];
            $total_amount += $receipt->amount;
        }

        $receipt_info = [
            'student_id' => $receipt_info[0]->student_id,
            'student_name' => ($receipt_info[0]->student->name) ?? '',
            'student_idno' => ($receipt_info[0]->student->user->idno) ?? '',
            'payor_name' => (!empty($receipt_info[0]->details[0]->payor_name)) ? $receipt_info[0]->details[0]->payor_name : (($receipt_info[0]->student_id) ? $receipt_info[0]->student->name : ''),
            'receipt_date' => $receipt_info[0]->receipt_date,
            'period_id' => $receipt_info[0]->period_id,
            'period_name' => $receipt_info[0]->period->name,
            'bank_id' => $receipt_info[0]->bank_id,
            'check_no' => $receipt_info[0]->check_no,
            'deposit_date' => $receipt_info[0]->deposit_date,
            'cancelled' => $receipt_info[0]->cancelled,
            'total_amount' => number_format($total_amount,2),
            'receipt_details' => $receipt_details
        ];

        return $receipt_info;
    }

    public function cancelReceipt($request)
    {
        if(!$request->cancel_remark)
        {
            return [
                'success' => false,
                'message' => 'Cancellation remark is required!',
                'alert' => 'alert-danger'
            ];
        }

        $receipt_info = Receipt::with(['details','receipt_ledger' => ['details']])->where('receipt_no', $request->receipt_no)->first();

        if(!$receipt_info)
        {
            return [
                'success' => false,
                'message' => 'Receipt Number is not yet in use, you can not cancel unused receipt.',
                'alert' => 'alert-danger'
            ];
        }

        DB::beginTransaction();

        if($receipt_info->student_id != NULL)
        {
            $receipt_info->details()->delete();
        }
        
        $receipt_info->update(['cancelled' => 1, 'cancel_remark' => strtoupper($request->cancel_remark)]);

        if ($receipt_info->receipt_ledger && $receipt_info->receipt_ledger->details) 
        {
            if ($receipt_info->receipt_ledger->details->count() > 0) 
            {
                $receipt_info->receipt_ledger->details()->delete();
            }
        }
        
        DB::commit();

        return [
            'success' => true,
            'message' => 'Receipt cancelled sucessfully!',
            'alert' => 'alert-success'
        ];
    }
}
