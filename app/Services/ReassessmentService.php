<?php

namespace App\Services;

use App\Models\Enrollment;


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
}
