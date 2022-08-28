<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolFormRequest;
use App\Http\Requests\SchoolUpdateFormRequest;
use App\Models\School;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::all()->sortBy('code');
        $schools->load('addedby');

        return view('school.index', compact('schools'));
    }

    public function create()
    {
        return view('school.create');
    }

    public function store(SchoolFormRequest $request)
    {
        $insert = School::firstOrCreate(['code' => $request->code, 'name' => $request->name], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'School sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, school already exists!'])->withInput();
    }

    public function edit(School $school)
    {
        return view('school.edit', compact('school'));
    }

    public function update(SchoolUpdateFormRequest $request, School $school)
    {
        $school->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'School sucessfully updated!']);
    }

    public function show(School $school)
    {
        //
    }

    public function destroy(School $school)
    {
    }
}
