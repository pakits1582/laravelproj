<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Reassessment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $enrollment;
    protected $total_units;
    protected $total_fees;
    protected $assessment_details;
    protected $assessment_breakdowns;
    protected $assessment_exams;

    public function __construct(
        $enrollment, 
        $total_units, 
        $total_fees, 
        $assessment_details,
        $assessment_breakdowns, 
        $assessment_exams
    ) {
        $this->enrollment = $enrollment;
        $this->total_units = $total_units;
        $this->total_fees = $total_fees;
        $this->assessment_details = $assessment_details;
        $this->assessment_breakdowns = $assessment_breakdowns;
        $this->assessment_exams = $assessment_exams;
    }
    
    public function handle()
    {
        DB::beginTransaction();

        $enrollment->update(['enrolled_units' => $total_units]);
        $enrollment->assessment()->update(['amount' => $total_fees]);

        $enrollment->assessment->breakdowns()->delete();
        $enrollment->assessment->details()->delete();
        $enrollment->assessment->exam()->delete();

        if($assessment_details)
        {
            $fees = [];
            foreach ($assessment_details as $key => $fee) 
            {
                $fees[] = [
                    'assessment_id' => $enrollment->assessment->id,
                    'fee_id' => $fee['fee_id'],
                    'amount' => $fee['amount'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            $enrollment->assessment->details()->insert($fees);
        }

        if($assessment_breakdowns)
        {
            $assessbreakdowns = [];
            foreach ($assessment_breakdowns as $key => $assessbreakdown) 
            {
                $assessbreakdowns[] = [
                    'assessment_id' => $enrollment->assessment->id,
                    'fee_type_id' => $assessbreakdown['fee_type_id'],
                    'amount' => $assessbreakdown['amount'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            $enrollment->assessment->breakdowns()->insert($assessbreakdowns);
        }

        $enrollment->assessment->exam()->create($assessment_exams);

        if($enrollment->validated == 1)
        {
            $enrollment->studentledger_assessment()->update(['amount' => $total_fees]);
        
            if($assessment_details) 
            {
                $studentledger_details = [];
                foreach ($assessment_details as $key => $assessment_detail) 
                {
                    $studentledger_details[] = [
                        'studentledger_id' => $enrollment->studentledger_assessment->id,
                        'fee_id' => $assessment_detail['fee_id'],
                        'amount' =>  $assessment_detail['amount'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
                $enrollment->studentledger_assessment->details()->delete();
                $enrollment->studentledger_assessment->details()->insert($studentledger_details);
            }
        }
    }
}
