<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Libs\Helpers;
use App\Models\Curriculum;
use App\Models\Program;
use App\Models\Subject;
use App\Models\Term;
use App\Services\CurriculumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurriculumController extends Controller
{
    protected $curriculumService;

    public function __construct(CurriculumService $curriculumService)
    {
        $this->curriculumService = $curriculumService;
        Helpers::setLoad(['jquery_curriculum.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $programs = $this->curriculumService->handleUser(Auth::user(), $request);
        
        
        if($request->ajax()){
            return view('curriculum.return_programs', compact('programs'));
        }

        return view('curriculum.index', compact('programs'));
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

    public function manage(Program $program)
    {
        if (Helpers::getAccessAbility(Auth::user()->access->toArray(), 'curriculum', 'write_only') === 1)
        {
            $program->load('curricula');
            $terms = Term::where('source', 1)->get();
            $subjects = Subject::all();

            return view('curriculum.manage', compact(['program', 'terms', 'subjects']));
        }
        
        return abort(404, 'Page Not Found');
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
     * @param  \App\Models\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function show(Curriculum $curriculum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function edit(Curriculum $curriculum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curriculum $curriculum)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Curriculum $curriculum)
    {
        //
    }

    public function returncurricula(Request $request)
    {
        $curricula = Curriculum::where('program_id', $request->program)->orderBy('id', 'DESC')->get();

        return response()->json(['data' => $curricula]);
    }
    
}
