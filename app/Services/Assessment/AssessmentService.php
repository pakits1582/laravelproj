<?php

namespace App\Services\Assessment;

use App\Models\AssessmentDetail;
use Illuminate\Support\Facades\DB;
use App\Models\AssessmentBreakdown;
use Illuminate\Support\Facades\Auth;

class AssessmentService
{

    public function updateAssessment($request, $assessment)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required',
            'assessment_id' => 'required',
        ]);    

        DB::beginTransaction();

        $assessment->assessed = 1;
        $assessment->amount = $request->totalfees;
        $assessment->user_id = Auth::id();
        $assessment->save();

        $assessment->enrollment->assessed = 1;
        $assessment->enrollment->enrolled_units = $request->enrolled_units;
        $assessment->enrollment->save();

        $assessment->breakdowns()->delete();
        $assessment->details()->delete();
        $assessment->exam()->delete();

        if($request->filled('fees'))
        {
            $fees = [];
            foreach ($request->fees as $key => $fee) 
            {
                $fee_info = explode("-", $fee);

                $fees[] = [
                    'assessment_id' => $assessment->id,
                    'fee_id' => $fee_info[0],
                    'amount' => $fee_info[1],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $assessment->details()->insert($fees);
        }

        if($request->filled('assessbreakdown'))
        {
            $assessbreakdowns = [];
            foreach ($request->assessbreakdown as $key => $assessbreakdown) 
            {
                $assessbreakdown_info = explode("-", $assessbreakdown);

                $assessbreakdowns[] = [
                    'assessment_id' => $assessment->id,
                    'fee_type_id' => $assessbreakdown_info[0],
                    'amount' => $assessbreakdown_info[1],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $assessment->breakdowns()->insert($assessbreakdowns);
        }

        if($request->filled('exams'))
        {
            $exams = ['downpayment' => $request->downpayment, 'amount' => $request->totalfees];
            $x = 1;
            foreach ($request->exams as $key => $exam) 
            {
                $exams['exam'.$x] = $exam;
                $x++;
            }

            $assessment->exam()->create($exams);
        }

        DB::commit();

        return true;
    }
}