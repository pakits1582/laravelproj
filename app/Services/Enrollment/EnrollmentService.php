<?php

namespace App\Services\Enrollment;

use App\Models\Enrollment;

class EnrollmentService
{
    //
    public function handleStudentEnrollmentInfo($student_id, $studentinfo)
    {
        $data = [];

        $data['student'] = $studentinfo;
        $data['probi']   = 0;
        $data['balance']  = 0;

        $enrollment = $this->studentEnrollment($student_id, session('current_period'));
        $data['enrolled'] = ($enrollment !== false) ? (($enrollment->acctok === 1 && $enrollment->assessed === 1) ? 1 : 0) : 0;

        $last_enrollment = $this->studentLastEnrollment($student_id);
        // if($last_enrollment !== false)
        // {
            
            $data['probi'] = $this->checkIfStudentIsOnProbation($student_id, '$last_enrollment->period_id');
            $data['balance'] = $this->checkIfstudentHasAccountBalance($student_id, '$last_enrollment->period_id');
            
        //}
        
        return $data;
    }

    public function studentEnrollment($student_id, $period_id)
    {
        $enrollment = Enrollment::with(['period', 'student', 'program', 'curriculum', 'section'])
                    ->where('student_id', $student_id)
                    ->where('period_id', $period_id)->first();

        return ($enrollment) ? $enrollment : false;
    }

    public function studentLastEnrollment($student_id)
    {
        $last_enrollment = Enrollment::where('student_id', $student_id)->latest('id')->first();

        return ($last_enrollment) ? $last_enrollment : false;
    }
    
    public function studentAllEnrollments($student_id)
    {
        $enrollments = Enrollment::with(['period'=> function ($q){
                                            $q->orderBy('year', 'DESC');
                                        }, 'student', 'program'])->where('student_id', $student_id)->get();

        return (!$enrollments->isEmpty()) ? $enrollments : false;
    }

    public function checkIfStudentIsOnProbation($student_id, $period_id)
    {
        return false;
    }

    public function checkIfstudentHasAccountBalance($student_id, $period_id)
    {
        $data = [];

        $data['hasbal'] = 1;
        $data['previous_balance'] = ['period' => 'First Semester, 2021-2022', 'balance' => 5000];

        return false;
    }

    public function insertStudentEnrollment()
    {

    }

}
