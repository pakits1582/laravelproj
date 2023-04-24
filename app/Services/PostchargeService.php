<?php

namespace App\Services;

use App\Models\FeeType;
use App\Models\Assessment;
use App\Models\Postcharge;
use App\Models\Studentledger;
use App\Models\AssessmentExam;
use App\Models\PaymentSchedule;
use Illuminate\Support\Facades\DB;
use App\Models\AssessmentBreakdown;
use App\Models\AssessmentDetail;
use App\Models\StudentledgerDetail;
use Illuminate\Support\Facades\Auth;
use App\Services\Enrollment\EnrollmentService;

class PostchargeService
{
   
    public function savePostcharge($request)
    {
        DB::beginTransaction();

        $enrollments = (new EnrollmentService)->filterEnrolledStudents($request->period_id, NULL, NULL, NULL, NULL, $request->input('enrollment_ids'));
        $additional_fee = FeeType::select('id')->where('type', 'Additional Fees')->get();
        
        $postcharges_array = [];
        $total_amount = array_sum($request->input('amount'));

        $assessments_update_array = [];
        $assessment_details_array = [];
        $assessment_exams_update_array = [];
        $assessment_breakdowns_update_array = [];

        $studentledgers_update_array = [];
        $studentledger_details_array = [];

        if($enrollments->isNotEmpty()) 
        {   
            $payment_schedules = PaymentSchedule::with(['paymentmode'])->where('period_id', $request->input('period_id'))->get();

            foreach ($enrollments as $key => $enrollment) 
            {
                $level_payment_schedules = $payment_schedules->where('educational_level_id', $enrollment->program->educational_level_id)->toArray();
                $assessment_breakdown_additional_fees = $this->recomputeAssessmentBreakdownAdditionalFees($enrollment->assessment->id, $total_amount, $enrollment->assessment->breakdowns, $additional_fee[0]->id);
                $assessment_exam = $this->recomputeAssessmentExams($level_payment_schedules, $enrollment->assessment->breakdowns, $enrollment->assessment->exam, $assessment_breakdown_additional_fees);
                
                $assessment_exams_update_array[] = $assessment_exam;
                $assessment_breakdowns_update_array[] = $assessment_breakdown_additional_fees;

                $assessments_update_array[] = [
                    'id' => $enrollment->assessment->id,
                    'enrollment_id' => $enrollment->id,
                    'period_id' => $request->input('period_id'),
                    'amount' => $enrollment->assessment->amount+$total_amount
                ];

                $studentledgers_update_array[] = [
                    'id' => $enrollment->studentledger_assessment->id,
                    'enrollment_id' => $enrollment->id,
                    'source_id' => $enrollment->assessment->id,
                    'type' => 'A',
                    'amount' => $enrollment->studentledger_assessment->amount+$total_amount,
                    'user_id' => $enrollment->studentledger_assessment->user_id,
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
                        'assessment_id' => $enrollment->assessment->id,
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
        
        AssessmentExam::upsert($assessment_exams_update_array, ['id'], ['amount','downpayment','exam1','exam2','exam3','exam4','exam5','exam6','exam7','exam8','exam9','exam10']);
        AssessmentBreakdown::upsert($assessment_breakdowns_update_array, ['id'], ['amount']);
        Assessment::upsert($assessments_update_array, ['id'], ['amount']);
        AssessmentDetail::insert($assessment_details_array);
        Studentledger::upsert($studentledgers_update_array, ['id'], ['amount']);
        StudentledgerDetail::insert($studentledger_details_array);
        Postcharge::insert($postcharges_array);

        DB::commit();

        return [
            'success' => true,
            'message' => 'Post Charge Successfully Saved!',
            'alert' => 'alert-success',
            'status' => 200
        ];
    }

    public function recomputeAssessmentBreakdownAdditionalFees($assessment_id, $total_amount, $assessment_breakdowns, $additonal_fee_id)
    {
        $assessment_additional_fee = $assessment_breakdowns->where('fee_type_id', $additonal_fee_id)->first();

        $current_additonal_fee_total = $assessment_additional_fee['amount'] ?? 0;

        return [
            'id' => $assessment_additional_fee['id'] ?? 0, 
            'assessment_id' => $assessment_additional_fee['assessment_id'] ?? $assessment_id, 
            'fee_type_id' => $assessment_additional_fee['fee_type_id'] ?? $additonal_fee_id,
            'amount' => $current_additonal_fee_total+$total_amount ?? $total_amount
        ];
    }

    public function recomputeAssessmentExams($payment_schedules, $assessment_breakdowns, $assessment_exam, $assessment_breakdown_additional_fees)
    {
        $totaltuition = 0;
        $labfeetotal = 0;
        $miscfeetotal = 0;
        $otherfeetotal = 0;
        $additionalfeetotal = $assessment_breakdown_additional_fees['amount'];

        foreach ($assessment_breakdowns as $k => $v) 
        {
            switch ($v->fee_type->type) 
            {
                case 'Tuition Fees':
                    $totaltuition += $v->amount;
                    break;
                case 'Laboratory Fees':
                    $labfeetotal += $v->amount;
                    break;
                case 'Miscellaneous Fees':
                    $miscfeetotal += $v->amount;
                    break; 
                case 'Other Miscellaneous Fees':
                    $otherfeetotal += $v->amount;
                    break;   
            }
        }

        $downpayment = 0;
        $fixedtuition = 0;
        $fixedmisc = 0;
        $fixedother = 0;
        
        foreach ($payment_schedules as $key => $ps) 
        {
            $topay = 0;

            $fixedtuition = ($ps['payment_type'] == 2) ? $ps['tuition'] : 0;
            $fixedmisc    = ($ps['payment_type'] == 2) ? $ps['miscellaneous'] : 0;
            $fixedother   = ($ps['payment_type'] == 2) ? $ps['others'] : 0;

            $totaltuition  = $totaltuition-$fixedtuition;
            $miscfeetotal  = $miscfeetotal-$fixedmisc;
            $otherfeetotal = $otherfeetotal-$fixedother;

            $tuitioncomp = ($ps['payment_type'] == 1) ? ($ps['tuition']/100) * $totaltuition : $ps['tuition'];
            $misccomp    = ($ps['payment_type'] == 1) ? ($ps['miscellaneous']/100) * $miscfeetotal : $ps['miscellaneous'];
            $otherscomp  = ($ps['payment_type'] == 1) ? ($ps['others']/100) * $otherfeetotal : $ps['others'];

            if(strcasecmp($ps['description'], 'downpayment') == 0)
            {
                $downpayment = $tuitioncomp+$misccomp+$otherscomp+$labfeetotal+$additionalfeetotal;
            }else{
                $exams[] = $tuitioncomp+$misccomp+$otherscomp;
            }
        }

        $assess_exam = [
            'id' => $assessment_exam->id,
            'assessment_id' => $assessment_exam->assessment_id,
            'amount' =>  $totaltuition+$labfeetotal+$miscfeetotal+$otherfeetotal+$additionalfeetotal,
            'downpayment' => $downpayment,
        ];

        $x = 1;
        foreach ($exams as $key => $exam) 
        {
            $assess_exam['exam'.$x] = $exam;
            $x++;
        }

        return $assess_exam;
    }

    public function removePostcharge($request)
    {
        DB::beginTransaction();

        $enrollments = (new EnrollmentService)->filterEnrolledStudents($request->period_id, NULL, NULL, NULL, NULL, $request->input('enrollment_ids'));
        
        if($enrollments->isNotEmpty()) 
        {   
            //return $enrollments;
            //delete fee from assessment_details
            $assessment_ids = ($enrollments->isNotEmpty()) ? $enrollments->pluck('assessment.id')->toArray() : [];
            $assessment_details = AssessmentDetail::where('fee_id', $request->fee_id)->whereIn('assessment_id', $assessment_ids)->delete();

            //delete fee from studentledger_details
            $studentledger_ids = ($enrollments->isNotEmpty()) ? $enrollments->pluck('studentledger_assessment.id')->toArray() : [];
            $studentledger_details = StudentledgerDetail::where('fee_id', $request->fee_id)->whereIn('studentledger_id', $studentledger_ids)->delete();
            
            //delete student from postcharge
            Postcharge::where('fee_id', $request->fee_id)->whereIn('enrollment_id', $request->enrollment_ids)->delete();




            
            //subtract from assessment the amount of postcharge
            //recompute assessment exams
            //subtract from assessment_breakdowns the amount of postcharge

            
            //subtract from studentledger the amount of postcharge
            
        }

    }
}