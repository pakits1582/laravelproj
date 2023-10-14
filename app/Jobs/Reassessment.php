<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

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

        $this->enrollment->update(['enrolled_units' => $this->total_units]);
        $this->enrollment->assessment()->update(['amount' => $this->total_fees]);

        $this->enrollment->assessment->breakdowns()->delete();
        $this->enrollment->assessment->details()->delete();
        $this->enrollment->assessment->exam()->delete();

        if($this->assessment_details)
        {
            $fees = [];
            foreach ($this->assessment_details as $key => $fee) 
            {
                $fees[] = [
                    'assessment_id' => $this->enrollment->assessment->id,
                    'fee_id' => $fee['fee_id'],
                    'amount' => $fee['amount'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            $this->enrollment->assessment->details()->insert($fees);
        }

        if($this->assessment_breakdowns)
        {
            $assessbreakdowns = [];
            foreach ($this->assessment_breakdowns as $key => $assessbreakdown) 
            {
                $assessbreakdowns[] = [
                    'assessment_id' => $this->enrollment->assessment->id,
                    'fee_type_id' => $assessbreakdown['fee_type_id'],
                    'amount' => $assessbreakdown['amount'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            $this->enrollment->assessment->breakdowns()->insert($assessbreakdowns);
        }

        $this->enrollment->assessment->exam()->create($this->assessment_exams);

        if($this->enrollment->validated == 1)
        {
            $this->enrollment->studentledger_assessment()->update(['amount' => $this->total_fees]);
        
            if($this->assessment_details) 
            {
                $studentledger_details = [];
                foreach ($this->assessment_details as $key => $assessment_detail) 
                {
                    $studentledger_details[] = [
                        'studentledger_id' => $this->enrollment->studentledger_assessment->id,
                        'fee_id' => $assessment_detail['fee_id'],
                        'amount' =>  $assessment_detail['amount'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
                $this->enrollment->studentledger_assessment->details()->delete();
                $this->enrollment->studentledger_assessment->details()->insert($studentledger_details);
            }
        }
    }
}
