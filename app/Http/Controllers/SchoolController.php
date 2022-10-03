<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use App\Http\Requests\SchoolFormRequest;
use App\Http\Requests\SchoolUpdateFormRequest;


class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $query = School::orderBy('code');
        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', '%'.$request->keyword.'%')
                ->orWhere('name', 'like', '%'.$request->keyword.'%');
            });
        }

        $schools =  $query->paginate(10);

        if($request->ajax())
        {
            return view('school.return_schools', compact('schools'));
        }
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
}
