<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\SectionMonitoring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Assessment\AssessmentService;
use App\Services\Enrollment\EnrollmentService;

class RegistrationService
{
    public function studentRegistration($student)
    {
        $config_schedules = (new ConfigurationService)->configurationSchedule(session('current_period'),'student_registration');

        $data = [
            'probi' => 0,
            'balance' => 0,
            'new' => 1,
            'old' => 0,
        ];

        $last_enrollment = (new EnrollmentService)->studentLastEnrollment($student->id);
        
        if($last_enrollment !== false)
        {
            $data['probi'] = (new EnrollmentService)->checkIfStudentIsOnProbation($student->id, '$last_enrollment->period_id');
            $data['balance'] = (new EnrollmentService)->checkIfstudentHasAccountBalance($student->id, '$last_enrollment->period_id');
            
            $data['new'] = 0;
            $data['old'] = 1;
        }
        
        $data['year_level'] = (new EnrollmentService)->studentYearLevel($student->year_level, $student->program->years, $data['probi'], $data['new'], session('periodterm'));
        $data['allowed_units'] = (new EnrollmentService)->studentEnrollmentUnitsAllowed($student->curriculum->id, session('periodterm'), $data['year_level'], $data['probi']);
        
        $data['errors'] = [];

        if($data['probi'] == 1)
        {
            $data['errors'][] = 'You are academically on probationary status last term attended (period name). Please contact your Program Head for enrollment. Thank You!';
        }

        if(isset($data['balance']['hasbal']) && $data['balance']['hasbal'] == 1)
        {
            if($data['balance']['previous_balance'])
            {
                $data['errors'][] = 'You have previous balance last term attended. ('.$data['balance']['previous_balance']['period'].' P '.$data['balance']['previous_balance']['balance'].'). You are advised to report in Accounting Office.';
            }
        }

        $data['registration_status'] = $this->checkRegistrationSchedules($student, $config_schedules, $data['year_level']);

        if($data['registration_status'] == 0)
        {
            $data['errors'][] = 'Registration is closed.';
        }

        $available_sections = $this->checkAvailableSection($student, $data['year_level']);
        if($available_sections)
        {
            $data['sections'] = $available_sections['open_sections'];
            $data['section'] = $available_sections['section'];
        }else{
            $data['errors'][] = 'No open or available section offerings for your program and year level. Please contact program head for your enrolment.';
        }

        if(empty($data['errors']))
        {
            $insert_enrollment = [
                'period_id' => session('current_period'),
                'student_id' => $student->id,
                'program_id' => $student->program_id,
                'curriculum_id' => $student->curriculum_id,
                'section_id' => $data['section']->section_id,
                'year_level' => $data['year_level'],
                'new' => $data['new'],
                'old' => $data['old'],
                'probationary' => $data['probi'],
                'user_id' => Auth::user()->id
            ];
    
            $enrollment = Enrollment::firstOrCreate(['period_id' => session('current_period'), 'student_id' => $student->id], $insert_enrollment);
            // Load relationships for the enrollment
            $enrollment->load([
                'program.level:id,code,level',
                'program.collegeinfo:id,code,name',
                'curriculum:id,program_id,curriculum',
                'section:id,code,name',
            ]);
            
            $data['enrollment'] = $enrollment;            
        }

        return $data;
    }

    public function checkRegistrationSchedules($student, $config_schedules, $year_level)
    {
        $is_open = 0;

        if($config_schedules->count() > 0)
        {
            foreach ($config_schedules as $config_schedule) 
            {
                $now = \Carbon\Carbon::now()->startOfDay()->format('Y-m-d');
                $date_from = \Carbon\Carbon::parse($config_schedule->date_from)->startOfDay()->format('Y-m-d');
                $date_to = \Carbon\Carbon::parse($config_schedule->date_to)->startOfDay()->format('Y-m-d');
                

                if ($config_schedule->educational_level_id == 0 && ($now >= $date_from && $now <= $date_to)) 
                {
                    $is_open = 1;
                    break;
                }else{
                    if(!is_null($config_schedule->year))
                    {
                        if (($student->program->level->id == $config_schedule->educational_level_id && $year_level == $config_schedule->year) && ($now >= $date_from && $now <= $date_to)) 
                        {
                            $is_open = 1;
                            break;
                        }
                    }
                }                
            }
        }

        return $is_open;
    }

