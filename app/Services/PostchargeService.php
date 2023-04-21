<?php

namespace App\Services;

use App\Models\PaymentSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Enrollment\EnrollmentService;

class PostchargeService
{
   
    public function savePostcharge($request)
    {
        $enrollments = (new EnrollmentService)->filterEnrolledStudents($request->period_id, NULL, NULL, NULL, NULL, $request->input('enrollment_ids'));
        
        $assessment_details_array = [];
        $studentledger_details_array = [];
        $postcharges_array = [];
        $total_amount = array_sum($request->input('amount'));

        $assessments_update_array = [];
        $studentledgers_update_array = [];
        $assessment_exams_update_array = [];

        if($enrollments->isNotEmpty()) 
        {   
            $payment_schedules = PaymentSchedule::with(['paymentmode'])->where('period_id', $request->input('period_id'))->get();

            foreach ($enrollments as $key => $enrollment) 
            {
                $level_payment_schedules = $payment_schedules->where('educational_level_id', $enrollment->program->educational_level_id)->toArray();

                return $level_payment_schedules;

                $assessments_update_array[] = [
                    'id' => $enrollment->assessment->id,
                    'enrollment_id' => $enrollment->id,
                    'period_id' => $request->input('period_id'),
                    'amount' => $enrollment->assessment->amount+$total_amount
                ];

                $studentledgers_update_array[] = [
                    'id' => $enrollment->studentledger_assessment->id,
                    'enrollment_id' => $enrollment->id,
                    'source' => $enrollment->assessment->id,
                    'type' => 'A',
                    'amount' => $enrollment->studentledger_assessment->amount+$total_amount
                ];

                foreach ($request->input('fees') as $k => $fee) 
                {
                    $studentledger_details_array[] = [
                        'studentledger_id' => $enrollment->studentledger_assessment->id,
                        'fee_id' => $fee,
                        'amount' => $request->input('amount')[$k],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    $assessment_details_array[] = [
                        'studentledger_id' => $enrollment->assessment->id,
                        'fee_id' => $fee,
                        'amount' => $request->input('amount')[$k],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    $postcharges_array[] = [
                        'enrollment_id' => $enrollment->id,
                        'fee_id' => $fee,
                        'amount' => $request->input('amount')[$k],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                }
            }
        }

        return $studentledgers_update_array;

        //Assessment::upsert($assessments_update_array, ['id'], ['amount']);
        //Studentledger::upsert($studentledgers_update_array, ['id'], ['amount']);
        //return $assessment_update_array;

        //return Postcharge::insert($postcharges_array);
    }
}