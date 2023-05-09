<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ValidationService
{

    public function validateEnrollment($validation)
    {
        if($validation->validated == 1)
        {
            return [
                'success' => false,
                'message' => 'Student is already validated, uncheck validated checkbox to unvalidate enrollment.',
                'alert' => 'alert-danger',
                'status' => 401
            ];
        }

        DB::beginTransaction();

        $this->validation($validation);

        DB::commit();

        return [
            'success' => true,
            'message' => 'Student enrollment successfully validated.',
            'alert' => 'alert-success',
            'status' => 200
        ];
    }

    public function validation($enrollment)
    {
        $enrollment->validated = 1;   
        $enrollment->save();

        $enrollment->grade()->delete();
        $enrollment->studentledger_assessment()->delete();
        

        $grade = $enrollment->grade()->create([
            'enrollment_id' => $enrollment->id,
            'student_id' => $enrollment->student_id,
            'period_id' => $enrollment->period_id,
            'program_id' => $enrollment->program_id,
            'origin' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if($enrollment->enrolled_classes->isNotEmpty()) 
        {
            $internal_grades = [];
            foreach ($enrollment->enrolled_classes as $key => $enrolled_class) 
            {
                $internal_grades[] = [
                    'grade_id' => $grade->id,
                    'class_id' => $enrolled_class->class->id,
                    'units' => $enrolled_class->class->units,
                    'grading_system_id' => NULL,
                    'completion_grade' => NULL,
                    'final' => 0,
                    'user_id' =>  $enrolled_class->user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $grade->internalgrades()->insert($internal_grades);
        }

        $studentledger = $enrollment->studentledger_assessment()->create([
            'enrollment_id' => $enrollment->id,
            'source_id' => $enrollment->assessment->id,
            'type' => 'A',
            'amount' => $enrollment->assessment->amount,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if($enrollment->assessment->details->isNotEmpty()) 
        {
            $studentledger_details = [];
            foreach ($enrollment->assessment->details as $key => $assessment_detail) 
            {
                $studentledger_details[] = [
                    'studentledger_id' => $studentledger->id,
                    'fee_id' => $assessment_detail->fee_id,
                    'amount' =>  $assessment_detail->amount,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $studentledger->details()->insert($studentledger_details);
        }
    }

    public function unvalidateEnrollment($enrollment)
    {
        DB::beginTransaction();

        $enrollment->validated = 0;   
        $enrollment->save();

        $enrollment->grade()->delete();
        $enrollment->studentledger_assessment()->delete();
        
        DB::commit();

        return [
            'success' => true,
            'message' => 'Student enrollment successfully unvalidated.',
            'alert' => 'alert-success',
            'status' => 200
        ];
    }
}