<?php

namespace App\Services\Enrollment;

use App\Models\Term;
use App\Libs\Helpers;
use App\Models\Enrollment;
use App\Models\SectionMonitoring;
use App\Services\ClassesService;
use App\Services\CurriculumService;
use App\Services\Grade\InternalGradeService;
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

        $data['hasbal'] = 1;
        $data['previous_balance'] = ['period' => 'First Semester, 2021-2022', 'balance' => 5000];

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
        
        return ($isProbi == 1) ? 18 : $allowed_units;

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

    public function countEnrolledinsSection($section_id, $period_id)
    {
        $enrolledin_section = Enrollment::where('section_id', $section_id)->where('period_id', $period_id)->count();

        return $enrolledin_section;
    }

    public function insertStudentEnrollment($studentinfo)
    {
        $student = $studentinfo['data'];

        if($student['balance'])
        {
            $user_permissions = Auth::user()->permissions;
            
            if($user_permissions)
            {
                if(!Helpers::is_column_in_array('can_withbalance', 'permission', $user_permissions->toArray())){
                    return [
                        'success' => false,
                        'message' => 'Your account does not have permission to enroll students with an account balance!',
                        'alert' => 'alert-danger'
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Your account does not have permission to enroll students with an account balance!',
                'alert' => 'alert-danger'
            ];
        }

        if($this->studentAllEnrollments($student['student']['id']) === false){
            $isnew = 1;
        }else{
            $isOld = 1;
        }

        $year_level = $this->studentYearLevel($student['student']['year_level'], $student['student']['program']['years'], $student['probi'], $isnew, session('periodterm'));

        $data = [
            'period_id' => session('current_period'),
            'student_id' => $student['student']['id'],
            'program_id' => $student['student']['program_id'],
            'curriculum_id' => $student['student']['curriculum_id'],
            'year_level' => $year_level,
            'new' => $isnew ?? 0,
            'old' => $isOld ?? 0,
            'probationary' => $student['probi'],
            'user_id' => Auth::user()->id
        ];

        $enrollment = Enrollment::firstOrCreate(['period_id' => session('current_period'), 'student_id' => $student['student']['id']], $data);
        $data['id'] = $enrollment->id;
        $studentinfo['success'] = true;
        $studentinfo['data']['enrollment'] = $data;

        return $studentinfo;
        
    }

    public function checkSectionSlot($section_id)
    {
        $section_monitoring = SectionMonitoring::where('section_id', $section_id)->where('period_id', session('current_period'))->first();

        if($section_monitoring)
        {
            $enrolledin_section = $this->countEnrolledinsSection($section_id, session('current_period'));

            if($enrolledin_section >= $section_monitoring->minimum_enrollees)
            {
                return [
                    'success' => false,
                    'message' => 'The section selected is already full! Please check section monitoring!',
                    'alert' => 'alert-danger'
                ];
            }

            return $section_monitoring;
        }

        return [
            'success' => false,
            'message' => 'The selected section has no class offerings!',
            'alert' => 'alert-danger'
        ];

    }

    public function enrollSection($student_id, $section_id, $enrollment_id)
    {
        $classesService = new ClassesService();

        $section_subjects = $classesService->classSubjects($section_id, session('current_period'));

        if(!$section_subjects->isEmpty())
        {           
            return $this->handleSectionSubjects($student_id, $section_subjects);
        }

        return [
            'success' => false,
            'message' => 'The selected section has no class offerings!',
            'alert' => 'alert-danger'
        ];
    }

    public function handleSectionSubjects($student_id, $section_subjects)
    {
        $internalGradeService = new InternalGradeService();
        $subjects = [];
        
        $ispassed = 0;
        foreach ($section_subjects as $key => $section_subject) {
            $subject_id = $section_subject['curriculumsubject']['subject_id'];

            $isgrade_passed = $internalGradeService->checkIfPassedInternalGrade($student_id, $subject_id);
            
            if($isgrade_passed)
            {
                $ispassed = $internalGradeService->checkIfPassedQuotaGrade(
                    $section_subject['curriculumsubject']['quota'] ?? false, 
                    $isgrade_passed->grade, 
                    $isgrade_passed->completion_grade
                );

            }else{
                $ispassed = 0;
            }

            $subjects[] = $ispassed;
        }

        return $subjects;
    }

}

