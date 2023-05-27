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
        return Grade::with(['student' => ['user'], 'period'])->find($grade_id);
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

    public function returnGradeFiles($request)
    {
        $grade_file = Grade::with(['period', 'school', 'program'])->where('student_id', $request->student_id);

        if (!empty($request->period_id)) 
        {
            $grade_file->where('period_id', $request->period_id);
        }

        $grades = $grade_file->get();

        $grade_files = [];
        if($grades)
        {
            foreach ($grades as $key => $v) 
            {
                $grade_id = $v->id;
                if (!isset($grade_files[$grade_id])) {
                    $grade_files[$grade_id] = [
                        'grade_id'  => $grade_id,
                        'period_id'  => $v->period->id,
                        'period_year' => $v->period->year,
                        'period_code' => $v->period->code,
                        'period_name' => $v->period->name,
                        'program_code' => $v->program->code,
                        'program_name' => $v->program->name,
                        'school_code' => $v->school->code ?? '',
                        'school_name' => $v->school->name ?? '',
                        'origin' => $v->origin
                    ];

                    if($v->origin == 0)
                    {
                        $grade_files[$grade_id]['grades'] = (new InternalGradeService())->getInternalGrades($grade_id)->toArray();
                    }else{
                        $grade_files[$grade_id]['grades'] = (new ExternalGradeService())->getExternalGrades($grade_id)->toArray();
                    }
                }
            }
        }

        usort($grade_files, function ($a, $b) {
            return $a['period_year'] <=> $b['period_year'];
        });

        return array_values($grade_files);
    }

}

