<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Section::with('programinfo')->orderBy('code');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', '%'.$request->keyword.'%')
                ->orWhere('name', 'like', '%'.$request->keyword.'%');
            });
        }
        $sections =  $query->paginate(10);

        if($request->ajax())
        {
            return view('section.return_sections', compact('sections'));
        }
        return view('section.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('section.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSectionRequest $request)
    {
        $insert = Section::firstOrCreate(['code' => $request->code, 'name' => $request->name], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'Section sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, section already exists!'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        return view('section.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Section sucessfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }

    public function getsections(Request $request)
    {
        $query = Section::with('programinfo')->orderBy('code');

        if($request) {
            $query->where(function($query) use($request){
                $query->where('program_id', $request->program_id)
                ->where('year', $request->year_level);
            });
        }

        $sections = $query->get();

        return response()->json(['data' => $sections]);
    }
}
