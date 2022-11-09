<?php

namespace App\Services\Grade;

use App\Models\Grade;
use App\Libs\Helpers;
use App\Services\StudentService;

class GradeService
{
    public function getGradeInfoByStudentAndPeriod($student_id, $period_id, $origin)
    {
        $origin = ($origin === 'internal') ? Grade::ORIGIN_INTERNAL : Grade::ORIGIN_EXTERNAL;
        
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

        return $this->studentInfo($grade, $origin);
    }

    public function studentInfo($grade, $origin)
    {
        if($origin === 'internal'){
            return [
                'grade_id' => $grade->id,
                'curriculum' => $grade->enrollment->curriculum->curriculum,
                'level' => $grade->enrollment->program->level->level,
                'program' => $grade->enrollment->program->name,
                'college' => $grade->enrollment->program->collegeinfo->code,
                'year_level' => Helpers::yearLevel($grade->enrollment->year_level),
            ];
        }

        return [
            'grade_id' => $grade->id,
            'curriculum' => $grade->student->curriculum->curriculum,
            'level' => $grade->program->level->level,
            'program' => $grade->program->name,
            'college' => $grade->program->collegeinfo->code,
            'year_level' => Helpers::yearLevel($grade->student->year_level),
        ];
    }

    public function returnGradeInfo($grade_id)
    {
        return Grade::find($grade_id);
    }

    public function storeGrade($request, $origin)
    {
        return Grade::firstOrCreate([
            'student_id' => $request->student_id,
            'period_id' => $request->period_id,
            'school_id' => $request->school_id,
            'program_id' => $request->program_id,
            'origin' => $origin,
        ], $request->validated());

        return [
            'success' => false,
            'message' => 'Duplicate entry, grade already exists!',
            'alert' => 'alert-danger',
            'status' => 401
        ];
    }

    public function getAllExternalGrades($student_id, $period_id)
    {
        return Grade::where('student_id', $student_id)->where('period_id', $period_id)->where('origin', Grade::ORIGIN_EXTERNAL)->get();
    }

    public function deleteGrade($grade_id)
    {
        Grade::where('id', $grade_id)->firstOrFail()->delete();
    }

    public function getExternalGradeInfo($student_id, $period_id, $school_id, $program_id)
    {

    }

}

