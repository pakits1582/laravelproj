<?php

namespace App\Http\Controllers;

use App\Models\GradingSystem;
use Illuminate\Http\Request;

class GradingSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $query = GradingSystem::with(['level', 'remark'])->orderBy('code');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', '%'.$request->keyword.'%')
                ->orWhere('value', 'like', '%'.$request->keyword.'%');
            });
        }
        
        if($request->has('educational_level_id') && !empty($request->educational_level_id)) {
            $query->where('educational_level_id', $request->educational_level_id);
        }

        $gradingsystems =  $query->paginate(10); 

        if($request->ajax())
        {
            return view('gradingsystem.return_gradingsystems', compact('gradingsystems'));
        }

        return view('gradingsystem.index', compact('gradingsystems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function show(GradingSystem $gradingSystem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function edit(GradingSystem $gradingSystem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GradingSystem $gradingSystem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function destroy(GradingSystem $gradingSystem)
    {
        //
    }
}
