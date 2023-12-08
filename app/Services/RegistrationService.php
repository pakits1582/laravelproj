<?php

namespace App\Services;

use App\Services\Enrollment\EnrollmentService;

class RegistrationService
{
    public function studentRegistration($student)
    {
        $config_schedules = (new ConfigurationService)->configurationSchedule(session('current_period'),'student_registration');

        $data = [];

        $data['probi']   = 0;
        $data['balance']  = 0;
        $data['new'] = 1;
        $data['old'] = 0;

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
            $data['error'][] = 'You are academically on probationary status last term attended (period name). Please contact your Program Head for enrollment. Thank You!';
        }

        if(isset($data['hasbal']) && $data['hasbal'] == 1)
        {
            if($data['previousbalance'])
            {
                $data['error'][] = 'You have previous balance last term attended. ('.$data['previousbalance']['period'].' P '.$data['previousbalance']['balance'].'). You are advised to report in Accounting Office.';
            }
        }

        $data['registration_status'] = $this->checkRegistrationSchedules($student, $config_schedules, $data['year_level']);

        if($data['registration_status'] == 1)
        {

        }


        
        
    }

    public function checkRegistrationSchedules($student, $config_schedules, $year_level)
    {
        $is_open = 0;

        if($config_schedules->count() > 0)
        {
            foreach ($config_schedules as $config_schedule) 
            {
                $now = \Carbon\Carbon::now();
                $date_from = \Carbon\Carbon::parse($config_schedule->date_from);
                $date_to = \Carbon\Carbon::parse($config_schedule->date_to);

                if (($config_schedule->educational_level_id == 0 && $now->between($date_from, $date_to, true)) ||
                    ($config_schedule->educational_level_id == 0 && $now->isSameDay($date_from) && $now->isSameDay($date_to))) 
                {
                    $is_open = 1;
                    break;
                }else{
                    if(!is_null($config_schedule->year))
                    {
                        if (($student->program->level->id == $config_schedule->educational_level_id && $year_level == $config_schedule->year && $now->between($date_from, $date_to, true)) ||
                            ($student->program->level->id == $config_schedule->educational_level_id && $year_level == $config_schedule->year && $now->isSameDay($date_from) && $now->isSameDay($date_to))) 
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
        
    }
}