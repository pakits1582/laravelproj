<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\SectionMonitoring;
use App\Services\Assessment\AssessmentService;
use Illuminate\Support\Facades\Auth;
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

            $data['section_subjects'] = (new EnrollmentService())->enrollSection($student->id, $data['section']->section_id, $enrollment->id);
            $data['enrolled_classes'] = (new EnrollmentService())->enrolledClassSubjects($enrollment->id);
            $data['class_schedules']  = (new AssessmentService())->enrolledClassSchedules($enrollment->id);
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
}