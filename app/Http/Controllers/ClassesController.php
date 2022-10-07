<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Term;
use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Services\ClassesService;
use Illuminate\Support\Facades\DB;
use App\Services\CurriculumService;
use App\Services\InstructorService;
use Illuminate\Database\Eloquent\Builder;

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

    public function storeclasssubject(Request $request, CurriculumService $curriculumService)
    {
        $validated = $request->validate([
            'subjects' => 'required',
            'section'  => 'required'
        ]);

        $classes = [];
        foreach ($request->subjects as $key => $subject) {
            $curriculum_subject = $curriculumService->returnCurriculumSubject($subject);
            $classes[] = [
                'period_id' => session('current_period'),
                'section_id'    => $request->section,
                'curriculum_subject_id' => $subject,
                'units' => $curriculum_subject->subjectinfo->units,
                'tfunits' => $curriculum_subject->subjectinfo->tfunits,
                'loadunits' => $curriculum_subject->subjectinfo->loadunits,
                'lecunits' => $curriculum_subject->subjectinfo->lecunits,
                'labunits' => $curriculum_subject->subjectinfo->labunits,
                'hours' => $curriculum_subject->subjectinfo->hours,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        Classes::insert($classes);

        return response()->json([
            'success' => true,
            'message' => 'Selected subjects successfully added!',
            'alert' => 'alert-success'
        ], 200);
    }

    public function sectionclasssubjects(Request $request)
    {
        $section_subjects = $this->classesService->classSubjects($request);

        return view('class.return_sectionclasssubjects', compact('section_subjects'));
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
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function show(Classes $classes)
    {

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
    public function destroy(Classes $classes)
    {
        //
    }

    public function checkroomschedule(Request $request)
    {
        return $this->classesService->checkRoomSchedule($request);
    }
}
