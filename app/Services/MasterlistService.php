<?php

namespace App\Services;

use App\Models\Enrollment;

class MasterlistService 
{
   
    public function masterList($period, $level = '', $college = '', $program_id = '', $year_level = '')
    {
        $query = Enrollment::with([
            'student', 
            'student.user' => function($query) {
                $query->select('id', 'idno');
            },
            'program' => ['level', 'collegeinfo']
        ]);

        $query->where('period_id', $period)->where('withdrawn', 0)->where('cancelled', 0);
        
        $query->when(isset($program_id) && !empty($program_id), function ($query) use($program_id) {
            $query->where('program_id', $program_id);
        });

        $query->when(isset($year_level) && !empty($year_level), function ($query) use($year_level) {
            $query->where('year_level', $year_level);
        });

        $query->when(isset($level) && !empty($level), function ($query) use($level) {
            $query->whereHas('program.level', function($query) use($level){
                $query->where('educational_levels.id', $level);
            });
        });

        $query->when(isset($college) && !empty($college), function ($query) use($college) {
            $query->whereHas('program.collegeinfo', function($query) use($college){
                $query->where('colleges.id', $college);
            });
        });

        $masterlist = $query->get();

        return $masterlist;
    }
}