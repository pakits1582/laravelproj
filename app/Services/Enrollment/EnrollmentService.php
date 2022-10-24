<?php

namespace App\Services\Enrollment;

use App\Models\Enrollment;
use App\Services\CurriculumService;

class EnrollmentService
{
    //
    public function handleStudentEnrollmentInfo($student_id, $studentinfo)
    {
        $data = [];

        $data['student'] = $studentinfo;
        $data['probi']   = 0;
        $data['balance']  = 0;

        $enrollment = $this->studentEnrollment($student_id, session('current_period'), 1);
        $data['enrolled'] = ($enrollment !== false) ? (($enrollment->acctok === 1 && $enrollment->assessed === 1) ? 1 : 0) : 0;

        $last_enrollment = $this->studentLastEnrollment($student_id);
        if($last_enrollment !== false)
        {
            
            $data['probi'] = $this->checkIfStudentIsOnProbation($student_id, '$last_enrollment->period_id');
            $data['balance'] = $this->checkIfstudentHasAccountBalance($student_id, '$last_enrollment->period_id');
            
        }
        
        return $data;
    }

    public function studentEnrollment($student_id, $period_id, $acctok = 0)
    {
        $query = Enrollment::with(['period', 'student', 'program', 'curriculum', 'section'])
                    ->where('student_id', $student_id)
                    ->where('period_id', $period_id);
            if($acctok === 1)
            {
                $query->where('acctok', 1);
            }
        $enrollment = $query->first();

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

        // $data['hasbal'] = 1;
        // $data['previous_balance'] = ['period' => 'First Semester, 2021-2022', 'balance' => 5000];

        return $data;
    }

    public function studentEnrollmentUnitsAllowed($curriculum_id, $term_id, $year_level)
    {
        $curriculumService = new CurriculumService();

        $curriculum_subjects = $curriculumService->curriculumSubjects($curriculum_id, $term_id, $year_level);

        return $curriculum_subjects->sum('subjectinfo.units');

    }

    public function insertStudentEnrollment($studentinfo)
    {
        $student = $studentinfo['data'];
        $student_isenrolled = $this->studentEnrollment($student['student']['id'], session('current_period'), 1);

        return $this->studentEnrollmentUnitsAllowed($student['student']['curriculum_id'], session('periodterm'), $student['student']['year_level']);

        // if($student_isenrolled){

        // }

        // $isnew = ($this->studentAllEnrollments === false) ? 1 : 0;

        // $data = [
        //     'period_id' => session('current_period'),
        //     'student_id' => $student['student']['id'],
        //     'program_id' => $student['student']['program_id'],
        //     'curriculum_id' => $student['student']['curriculum_id'],
        //     'new' => $isnew,
        // ];



    }

}