    public function checkAvailableSection($student, $year_level)
    {
        $program_id = $student->program_id;
        $period_id = session('current_period');

        $section_monitorings = (new SectionMonitoringService)->sectionmonitoring($period_id, $program_id, $year_level);

        $open_sections = $section_monitorings->where('status', SectionMonitoring::STATUS_OPEN);
        $close_sections = $section_monitorings->where('status', SectionMonitoring::STATUS_CLOSE);

        if($open_sections->isNotEmpty()) 
        {
            $last_open_section = $open_sections->last();

            if($last_open_section->minimum_enrollees == $last_open_section->enrolled_count)
            {
                //CHECK IF THERE ARE CLOSE SECTIONS
                if($close_sections->isNotEmpty()) 
                {
                    //OPEN THE NEXT CLOSE SECTION
                    $next_close_section = $close_sections->first(); // Get the first close section

                    // Update the status of the next close section to open (adjust this according to your needs)
                    $next_close_section->update(['status' => SectionMonitoring::STATUS_OPEN]);
                    
                    return ['open_sections' => $open_sections, 'section' => $next_close_section];
                }else{
                    return false;
                }
            }

            return ['open_sections' => $open_sections, 'section' => $last_open_section];
        }else{
            // No open sections available
            return false;
        }
    }

    public function checkClassesIfConflictStudentSchedule($enrollment_schedules, $subjects)
    {
        //$enrollment_schedules = EnrolledClassSchedule::with(['class'])->where('enrollment_id', $enrollment_id)->get();

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


    public function checkIfClassIfDuplicate($enrolled_classes, $section_subjects)
    {
        $checked_duplicates = [];

        if($section_subjects)
        {
            foreach ($section_subjects as $key => $section_subject) 
            {  
                $checked_duplicates[$key] = $section_subject;
                $class_id = $section_subject['id'];
                $exists = $enrolled_classes->contains('class.id', $class_id);

                $checked_duplicates[$key]['duplicate'] = ($exists) ? 1 : 0;
              
            }
        }

        return $checked_duplicates;
    }

    public function saveSelectedClasses($request)
    {
        $selected_classes =  Classes::with([
            'curriculumsubject.subjectinfo',
            'schedule',
            'enrolledstudents' => function($query)
            {
                $query->withCount('enrollment');
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
            ]
        ])->whereIn("id", $request->class_ids)->get();

        if($selected_classes->isNotEmpty())
        {
            $to_insert_classes = [];
            $full_slots = [];

            foreach ($selected_classes as $key => $selected_class) 
            {
                $total_slots = ($selected_class->merge > 0) ? $selected_class->mergetomotherclass->slots : $selected_class->slots;
                $total_slots_taken = (new EnrollmentService)->getTotalSlotsTakenOfClass($selected_class); 

                if($total_slots_taken >= $total_slots)
                {
                    $full_slots[] = ['code' => $selected_class->code, 'subject_code' => $selected_class->curriculumsubject->subjectinfo->code];
                }else{
                    $to_insert_classes[] = $selected_class;
                }
            }

            if (!empty($to_insert_classes)) 
            {
                DB::beginTransaction();

                $enrollment = Enrollment::findOrFail($request->enrollment_id);

                $enroll_classes = [];
                $enroll_class_schedules = [];

                foreach ($to_insert_classes as $key => $class_subject)
                {
                    $enroll_classes[] = [
                        'enrollment_id' => $request->enrollment_id,
                        'class_id' => $class_subject->id,
                        'user_id' => Auth::id(),
                        'created_at' => carbon::now(),
                        'updated_at' => carbon::now()
                    ];
                    
                    if(!is_null($class_subject->schedule->schedule))
                    {
                        $class_schedules = (new ClassesService())->processSchedule($class_subject->schedule->schedule);

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
                                    'created_at' => carbon::now(),
                                    'updated_at' => carbon::now()
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
                    'message' => 'Selected class subjects successfully added!',
                    'alert' => 'alert-success',
                    'full_slots' => $full_slots,
                    'status' => 200
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Something went wrong! Can not add selected classes.',
            'alert' => 'alert-danger',
            'status' => 200
        ];

    }
}