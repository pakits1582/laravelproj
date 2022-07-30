<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Http\Requests\SchoolFormRequest;
use App\Interfaces\SchoolRepositoryInterface;

class SchoolService
{
    //
    
    protected $school;

    public function __construct(SchoolRepositoryInterface $school)
    {
        $this->school = $school; 
    } 

    public function allSchools()
    {
        return $this->school->getAllSchools();
    }

    public function saveSchool($request)
    {
    
        $insert = $this->school->createSchool(['code' => $request->code, 'name' => $request->name], $request->validated());
       
        if($insert->wasRecentlyCreated){
            return back()->with(['alert-class' => 'alert-success', 'message' => 'School sucessfully added!']);
        }else{
            return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, school already exists!']) ->withInput();
        }
    }

    public function editSchool($schoolId)
    {
            $schlinfo = $this->school->getSchoolById($schoolId);
    
            if($schlinfo){
                return view('school.edit', ['schoolinfo' => $schlinfo]);
            }else{
                return redirect()->route('schoolindex');
            }
    }

    public function updateSchool($request, $schoolId)
    {
            $sch = $this->school->checkDuplicateOnUpdate(
            [
                ['code', '=', $request->code],
                ['name', '=', $request->name],
                ['id', '<>', $schoolId]
            ]);

        if($sch){
            return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, school already exists!']);
        }else{
            $this->school->updateSchool(['id' => $schoolId], $request->validated());
            return back()->with(['alert-class' => 'alert-success', 'message' => 'School sucessfully updated!']);
        }
    }


   
}
