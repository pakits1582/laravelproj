<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\Program;

class ProgramService
{
    //

    public function returnPrograms($request, $all = false)
    {
        $query = Program::with(['level', 'collegeinfo', 'headinfo'])->orderBy('code');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where('code', 'like', '%'.$request->keyword.'%')->orWhere('name', 'like', '%'.$request->keyword.'%');
        }
        if($request->has('educational_level') && !empty($request->educational_level)) {
            $query->where('educational_level_id', $request->educational_level);
        }
        if($request->has('college') && !empty($request->college)) {
            $query->where('college_id', $request->college);
        }
        if($request->has('status') && ($request->status == '0' || $request->status == '1')) {
            $query->where('active', $request->status);
        }

        if($all){
            return $query->get();
        }
    
        return $query->paginate(10);
        
    }
}
