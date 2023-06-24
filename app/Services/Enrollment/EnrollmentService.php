<?php

namespace App\Services\Enrollment;

use Carbon\Carbon;
use App\Models\Term;
use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\Assessment;
use App\Models\Enrollment;
use App\Models\TaggedGrades;
use App\Models\EnrolledClass;
use App\Services\UserService;
use App\Services\ClassesService;
use App\Models\SectionMonitoring;
use Illuminate\Support\Facades\DB;
use App\Services\CurriculumService;
use App\Services\TaggedGradeService;
use Illuminate\Support\Facades\Auth;
use App\Models\EnrolledClassSchedule;
use App\Services\Grade\ExternalGradeService;
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
        $user_programs = (new UserService())->handleUserPrograms(Auth::user());

        if($user_programs->contains('id', $studentinfo['program_id']) || $user_programs->contains('program.id', $studentinfo['program_id']))
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

        return [
            'success' => false,
            'message' => 'Your account does not have permission to enroll student\'s current program!',
            'alert' => 'alert-danger'
        ];
    }

    public function studentEnrollment($student_id, $period_id, $acctok = 0)
    {
        $period_id = ($period_id == NULL) ? session('current_period') : $period_id;
        
        $query = Enrollment::with(['period', 'student', 'program' => ['level', 'collegeinfo'], 'curriculum', 'section', 'assessment' => ['exam', 'breakdowns', 'details']])
                    ->where('student_id', $student_id)
                    ->where('period_id', $period_id);
            if($acctok == 1)
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

            if (!$user_permissions || !Helpers::is_column_in_array('can_withbalance', 'permission', $user_permissions->toArray())) {
                return [
                    'success' => false,
                    'message' => 'Your account does not have permission to enroll students with account balance!',
                    'alert' => 'alert-danger'
                ];
            }

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
            'probationary' => ($student['probi'] === true) ? 1 : 0,
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
        $query = Classes::with([
            'sectioninfo',
            'instructor', 
            'schedule',
            'enrolledstudents' => function($query)
            {
                $query->with('enrollment')->withCount('enrollment');
            },
            'merged' => function($query)
            {
                $query->withCount('enrolledstudents');
            },
            'mergetomotherclass' => [
                'enrolledstudents' => function($query)
                {
                    $query->withCount('enrollment');
                },
                'merged' => function($query)
                {
                    $query->withCount('enrolledstudents');
                },
            ],
            'curriculumsubject' => [
                'subjectinfo', 
                'curriculum',
                'prerequisites' => ['curriculumsubject.subjectinfo','curriculumsubject.equivalents',], 
                'corequisites', 
                'equivalents'
            ]
        ])->where('section_id', $section_id)->where('period_id', session('current_period'))->where('dissolved', '!=', 1);

        $section_subjects = $query->get();

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
        $external_grades = (new ExternalGradeService())->getAllStudentPassedExternalGrades($student_id);
        $tagged_grades   = (new TaggedGradeService())->getAllTaggedGrades($student_id);

        //return $section_subjects;
        //CHECK SECTION SUBJECTS IF ALREADY PASSED
        foreach ($section_subjects as $key => $section_subject)
        {        
            //return $section_subject->merged->sum('enrolledstudents_count');    
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
                        $ispassed = ($grade_info && ($grade_info['grade'] >= $section_subject->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $section_subject->curriculumsubject->quota)) ? 1 : 0;
                    }

                    if($ispassed === 0)
                    {
                        if($equivalent_subjects_external_grades)
                        {
                            $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($equivalent_subjects_external_grades, $internal_grades, $external_grades);
                            $ispassed = ($grade_info && ($grade_info['grade'] >= $section_subject->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $section_subject->curriculumsubject->quota)) ? 1 : 0;
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
                    $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($curriculum_subject_tagged_grades, $internal_grades, $external_grades);
                    $ispassed = ($grade_info && ($grade_info['grade'] >= $section_subject->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $section_subject->curriculumsubject->quota)) ? 1 : 0;
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
                                                                        $external_grades,
                                                                        $tagged_grades
                                                                    );

            $final_section_subjects[] = $not_passed_section_subject;
        }

        return $final_section_subjects;
    }

    public function checkIfSectionSubjectPrerequisitesPassed($prerequisites, $internal_grades, $external_grades, $tagged_grades)
    {
        $failed_prerequisites = [];

        if($prerequisites->count())
        {
            foreach ($prerequisites as $key => $prerequisite)
            {
                // Check if there are any failed prerequisites
                if (!empty($failed_prerequisites)) {
                    break;
                }
                
                $subject_id = $prerequisite->curriculumsubject->subject_id;
                $ispassed = 0;

                $grades = $internal_grades->where('subject_id', $subject_id)->toArray();
                if($grades)
                {
                    $grade_info = (new EvaluationService())->getMaxValueOfGrades($grades);
                    $ispassed = ($grade_info && ($grade_info['grade'] >= $prerequisite->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $prerequisite->curriculumsubject->quota)) ? 1 : 0;   
                }else{
                    //CHECK EQUIVALENTS SUBJECTS IF PASSED
                    if($prerequisite->curriculumsubject->equivalents->count())
                    {
                        $equivalent_subjects_internal_grades = [];
                        $equivalent_subjects_external_grades = [];
                        
                        foreach ($prerequisite->curriculumsubject->equivalents as $key => $equivalent_subject)
                        {
                            //GET ALL INTERNAL GRADES OF EQUIVALENT SUBJECTS
                            $equivalent_subjects_internal_grades += $internal_grades->where('subject_id', $equivalent_subject['equivalent'])->toArray();
                            //GET ALL EXTERNAL GRADES OF EQUIVALENT SUBJECTS
                            $equivalent_subjects_external_grades += $tagged_grades->where('subject_id', $equivalent_subject['equivalent'])->toArray();
                        }
    
                        if($equivalent_subjects_internal_grades)
                        {
                            $grade_info = (new EvaluationService())->getMaxValueOfGrades($equivalent_subjects_internal_grades);
                            $ispassed = ($grade_info && ($grade_info['grade'] >= $prerequisite->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $prerequisite->curriculumsubject->quota)) ? 1 : 0;
                        }
    
                        if($ispassed === 0)
                        {
                            if($equivalent_subjects_external_grades)
                            {
                                $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($equivalent_subjects_external_grades, $internal_grades, $external_grades);
                                $ispassed = ($grade_info && ($grade_info['grade'] >= $prerequisite->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $prerequisite->curriculumsubject->quota)) ? 1 : 0;
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
                        $grade_info = (new TaggedGradeService())->checkTaggedGradeInfo($curriculum_subject_tagged_grades, $internal_grades, $external_grades);
                        $ispassed =($grade_info && ($grade_info['grade'] >= $prerequisite->curriculumsubject->quota || !is_null($grade_info['completion_grade']) >= $prerequisite->curriculumsubject->quota)) ? 1 : 0;
                    }//end of if has tagged subject
                }

                if($ispassed === 0)
                {
                    $failed_prerequisites[] = $prerequisite;
                }
            }
        }

        return $failed_prerequisites;
    }

    public function getTotalSlotsTakenOfClass($class)
    {
        $total_enrolled_in_class = 0;
        if($class->ismother === 1)
        {  
            $total_enrolled_in_class = $class->enrolledstudents->sum('enrollment_count') + $class->merged->sum('enrolledstudents_count');
        }else{
            if(!is_null($class->merge))
            {
                $total_enrolled_in_class = $class->mergetomotherclass->enrolledstudents->sum('enrollment_count') + $class->mergetomotherclass->merged->sum('enrolledstudents_count');
            }else{
                $total_enrolled_in_class = $class->enrolledstudents->count();
            }

        }   
        
        return $total_enrolled_in_class;
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

    public function enrollClassSubjects($request)
    {     
        DB::beginTransaction();

        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        $enrollment->update(['section_id' => $request->section_id]);
        $enrollment->enrolled_classes()->delete();
        $enrollment->enrolled_class_schedules()->delete();

        $enroll_classes = [];
        $enroll_class_schedules = [];

        foreach ($request->class_subjects as $key => $class_subject) {
            //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
            $enroll_classes[] = [
                'enrollment_id' => $request->enrollment_id,
                'class_id' => $class_subject['id'],
                'user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            if(!is_null($class_subject['schedule']))
            {
                $class_schedules = (new ClassesService())->processSchedule($class_subject['schedule']);

                foreach ($class_schedules as $key => $class_schedule) 
                {
                    foreach ($class_schedule['days'] as $key => $day) {
                        $enroll_class_schedules[] = [
                            'enrollment_id' => $request->enrollment_id,
                            'class_id' => $class_subject['id'],
                            'from_time' => $class_schedule['timefrom'],
                            'to_time' => $class_schedule['timeto'],
                            'day' => $day,
                            'room' => $class_schedule['room'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
            }
        }

        $enrollment->enrolled_classes()->insert($enroll_classes);
        $enrollment->enrolled_class_schedules()->insert($enroll_class_schedules);

        DB::commit();

        return true;
    }

    public function enrolledClassSubjects($enrollment_id)
    {
        $enrolled_classes = EnrolledClass::with([
            'class' => [
                'sectioninfo',
                'instructor', 
                'schedule',
                'curriculumsubject' => ['subjectinfo']
            ],
            'addedby'
        ])->where('enrollment_id', $enrollment_id)->get();

        return $enrolled_classes;
    }

    public function searchClassSubject($request)
    {
        $enrollment_id = $request->enrollment_id;
        $student_id =  $request->student_id;
        $searchcodes = stripslashes($request->searchcodes);

        $searchcodes = array_unique(preg_split('/(\s*,*\s*)*,+(\s*,*\s*)*/', $searchcodes));
        $searchcodes= array_map('trim', $searchcodes);

        $query = Classes::with([
            'sectioninfo',
            'instructor', 
            'schedule',
            'enrolledstudents' => function($query)
            {
                $query->with('enrollment')->withCount('enrollment');
            },
            'merged' => function($query)
            {
                $query->withCount('enrolledstudents');
            },
            'mergetomotherclass' => [
                'enrolledstudents' => function($query)
                {
                    $query->withCount('enrollment');
                },
                'merged' => function($query)
                {
                    $query->withCount('enrolledstudents');
                },
            ],
            'curriculumsubject' => [
                'subjectinfo', 
                'curriculum',
                'prerequisites' => ['curriculumsubject.subjectinfo','curriculumsubject.equivalents',], 
                'corequisites', 
                'equivalents'
            ]
        ])->where('period_id', session('current_period'))->where('dissolved', '!=', 1);

        $query->where(function($query) use($searchcodes){
            foreach($searchcodes as $key => $code){
                $query->orwhere(function($query) use($code){
                    $query->orWhere('code', 'LIKE', '%'.$code.'%');
                    $query->orwhereHas('curriculumsubject.subjectinfo', function($query) use($code){
                        $query->where('subjects.code', 'LIKE', '%'.$code.'%');
                    });
                });
            }
        });

        $section_subjects =  $query->get()->sortBy('curriculumsubject.subjectinfo.code');
        $subjects = $this->handleClassSubjects($student_id, $section_subjects);
        $checked_subjects = $this->checkClassesIfConflictStudentSchedule($enrollment_id, $subjects);

        return $checked_subjects;
    }

    public function searchClassSubjectBySection($request)
    {
        $enrollment_id = $request->enrollment_id;
        $student_id =  $request->student_id;
        $section_id = $request->section_id;

        $query = Classes::with([
            'sectioninfo',
            'instructor', 
            'schedule',
            'enrolledstudents' => function($query)
            {
                $query->with('enrollment')->withCount('enrollment');
            },
            'merged' => function($query)
            {
                $query->withCount('enrolledstudents');
            },
            'mergetomotherclass' => [
                'enrolledstudents' => function($query)
                {
                    $query->withCount('enrollment');
                },
                'merged' => function($query)
                {
                    $query->withCount('enrolledstudents');
                },
            ],
            'curriculumsubject' => [
                'subjectinfo', 
                'curriculum',
                'prerequisites' => ['curriculumsubject.subjectinfo','curriculumsubject.equivalents'], 
                'corequisites', 
                'equivalents'
            ]
        ])->where('period_id', session('current_period'))->where('dissolved', '!=', 1)->where('section_id', $section_id);

        $section_subjects =  $query->get()->sortBy('curriculumsubject.subjectinfo.code');
        $subjects = $this->handleClassSubjects($student_id, $section_subjects);
        $checked_subjects = $this->checkClassesIfConflictStudentSchedule($enrollment_id, $subjects);

        return $checked_subjects;
    }

    public function addSelectedClasses($request)
    {
        $class_subjects = Classes::with(['schedule'])->whereIn("id", $request->class_ids)->get();

        DB::beginTransaction();

        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        $enroll_classes = [];
        $enroll_class_schedules = [];

        foreach ($class_subjects as $key => $class_subject)
        {
            $enroll_classes[] = [
                'enrollment_id' => $request->enrollment_id,
                'class_id' => $class_subject['id'],
                'user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            if(!is_null($class_subject['schedule']['schedule']))
            {
                $class_schedules = (new ClassesService())->processSchedule($class_subject['schedule']['schedule']);

                foreach ($class_schedules as $key => $class_schedule) 
                {
                    foreach ($class_schedule['days'] as $key => $day) {
                        $enroll_class_schedules[] = [
                            'enrollment_id' => $request->enrollment_id,
                            'class_id' => $class_subject['id'],
                            'from_time' => $class_schedule['timefrom'],
                            'to_time' => $class_schedule['timeto'],
                            'day' => $day,
                            'room' => $class_schedule['room'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
            }
        }

        $enrollment->enrolled_classes()->insert($enroll_classes);
        $enrollment->enrolled_class_schedules()->insert($enroll_class_schedules);

        DB::commit();

        return [
            'success' => true,
            'message' => 'Selected class subject/s successfully added!',
            'alert' => 'alert-success',
            'status' => 200
        ];
    }

    public function deleteSelectedSubjects($request)
    {
        DB::beginTransaction();

        $selectedclasses = EnrolledClass::where('enrollment_id', $request->enrollment_id)->whereIn('class_id', $request->class_ids)->delete();
        $selectedclassesscheds = EnrolledClassSchedule::where('enrollment_id', $request->enrollment_id)->whereIn('class_id', $request->class_ids)->delete();

        DB::commit();
        
        return [
            'success' => true,
            'message' => 'Selected class subject/s successfully deleted!',
            'alert' => 'alert-success',
            'status' => 200
        ];
    }

    public function saveEnrollment($request, $enrollment)
    {
        DB::beginTransaction();

        $enrollment->student()->update([
            'program_id' => $request->program_id,
            'year_level' => $request->year_level,
            'curriculum_id' => $request->curriculum_id,
        ]);

        $enrollment->update($request->validated()+['acctok' => 1, 'user_id' => Auth::user()->id]);

        $assessment = Assessment::firstOrCreate([
            'enrollment_id' => $enrollment->id,
            'period_id' => session('current_period'),
        ], [
            'enrollment_id' => $enrollment->id,
            'period_id' => session('current_period'),
            'user_id' => Auth::id()
        ]);

        DB::commit();

        return [
            'success' => true,
            'message' => 'Enrollment successfully saved!',
            'alert' => 'alert-success',
            'status' => 200,
            'assessment_id' => $assessment->id
        ];
    }

    public function filterEnrolledStudents($period_id, $program_id = NULL, $year_level = NULL, $class_id = NULL, $idno = NULL, $enrollment_ids = NULL)
    {
        $query = Enrollment::with([
            'student' => function($query) {
                $query->select('id', 'last_name', 'first_name', 'middle_name', 'name_suffix', 'user_id');
            },
            'student.user' => function($query) {
                $query->select('id', 'idno');
            },
            'program:id,code,educational_level_id',
            'assessment' => [
                'breakdowns' => ['fee_type'], 
                'exam'
            ],
            'studentledger_assessment'
        ])->where('period_id', $period_id)->where('validated', 1);
        

        $query->when(isset($program_id) && !empty($program_id), function ($query) use($program_id) {
            $query->where('program_id', $program_id);
        });

        $query->when(isset($year_level) && !empty($year_level), function ($query) use($year_level) {
            $query->where('year_level', $year_level);
        });

        $query->when(isset($class_id) && !empty($class_id), function ($query) use($class_id) {
            $query->whereHas('enrolled_classes', function($query) use($class_id){
                $query->where('enrolled_classes.class_id', $class_id);
            });
        });

        $query->when(isset($idno) && !empty($idno), function ($query) use($idno) {
            $query->whereHas('student.user', function($query) use($idno){
                $query->where('users.idno', $idno);
            });
        });

        $query->when(isset($enrollment_ids) && !empty($enrollment_ids), function ($query) use($enrollment_ids) {
            $query->whereIn('id', $enrollment_ids);
        });

        return $query->get();
    }
}

