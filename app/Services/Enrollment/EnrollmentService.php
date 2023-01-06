<?php

namespace App\Services\Enrollment;

use Carbon\Carbon;
use App\Models\Term;
use App\Libs\Helpers;
use App\Models\Enrollment;
use App\Models\TaggedGrades;
use App\Models\EnrolledClass;
use App\Services\ClassesService;
use App\Models\SectionMonitoring;
use App\Services\CurriculumService;
use App\Services\TaggedGradeService;
use Illuminate\Support\Facades\Auth;
use App\Models\EnrolledClassSchedule;
use App\Services\Grade\InternalGradeService;
use App\Services\Evaluation\EvaluationService;

class EnrollmentService
{
    protected $internalGradeService;

    public function __construct()
    {
        $this->internalGradeService = new InternalGradeService();
    }

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
                        'message' => 'Your account does not have permission to enroll students with account balance!',
                        'alert' => 'alert-danger'
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Your account does not have permission to enroll students with account balance!',
                'alert' => 'alert-danger'
            ];
        }

        $isnew = 0;
        $isOld = 0;

        if($this->studentAllEnrollments($student['student']['id']) === false)
        {
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
        $section_subjects = (new ClassesService())->classSubjects($section_id, session('current_period'), false);

        if(!$section_subjects->isEmpty())
        {        
            return $this->handleClassSubjects($student_id, $section_subjects);
        }

        return [
            'success' => false,
            'message' => 'The selected section has no class offerings!',
            'alert' => 'alert-danger'
        ];
    }

    public function handleClassSubjects($student_id, $section_subjects)
    {
        $not_passed_section_subjects = [];
        $internal_grades = (new InternalGradeService())->getAllStudentPassedInternalGrades($student_id);
        $tagged_grades   = (new TaggedGradeService())->getAllTaggedGrades($student_id);
        
        //return $internal_grades;
        //CHECK SECTION SUBJECTS IF ALREADY PASSED
        foreach ($section_subjects as $key => $section_subject)
        {            
            $ispassed = 0;
            $grades = $internal_grades->where('subject_id', $section_subject->curriculumsubject->subject_id)->toArray();

            if($grades)
            {
                $grade_info = (new EvaluationService())->getMaxValueOfGrades($grades);
                $ispassed = ($grade_info['grade'] >= $section_subject->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $section_subject->curriculumsubject->quota) ? 1 : 0;
            }else{
                //CHECK EQUIVALENTS SUBJECTS IF PASSED
                if($section_subject->curriculumsubject->equivalents->count())
                {
                    $equivalent_subjects_internal_grades = [];
                    $equivalent_subjects_external_grades = [];
                    
                    foreach ($section_subject->curriculumsubject->equivalents as $key => $equivalent_subject)
                    {
                        //GET ALL INTERNAL GRADES OF EQUIVALENT SUBJECTS
                        $equivalent_subjects_internal_grades += $internal_grades->where('subject_id', $equivalent_subject['equivalent'])->toArray();
                        //GET ALL EXTERNAL GRADES OF EQUIVALENT SUBJECTS
                        $equivalent_subjects_external_grades += $tagged_grades->where('subject_id', $equivalent_subject['equivalent'])->toArray();
                    }

                    if($equivalent_subjects_internal_grades)
                    {
                        $grade_info = (new EvaluationService())->getMaxValueOfGrades($equivalent_subjects_internal_grades);
                        $ispassed = ($grade_info['grade'] >= $section_subject->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $section_subject->curriculumsubject->quota) ? 1 : 0;
                    }

                    if($ispassed === 0)
                    {
                        if($equivalent_subjects_external_grades)
                        {
                            $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($equivalent_subjects_external_grades);
                            $ispassed = ($grade_info['grade'] >= $section_subject->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $section_subject->curriculumsubject->quota) ? 1 : 0;
                        }
                    }
                }//end of foreach equivalents
            }//end of if passed internal
            //CHECK FROM TAGGED GRADES
            if($ispassed === 0)
            {
                $curriculum_subject_tagged_grades = $tagged_grades->where('subject_id', $section_subject->curriculumsubject->subject_id)->toArray();

                if($curriculum_subject_tagged_grades)
                {
                    $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($curriculum_subject_tagged_grades);
                    $ispassed = ($grade_info['grade'] >= $section_subject->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $section_subject->curriculumsubject->quota) ? 1 : 0;
                }//end of if has tagged subject
            }

            if($ispassed === 0)
            {
                $not_passed_section_subjects[] = $section_subject;
            }
        }//end of foreach section subjects

        $final_section_subjects = [];

        foreach ($not_passed_section_subjects as $key => $not_passed_section_subject) 
        {
            $not_passed_section_subject['total_slots'] = ($not_passed_section_subject->merge > 0) ? $not_passed_section_subject->mergetomotherclass->slots : $not_passed_section_subject->slots;
            $not_passed_section_subject['total_slots_taken'] = $this->getTotalSlotsTakenOfClass($not_passed_section_subject); 
            $not_passed_section_subject['unfinished_prerequisites'] = $this->checkIfSectionSubjectPrerequisitesPassed(
                                                                        $not_passed_section_subject->curriculumsubject->prerequisites,
                                                                        $internal_grades,
                                                                        $tagged_grades
                                                                    );

            $final_section_subjects[] = $not_passed_section_subject;
        }

        return $final_section_subjects;
    }

    public function checkIfSectionSubjectPrerequisitesPassed($prerequisites, $internal_grades, $tagged_grades)
    {
        $failed_prerequisites = [];

        if($prerequisites->count())
        {
            foreach ($prerequisites as $key => $prerequisite) {
                $prerequisite_info = (new CurriculumService())->returnCurriculumSubjectInfo($prerequisite->prerequisite);
                $subject_id = $prerequisite_info->subjectinfo->id;
                $ispassed = 0;

                $grades = $internal_grades->where('subject_id', $subject_id)->toArray();
                if($grades)
                {
                    $grade_info = (new EvaluationService())->getMaxValueOfGrades($grades);
                    $ispassed = ($grade_info['grade'] >= $prerequisite_info->quota || !is_null($grade_info['completion_grade']) >= $prerequisite_info->quota) ? 1 : 0;   
                }else{
                    //CHECK EQUIVALENTS SUBJECTS IF PASSED
                    if($prerequisite_info->equivalents->count())
                    {
                        $equivalent_subjects_internal_grades = [];
                        $equivalent_subjects_external_grades = [];
                        
                        foreach ($prerequisite_info->equivalents as $key => $equivalent_subject)
                        {
                            //GET ALL INTERNAL GRADES OF EQUIVALENT SUBJECTS
                            $equivalent_subjects_internal_grades += $internal_grades->where('subject_id', $equivalent_subject['equivalent'])->toArray();
                            //GET ALL EXTERNAL GRADES OF EQUIVALENT SUBJECTS
                            $equivalent_subjects_external_grades += $tagged_grades->where('subject_id', $equivalent_subject['equivalent'])->toArray();
                        }
    
                        if($equivalent_subjects_internal_grades)
                        {
                            $grade_info = (new EvaluationService())->getMaxValueOfGrades($equivalent_subjects_internal_grades);
                            $ispassed = ($grade_info['grade'] >= $prerequisite_info->quota || !is_null($grade_info['completion_grade']) >= $prerequisite_info->quota) ? 1 : 0;
                        }
    
                        if($ispassed === 0)
                        {
                            if($equivalent_subjects_external_grades)
                            {
                                $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($equivalent_subjects_external_grades);
                                $ispassed = ($grade_info['grade'] >= $prerequisite_info->quota || !is_null($grade_info['completion_grade']) >= $prerequisite_info->quota) ? 1 : 0;
                            }
                        }
                    }//end of foreach equivalents
                }//end of if passed internal
                //CHECK FROM TAGGED GRADES
                if($ispassed === 0)
                {
                    $curriculum_subject_tagged_grades = $tagged_grades->where('curriculum_subject_id', $prerequisite->prerequisite)->toArray();

                    if($curriculum_subject_tagged_grades)
                    {
                        $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($curriculum_subject_tagged_grades);
                        $ispassed = ($grade_info['grade'] >= $prerequisite_info->quota || !is_null($grade_info['completion_grade']) >= $prerequisite_info->quota) ? 1 : 0;
                    }//end of if has tagged subject
                }

                if($ispassed === 0)
                {
                    $failed_prerequisites[] = $prerequisite_info;
                }
            }
        }

        return $failed_prerequisites;
    }

    public function getTotalSlotsTakenOfClass($class)
    {
        if($class->ismother === 1)
        {
            return $this->getEnrolledMergedChildren($class->id)->count()+$class->enrolledstudents->count();
        }else{
            if($class->merge > 0)
            {
                $enrolled_in_mother = $this->getEnrolledInCLass($class->merge)->count();
                $enrolled_merged = $this->getEnrolledMergedChildren($class->merge)->count();

                return $enrolled_in_mother+$enrolled_merged;
            }

            return $class->enrolledstudents->count();
        }    
    }

    public function getEnrolledMergedChildren($mother_class)
    {
        $enrolled_children = EnrolledClass::join('enrollments', 'enrolled_classes.enrollment_id', '=', 'enrollments.id')
            ->join('classes', 'enrolled_classes.class_id', '=', 'classes.id')
            ->where('classes.merge', $mother_class)->get();

        return $enrolled_children;
    }
   
    public function getEnrolledInCLass($class_id)
    {
        $enrolled = EnrolledClass::join('enrollments', 'enrolled_classes.enrollment_id', '=', 'enrollments.id')
            ->join('classes', 'enrolled_classes.class_id', '=', 'classes.id')
            ->where('classes.id', $class_id)->get();

        return $enrolled;
        
    }

    public function checkClassesIfConflictStudentSchedule($enrollment_id, $subjects)
    {
        $enrollment_schedules = EnrolledClassSchedule::with(['class'])->where('enrollment_id', $enrollment_id)->get();

        $checked_subjects = [];

        if($subjects)
        {
            foreach ($subjects as $key => $subject)
            {
                $conflict = 0;
                $schedule = $subject->schedule->schedule;
                
                if($schedule !== '')
                {
                    $schedule = strtoupper(preg_replace('/\s+/', ' ', trim($schedule)));
                    $schedules = explode(", ", $schedule);

                    foreach($schedules as $sched)
                    {
                        $splits = preg_split('/ ([MTWHFSU]+) /', $sched, -1, PREG_SPLIT_DELIM_CAPTURE);

                        $times = $splits[0];
                        $days  = $splits[1];
                        $room  = $splits[2];

                        $times    = explode("-", $times);
                        $timefrom = Carbon::parse($times[0])->format('H:i:s');
                        $timeto   = Carbon::parse($times[1])->format('H:i:s');

                        $splitdays = preg_split('/(.[HU]?)/' ,$days, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

                        $confli = 0;
                        foreach($splitdays as $splitday)
                        {
                            $hasconflict = 0;
                            foreach ($enrollment_schedules as $k => $v)
                            {
                                if($v->to_time > $timefrom && $timeto > $v->from_time && $splitday == $v->day){
                                    $hasconflict = 1;
                                    break;
                                }
                            }
                            if($hasconflict == 1){
                                $confli = 1;
                                break;
                            }
                        }

                        if($confli == 1){
                            $conflict = 1;
                        }
                    }
                }
                $checked_subjects[$key] = $subject;
                $checked_subjects[$key]['conflict'] = $conflict;
            }
        }

        return $checked_subjects;
    }
}

