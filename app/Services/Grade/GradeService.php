<?php

namespace App\Services\Grade;

use App\Models\Grade;
use App\Libs\Helpers;
use App\Services\StudentService;

class GradeService
{
    public function getGradeInfoByStudentAndPeriod($student_id, $period_id, $origin)
    {
        $grade = Grade::with([
            'enrollment' => fn($query) => $query->with(
                'curriculum',
                'program',
                'program.level',
                'program.collegeinfo'
            )
        ])->oforigin($origin)->where('student_id', $student_id)->where('period_id', $period_id)->first();

        if(!$grade)
        {
            $studentService = new StudentService();

            $student = $studentService->returnStudentInfo($student_id);

            return [
                'grade_id' => 0,
                'curriculum' => $student['values']['curriculum']['curriculum'],
                'level' => $student['values']['program']['level']['level'],
                'program' => $student['values']['program']['name'],
                'college' => $student['values']['program']['collegeinfo']['code'],
                'year_level' => Helpers::yearLevel($student['values']['year_level']),
            ];
        }

        return [
            'grade_id' => $grade->id,
            'curriculum' => $grade->enrollment->curriculum->curriculum,
            'level' => $grade->enrollment->program->level->level,
            'program' => $grade->enrollment->program->name,
            'college' => $grade->enrollment->program->collegeinfo->code,
            'year_level' => Helpers::yearLevel($grade->enrollment->year_level),
        ];
    }

}

