<?php

namespace App\Services;

use App\Models\Subject;

class SubjectService
{
    //

    public function returnSubjects($request, $all = false)
    {
        $query = Subject::with(['collegeinfo', 'educlevel'])->orderBy('code');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where('code', 'like', '%'.$request->keyword.'%')->orWhere('name', 'like', '%'.$request->keyword.'%');
        }
        if($request->has('educational_level') && !empty($request->educational_level)) {
            $query->where('educational_level_id', $request->educational_level);
        }
        if($request->has('college') && !empty($request->college)) {
            $query->where('college_id', $request->college);
        }
        if($request->has('type') && !empty($request->type)) {
            if($request->type === 'professional'){
                $query->where('professional', 1);
            }
            if($request->type === 'laboratory'){
                $query->where('laboratory', 1);
            }
        }

        if($all){
            return $query->get();
        }
    
        return $query->paginate(10);
        
    }
}
