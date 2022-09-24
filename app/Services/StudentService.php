<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\Student;
use App\Models\Useraccess;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class StudentService
{
    //
    public function returnStudents($request, $all = false)
    {
        $query = Student::with(['program', 'curriculum'])->orderBy('last_name')->orderBy('first_name');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where('last_name', 'like', '%'.$request->keyword.'%')
                    ->orWhere('first_name', 'like', '%'.$request->keyword.'%')
                    ->orWhere('middle_name', 'like', '%'.$request->keyword.'%')
                    ->orWhereHas('user', function (Builder $query) use($request) {
                        $query->where('idno', 'like', '%'.$request->keyword.'%');
                    });
        }
        if($request->has('program') && !empty($request->program)) {
            $query->where('program_id', $request->program);
        }
        if($request->has('academic_status') && !empty($request->academic_status)) {
            $query->where('academic_status', $request->academic_status);
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

    public function returnStudentAccesses()
    {
        foreach (Helpers::studentDefaultAccesses() as $key => $access) {
            $accesses[] = new Useraccess([
                'access' => $access['access'],
                'title' => $access['title'],
                'category' => $access['category'],
            ]);
        }

        return $accesses;
    }
}
