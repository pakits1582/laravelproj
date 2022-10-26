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
        $query = Student::with(['program', 'curriculum', 'user'])->orderBy('last_name')->orderBy('first_name');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('last_name', 'like', '%'.$request->keyword.'%')
                    ->orWhere('first_name', 'like', '%'.$request->keyword.'%')
                    ->orWhere('middle_name', 'like', '%'.$request->keyword.'%')
                    ->orWhereHas('user', function (Builder $query) use($request) {
                        $query->where('idno', 'like', '%'.$request->keyword.'%');
                });
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

    public function dropdownSelectSearch($request)
    {
        if($request->searchTerm)
        {
            $query = Student::with(['program', 'curriculum', 'user'])->orderBy('last_name')->orderBy('first_name');

            if($request->has('searchTerm') && !empty($request->searchTerm)) 
            {
                $query->where(function($query) use($request){
                    $query->where('last_name', 'like', '%'.$request->searchTerm.'%')
                        ->orWhere('first_name', 'like', '%'.$request->searchTerm.'%')
                        ->orWhere('middle_name', 'like', '%'.$request->searchTerm.'%')
                        ->orWhereHas('user', function (Builder $query) use($request) {
                            $query->where('idno', 'like', '%'.$request->searchTerm.'%');
                    });
                });
            }

            $students = $query->limit(20)->get();

            $data = [];
            if(!$students->isEmpty())
            {
                foreach ($students as $key => $student) {
                    $data[] = ['id' => $student->id, 'text' => '('.$student->user->idno.') '.$student->name];
                }
            } 

            return $data;
        }
    }

    public function returnStudentInfo($student_id)
    {
        $student = Student::with(['program', 'curriculum', 'user', 'program.curricula', 'program.level', 'program.collegeinfo'])->where('id', $student_id)->first();

        if($student)
        {
            return  [
                'success' => true,
                'values' => $student
            ];
        }

        return [
            'success' => false,
            'message' => 'Please check selection! Student does not exist!',
            'alert' => 'alert-danger'
        ];
    }
}
