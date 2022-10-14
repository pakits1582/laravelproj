<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateClassRequest;
use Carbon\Carbon;
use App\Models\Term;
use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Services\ClassesService;
use App\Services\CurriculumService;
use App\Services\InstructorService;


class ClassesController extends Controller
{
    protected $classesService;

    public function __construct(ClassesService $classesService)
    {
        $this->classesService = $classesService;
        Helpers::setLoad(['jquery_classes.js', 'select2.full.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InstructorService $instructorService)
    {
        $instructors = $instructorService->getInstructor();

        return view('class.index', compact('instructors'));
    }

    public function addclassoffering(Section $section)
    {
        $terms = Term::where('source', Term::SOURCE_INTERNAL)->get();

        $curriculum_subjects = $this->classesService->filterCurriculumSubjects($section->programinfo->curricula()->first()->id, Session('periodterm'), $section->year, $section->id);

        return view('class.addclassoffering', compact('terms', 'section', 'curriculum_subjects'));
    }

    public function filtercurriculumsubjects(Request $request)
    {
        $curriculum_subjects = $this->classesService->filterCurriculumSubjects($request->curriculum, $request->term, $request->year_level, $request->section);

        return response()->json(['data' => $curriculum_subjects]);
    }

    public function store(Request $request, CurriculumService $curriculumService)
    {
        $this->classesService->storeClassSubjects($request, $curriculumService);

        return response()->json([
            'success' => true,
            'message' => 'Selected subjects successfully added!',
            'alert' => 'alert-success'
        ], 200);
    }

    public function sectionclasssubjects(Request $request)
    {
        $section_subjects = $this->classesService->classSubjects($request->section, $request->period ?? session('current_period'));
        
        if($request->has('period'))
        {
            return response()->json(['data' => $section_subjects]);
        }

        return view('class.return_sectionclasssubjects', compact('section_subjects'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function show(Classes $class)
    {
        return $this->classesService->processSchedule('9:00 AM-10:30 AM TTH ONLINE, 7:30 AM-9:00 AM TTH ONLINE');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function edit(Classes $class)
    {
        $class->load(['curriculumsubject.subjectinfo', 'instructor', 'schedule']);

        return response()->json(['data' => $class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classes $classes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classes $class)
    {
        $return = $this->classesService->deleteClassSubject($class);
        
        return response()->json($return, $return['status'] ?? '');
    }

    public function checkroomschedule(Request $request)
    {
        $return = $this->classesService->checkRoomSchedule($request);

        return response()->json(['error' => $return]);

    }

    public function checkconflicts(Request $request)
    {
        $return = $this->classesService->checkConflicts($request);

        return response()->json(['error' => $return]);

    }

    public function updateclasssubject(Classes $class, UpdateClassRequest $request)
    {
        $return = $this->classesService->UpdateClassSubject($class, $request);

        return response()->json(['data' => $return]);
    }

    public function generatecode()
    {
        $this->classesService->generateCode();

        return with('Code generated successfully!');
    }

    public function copyclass(Section $section)
    {
        $section_subjects = $this->classesService->classSubjects($section->id, session('current_period'));

        $sections = Section::with('programinfo')->where('program_id', $section->programinfo->id)->orderBy('code')->get();

        return view('class.copyclass', compact('section', 'section_subjects', 'sections'));
    }

    public function storecopyclass(Request $request)
    {
        $return = $this->classesService->storeCopyClass($request);
        
        return response()->json($return,$return['status'] ?? '');

    }
}
