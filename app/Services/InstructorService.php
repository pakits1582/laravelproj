<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Builder;

class InstructorService
{
    //
    public function returnInstructors($request, $all = false)
    {
        $query = Instructor::with(['collegeinfo', 'deptinfo']);

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where('last_name', 'like', '%'.$request->keyword.'%')
                    ->orWhere('first_name', 'like', '%'.$request->keyword.'%')
                    ->orWhere('middle_name', 'like', '%'.$request->keyword.'%')
                    ->orWhereHas('user', function (Builder $query) use($request) {
                        $query->where('idno', 'like', '%'.$request->keyword.'%');
                    });
        }
        if($request->has('educational_level') && !empty($request->educational_level)) {
            $query->where('educational_level_id', $request->educational_level);
        }
        if($request->has('college') && !empty($request->college)) {
            $query->where('college_id', $request->college);
        }
        if($request->has('department') && !empty($request->department)) {
            $query->where('department_id', $request->department);
        }

        if($all){
            return $query->orderBy('last_name')->orderBy('first_name')->get();
        }
    
        return $query->paginate(10);
        
    }
}
