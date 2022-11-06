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

        $grade = $gradeService->returnGradeInfo($request->grade_id);

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
                'status' => 200
            ];        }

        return [
            'success' => false,
            'message' => 'Duplicate entry, grade already exists!',
            'alert' => 'alert-danger',
            'status' => 401
        ];

    }

}

