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

        $validation->validated = 1;   
        $validation->save();

        $validation->grade()->delete();
        $validation->studentledger_assessment()->delete();
        

        $grade = $validation->grade()->create([
            'enrollment_id' => $validation->id,
            'student_id' => $validation->student_id,
            'period_id' => $validation->period_id,
            'program_id' => $validation->program_id,
            'origin' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if($validation->enrolled_classes->isNotEmpty()) 
        {
            $internal_grades = [];
            foreach ($validation->enrolled_classes as $key => $enrolled_class) 
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

        $studentledger = $validation->studentledger_assessment()->create([
            'enrollment_id' => $validation->id,
            'source_id' => $validation->assessment->id,
            'type' => 'A',
            'amount' => $validation->assessment->amount,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if($validation->assessment->details->isNotEmpty()) 
        {
            $studentledger_details = [];
            foreach ($validation->assessment->details as $key => $assessment_detail) 
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

        DB::commit();

        return [
            'success' => true,
            'message' => 'Student enrollment successfully validated.',
            'alert' => 'alert-success',
            'status' => 200
        ];
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