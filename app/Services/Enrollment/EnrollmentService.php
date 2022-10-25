<?php

namespace App\Services\Enrollment;

use App\Models\Enrollment;
use App\Models\Term;
use App\Services\CurriculumService;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnSelf;

class EnrollmentService
{
    //
    public function handleStudentEnrollmentInfo($student_id, $studentinfo)
    {
        $data = [];

        $data['student'] = $studentinfo;
        $data['probi']   = 0;
        $data['balance']  = 0;
        $data['enrollment'] = $this->studentEnrollment($student_id, session('current_period'));

        $last_enrollment = $this->studentLastEnrollment($student_id);
        
        if($last_enrollment !== false)
        {
            $data['probi'] = $this->checkIfStudentIsOnProbation($student_id, '$last_enrollment->period_id');
            $data['balance'] = $this->checkIfstudentHasAccountBalance($student_id, '$last_enrollment->period_id');
            
        }
        
        $data['allowed_units'] = $this->studentEnrollmentUnitsAllowed($studentinfo['curriculum_id'], session('periodterm'), $studentinfo['year_level'], $data['probi']);

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
        $last_enrollment = Enrollment::where('student_id', $student_id)->where('validated', 1)->latest('id')->first();

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

    public function studentEnrollmentUnitsAllowed($curriculum_id, $term_id, $year_level, $isProbi)
    {
        $curriculumService = new CurriculumService();
        $curriculum_subjects = $curriculumService->curriculumSubjects($curriculum_id, $term_id, $year_level);

        $curriculum_allowed_units = $curriculum_subjects->sum('subjectinfo.units');

        $term_info = Term::find($term_id);

        $allowed_units = 0;
        $allowed_units = ($curriculum_allowed_units === 0) ? (($term_info->type === 2) ? 9 : 21) : $curriculum_allowed_units;
        
        $allowed_units = ($isProbi === 1) ? 18 : $allowed_units;

        return $allowed_units;
    }

    public function studentYearLevel($year_level, $program_years, $probi, $isnew, $term_id)
    {
        $term_info = Term::find($term_id);

        if(!$term_info)
        {   
            if($term_info->type === 1){
                $year_level = ($probi === 0 && $isnew === 0) ? (($year_level < $program_years) ? $year_level+1 : $year_level) : $year_level;
            }
        }

        return $year_level;
    }

    public function insertStudentEnrollment($studentinfo)
    {
        $student = $studentinfo['data'];

        if($this->studentAllEnrollments($student['student']['id']) === false){
            $isnew = 1;
        }else{
            $isOld = 1;
        }

        // $allowed_units = $this->studentEnrollmentUnitsAllowed($student['student']['curriculum_id'], session('periodterm'), $student['student']['year_level'], $student['probi']);
        $year_level = $this->studentYearLevel($student['student']['year_level'], $student['student']['program']['years'], $student['probi'], $isnew, session('periodterm'));

        $data = [
            'period_id' => session('current_period'),
            'student_id' => $student['student']['id'],
            'program_id' => $student['student']['program_id'],
            'curriculum_id' => $student['student']['curriculum_id'],
            'year_level' => $year_level,
            'new' => $isnew ?? 0,
            'old' => $isOld ?? 0,
            'user_id' => Auth::user()->id
        ];

        return Enrollment::insert($data);

    }

}

