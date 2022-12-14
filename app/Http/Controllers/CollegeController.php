<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Requests\CollegeFormRequest;
use App\Http\Requests\CollegeUpdateFormRequest;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = College::with('deaninfo');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', '%'.$request->keyword.'%')
                ->orWhere('name', 'like', '%'.$request->keyword.'%');
            });
        }

        $colleges =  $query->paginate(10);

        if($request->ajax())
        {
            return view('college.return_colleges', compact('colleges'));
        }
        return view('college.index', compact('colleges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $instructors = Instructor::where('designation', Instructor::TYPE_DEAN)->get();

        return view('college.create', compact('instructors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CollegeFormRequest $request)
    {
        $insert = College::firstOrCreate(['code' => $request->code, 'name' => $request->name, 'class_code' => $request->class_code], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'College sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, college already exists!'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\College  $college
     * @return \Illuminate\Http\Response
     */
    public function show(College $college)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\College  $college
     * @return \Illuminate\Http\Response
     */
    public function edit(College $college)
    {
        $instructors = Instructor::where('designation', Instructor::TYPE_DEAN)->get();

        return view('college.edit', compact('college', 'instructors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\College  $college
     * @return \Illuminate\Http\Response
     */
    public function update(CollegeUpdateFormRequest $request, College $college)
    {
        $college->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'College sucessfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\College  $college
     * @return \Illuminate\Http\Response
     */
    public function destroy(College $college)
    {
        //
    }
}
