<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Models\User;
use App\Libs\Helpers;
use App\Models\Program;
use App\Models\Subject;
use App\Models\Curriculum;
use Illuminate\Http\Request;
use App\Services\CurriculumService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCurriculumRequest;
use App\Http\Requests\StoreCurriculumSubjectsRequest;

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

    public function manage(Program $program)
    {
        $program->load('curricula');
        $terms = Term::where('source', 1)->get();
        $subjects = Subject::all();

        return view('curriculum.manage', compact(['program', 'terms', 'subjects']));
    }

    public function returncurricula(Request $request)
    {
        $curricula = Curriculum::where('program_id', $request->program)->orderBy('id', 'DESC')->get();

        return response()->json(['data' => $curricula]);
    }

    public function addnewcurriculum(Program $program)
    {
        return view('curriculum.addnewcurriculum', compact('program'));
    }

    public function storecurriculum(StoreCurriculumRequest $request)
    {
        $insert = Curriculum::firstOrCreate(['program_id' => $request->program_id, 'curriculum' => $request->curriculum], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Curriculum successfully added!',
                'alert' => 'alert-success',
                'id' => $insert->id,
                'curriculum' => $request->curriculum,
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, curriculum already exists!']);
    }

    public function searchsubject(Request $request)
    {
        $subjects = $this->curriculumService->searchSubjects($request);

        return response()->json(['data' => $subjects]);
    }

    public function storesubjects(StoreCurriculumSubjectsRequest $request)
    {
        $this->curriculumService->storeCurriculumSubejects($request);

        return response()->json([
            'success' => true,
            'message' => 'Subjects sucessfully added!',
            'alert' => 'alert-success'
        ], 200);
    }

    public function viewcurriculum(Program $program, Curriculum $curriculum)
    {
        $curriculuminfo = $this->curriculumService->viewCurriculum($program, $curriculum);
        
        return view('curriculum.view_curriculum', $curriculuminfo);
    }

}
