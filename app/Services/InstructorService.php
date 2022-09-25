<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\Instructor;
use App\Models\Useraccess;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class InstructorService
{
    //
    public function returnInstructors($request, $all = false)
    {
        $query = Instructor::with(['collegeinfo', 'deptinfo'])->orderBy('last_name')->orderBy('first_name');

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
        if($request->has('status') && ($request->status == '0' || $request->status == '1')) {
            $query->orWhereHas('user', function (Builder $query) use($request) {
                $query->where('is_active', $request->status);
            });
        }

        if($all){
            return $query->get();
        }
    
        return $query->paginate(10);
    }

    public function returnInstructorAccesses()
    {
        foreach (Helpers::instructorDefaultAccesses() as $key => $access) {
            $accesses[] = new Useraccess([
                'access' => $access['access'],
                'title' => $access['title'],
                'category' => $access['category'],
                'read_only' => 1,
                'write_only' => 1
            ]);
        }

        return $accesses;
    }
}
