<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolFormRequest;
use App\Services\SchoolService;

class SchoolController extends Controller
{
    protected $schoolService;

    public function __construct(SchoolService $schoolService)
    {
        $this->schoolService = $schoolService;
    }

    public function index()
    {
        $schools = $this->schoolService->allSchools();

        return view('school.index', compact('schools'));
    }

    public function create()
    {
        return view('school.create');
    }

    public function store(SchoolFormRequest $request)
    {
        return  $this->schoolService->saveSchool($request);
    }

    public function edit($schoolId)
    {
        return  $this->schoolService->editSchool($schoolId);
    }

    public function update(SchoolFormRequest $request, $school)
    {
        return  $this->schoolService->updateSchool($request, $school);
    }

    // public function show(school $school)
    // {
    //     //
    // }

    // public function destroy(school $school){

    //     $school->delete();

    //     return redirect()->route('schoolindex')->with(['alert-class' => 'alert-success', 'message' => 'School sucessfully deleted!']);
    // }
}
