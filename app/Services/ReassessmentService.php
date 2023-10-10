<?php

namespace App\Services;

use App\Models\FeeSetup;
use App\Models\Enrollment;
use App\Models\PaymentSchedule;


class ReassessmentService
{
    public function enrolledstudents($period_id, $educational_level_id = '', $college_id = '', $program_id = '', $year_level = '')
    {
        $query = Enrollment::with([
            'student', 
            'student.user:id,idno',
            'program',
            'program.level',
            'section:id,code',
            'enrolledby:id,idno'
        ])->where('period_id', $period_id)->where('assessed', 1)->where('validated', 1)->withCount('enrolled_classes');

        $query->when(isset($program_id) && !empty($program_id), function ($query) use($program_id) {
            $query->where('program_id', $program_id);
        });

        $query->when(isset($year_level) && !empty($year_level), function ($query) use($year_level) {
            $query->where('year_level', $year_level);
        });

        $query->when(isset($educational_level_id) && !empty($educational_level_id), function ($query) use($educational_level_id) {
            $query->whereHas('program.level', function($query) use($educational_level_id){
                $query->where('educational_level_id', $educational_level_id);
            });
        });

        $query->when(isset($college_id) && !empty($college_id), function ($query) use($college_id) {
            $query->whereHas('program.level', function($query) use($college_id){
                $query->where('college_id', $college_id);
            });
        });

        return $query->get();
    }

    public function reassessEnrollments($period_id, $educational_level_id = '', $college_id = '', $program_id = '', $year_level = '')
    {
        $setup_fees        = FeeSetup::with('fee.feetype')->where('period_id', $period_id)->get();
        $payment_schedules = PaymentSchedule::with('paymentmode')->where('period_id', $period_id)->get();

        $perPage = 1000;
        $all_enrollments = collect();

        Enrollment::with([
            'student',
            'student.user:id,idno',
            'program',
            'program.level',
            'enrolled_classes.class.curriculumsubject.subjectinfo'
        ])
        ->where('period_id', $period_id)
        ->where('assessed', 1)
        ->where('validated', 1)
        ->withCount('enrolled_classes')
        ->when(isset($program_id) && !empty($program_id), function ($query) use ($program_id) {
            $query->where('program_id', $program_id);
        })
        ->when(isset($year_level) && !empty($year_level), function ($query) use ($year_level) {
            $query->where('year_level', $year_level);
        })
        ->when(isset($educational_level_id) && !empty($educational_level_id), function ($query) use ($educational_level_id) {
            $query->whereHas('program.level', function ($query) use ($educational_level_id) {
                $query->where('educational_level_id', $educational_level_id);
            });
        })
        ->when(isset($college_id) && !empty($college_id), function ($query) use ($college_id) {
            $query->whereHas('program.level', function ($query) use ($college_id) {
                $query->where('college_id', $college_id);
            });
        })
        ->chunk($perPage, function ($enrollments) use (&$all_enrollments) {
            $all_enrollments = $all_enrollments->concat($enrollments);
        });


    }
    
}
