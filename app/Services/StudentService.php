<?php

namespace App\Services;

use App\Jobs\StudentUserAccess;
use App\Models\User;
use App\Libs\Helpers;
use App\Models\Period;
use App\Models\Student;
use App\Models\Useraccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentService
{
    //
    public function returnStudents($request, $all = false, $limit = 500)
    {
        $query = Student::with(['program', 'curriculum', 'user'])->orderBy('last_name')->orderBy('first_name')->limit($limit);

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('last_name', 'like', $request->keyword.'%')
                    ->orWhere('first_name', 'like', $request->keyword.'%')
                    ->orWhere('middle_name', 'like', $request->keyword.'%')
                    ->orWhereHas('user', function (Builder $query) use($request) {
                        $query->where('idno', 'like', $request->keyword.'%');
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

    public function insertStudent($request)
    {
        DB::beginTransaction();

        //INSERT TO USERS TABLE
        $user = User::create([
            'idno' => $request->idno,
            'password' => Hash::make('password'),
            'utype' => User::TYPE_STUDENT,
        ]);
        //INSERT TO STUDENT TABLE
        Student::firstOrCreate([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'name_suffix' => $request->name_suffix,
        ], array_merge($request->validated(), ['user_id' => $user->id])
        );
        
        $accesses = $this->returnStudentAccesses();
        $user->access()->saveMany($accesses);

        DB::commit();
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
            $query->where('user_id', '!=', null);

            if($request->has('searchTerm') && !empty($request->searchTerm)) 
            {
                $query->where(function($query) use($request){
                    $query->where('last_name', 'like', $request->searchTerm.'%')
                        ->orWhere('first_name', 'like', $request->searchTerm.'%')
                        ->orWhere('middle_name', 'like', $request->searchTerm.'%')
                        ->orWhereHas('user', function (Builder $query) use($request) {
                            $query->where('idno', 'like', $request->searchTerm.'%');
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
        $student = $this->studentInformation($student_id);
        
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

    public function studentInformation($student_id)
    {
        $student = Student::with([
            'program' => fn($query) => $query->with('curricula', 'level', 'collegeinfo'),
            'curriculum', 
            'user'
            ])->where('id', $student_id)->first();

        return $student;
    }

    public function studentFullInformation($request)
    {
        $query = Student::with(['user', 'academic_info', 'contact_info', 'personal_info']);

        $query->when($request->has('idno') , function ($query) use ($request) 
        {
            $idno = $request->idno;
            $query->whereHas('user', function($query) use($idno){
                $query->where('idno', $idno);
            });
        });

        return $query->first();
    }

    public function generateIdno($request)
    {
        try {
            $period = Period::findOrfail($request->period_id);
            $new_idno = '';

            if(!is_null($period->idmask))
            {
                $mask = $period->idmask.'%';
                
                $last_idno = User::select(DB::raw('MAX(idno) AS idno'))
                ->where('idno', 'LIKE', $mask)
                ->first();

                if($last_idno)
                {
                    $new_idno = $last_idno->idno+1;
                }else{
                    $idmaskLength = strlen($period->idmask);
                    $numberOfZeros = 7 - $idmaskLength;

                    $newId = $period->idmask . str_repeat('0', $numberOfZeros) . '1';
                    $new_idno = substr($newId, 0, 8);
                }
            }

            return $new_idno;

        } catch (ModelNotFoundException $e) {

            return[
                'success' => false,
                'message' => 'Period not found',
                'alert' => 'alert-danger'
            ];
        }
    }

    public function studentsWithNoAccess()
    {
        $students = Student::whereNotNull('user_id')
        ->whereDoesntHave('user.access')
        ->get();

        $user_accesses = [];

        foreach ($students as $k => $student) 
        {
            foreach (Helpers::studentDefaultAccesses() as $key => $access) 
            {
                $user_accesses[] = [
                    'user_id' => $student->user_id,
                    'access' => $access['access'],
                    'title' => $access['title'],
                    'category' => $access['category'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        $chunkedUserAccesses = array_chunk($user_accesses, 500);

        //return $chunkedUserAccesses;

        foreach ($chunkedUserAccesses as $chunk) 
        {
            if (!empty($chunk)) 
            {
                StudentUserAccess::dispatch($chunk);
            }
        }

        return[
            'success' => true,
            'message' => 'Records inserted successfully',
            'alert' => 'alert-success'
        ];

    }
}
