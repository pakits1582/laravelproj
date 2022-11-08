<?php

namespace App\Services\Grade;

use App\Libs\Helpers;
use App\Models\Grade;
use App\Models\ExternalGrade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExternalGradeService
{
    public function storeExternalGrade($request)
    {
    
        $gradeService = new GradeService();

        $grade = $this->gradeInfo($request, $gradeService);

        $gradeinfo = ($grade) ? $grade : $gradeService->storeGrade($request, Grade::ORIGIN_EXTERNAL);

        $insert = ExternalGrade::firstOrCreate([
            'grade_id' => $gradeinfo->id,
            'subject_code' => $request->subject_code,
            'subject_description' => $request->subject_description,
            'grade' => $request->grade,
        ], $request->validated()+['grade_id' => $gradeinfo->id, 'user_id' => Auth::id()]);
       
        if ($insert->wasRecentlyCreated) {
            return [
                'success' => true,
                'message' => 'External Grade successfully added!',
                'alert' => 'alert-success',
                'grade_id' => $gradeinfo->id,
                'status' => 200
            ];        }

        return [
            'success' => false,
            'message' => 'Duplicate entry, grade already exists!',
            'alert' => 'alert-danger',
            'status' => 401
        ];

    }

    public function gradeInfo($request, $gradeService)
    {
        $grade = $gradeService->returnGradeInfo($request->grade_id);

        if($grade)
        {
            if($grade->school_id != $request->school_id || $grade->program_id != $request->program_id){
                $grade->update(['school_id' => $request->school_id, 'program_id' => $request->program_id]);
            }
        }

        return $grade;
    }

    public function updateExternalGrade($request, $gradeexternal)
    {
        $gradeService = new GradeService();

        $gradeinfo = $this->gradeInfo($request, $gradeService);

        $gradeexternal->update($request->validated());

        return [
            'success' => true,
            'message' => 'External Grade successfully updated!',
            'alert' => 'alert-success',
            'grade_id' => $gradeinfo->id,
            'status' => 200
        ];        
    }

    public function getExternalGradeSubjects($grade_id)
    {
        return ExternalGrade::with([
            'gradeinfo' => fn($query) => $query->with('school','program'),
            'remark'])->where('grade_id', $grade_id)->get();
    }

    public function deleteExternalGrade($gradeexternal, $grade_id)
    {
        $gradeexternal->delete();

        $external_grades_count = $this->getExternalGradeSubjects($grade_id)->count();

        if($external_grades_count === 0){
            
        }

        // return [
        //     'success' => true,
        //     'message' => 'External Grade successfully deleted!',
        //     'alert' => 'alert-success',
        //     'status' => 200
        // ];        
    }

}

