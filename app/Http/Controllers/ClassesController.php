<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Term;
use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Services\ClassesService;
use App\Services\CurriculumService;
use App\Services\InstructorService;
use App\Http\Requests\UpdateClassRequest;
use Illuminate\Database\Eloquent\Collection;


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
        //return $this->classesService->processSchedule('9:00 AM-10:30 AM TTH ONLINE, 7:30 AM-9:00 AM TTH ONLINE');
        $class->load([
            'sectioninfo',
            'curriculumsubject.subjectinfo', 
            'instructor', 
            'schedule',
            'enrolledstudents.enrollment',
            'merged' => [
                'curriculumsubject' => fn($query) => $query->with('subjectinfo'),
                'sectioninfo',
                'instructor', 
                'schedule',
                'enrolledstudents.enrollment',
                'mergetomotherclass',
            ]
        ]);

        return response()->json(['data' => $class]);
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
    public function update(Classes $class, UpdateClassRequest $request)
    {
        $return = $this->classesService->UpdateClassSubject($class, $request);

        return response()->json(['data' => $return]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classes $class)
    {
        $data = $this->classesService->deleteClassSubject($class);
        
        return response()->json(['data' => $data]);
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

    public function merge(Request $request)
    {
        $class_info = $request->class; 

        return view('class.merge_class', ['class' => $class_info]);
    }

    public function searchcodetomerge(Request $request)
    {
        $search_classes = $this->classesService->searchClassSubjectsToMerge($request->searchcode);

        $classes = $search_classes->where('merge', 0)->where('ismother', 0)->where('id', '!=', $request->class_id);

        return view('class.return_search_code_results', ['classes' => $classes]);
    }

    public function savemerge(Request $request)
    {
        if($request->filled('class_ids'))
        {
            Classes::whereIn("id", $request->class_ids)->update(['merge' => $request->class_id]);
            Classes::where("id", $request->class_id)->update(["ismother" => 1]);

            return [
                'success' => true,
                'message' => 'Class subjects sucessfully merged!',
                'alert' => 'alert-success',
                'status' => 200
            ];
        }
       
        return [
            'success' => false,
            'message' => 'Please select at least one checkbox or class subject to merge!',
            'alert' => 'alert-danger',
            'status' => 401
        ];
    }

    public function unmergesubject(Request $request)
    {
        $class_info = Classes::with(['merged', 'mergetomotherclass' => ['merged']])->findOrFail($request->class_id);

        if($class_info->mergetomotherclass->merged->count() === 1)
        {
            Classes::where("id", $class_info->merge)->update(["ismother" => 0]);
        }

        $class_info->update(["merge" => NULL]);

        return [
            'success' => true,
            'message' => 'Class subject sucessfully unmerged!',
            'alert' => 'alert-success',
            'status' => 200
        ];
    }

    public function viewmergedclasses(Classes $class)
    {
        $class->load([
            'sectioninfo',
            'curriculumsubject.subjectinfo', 
            'instructor', 
            'schedule',
            'enrolledstudents.enrollment.student',
            'merged' => [
                'curriculumsubject' => fn($query) => $query->with('subjectinfo'),
                'sectioninfo',
                'instructor', 
                'schedule',
                'enrolledstudents.enrollment.student',
                'mergetomotherclass',
            ]
        ]);

        return view('class.merged_classes', ['class' => $class]);
    }

    public function displayenrolled(Classes $class)
    {
        $enrolled_students = $this->classesService->displayEnrolledToClassSubject($class);

        //return $enrolled_students;
        return view('class.display_enrolled_students', $enrolled_students);
    }

    public function inlineupdateslots(Request $request)
    {
        $update_slots = $this->classesService->saveInlineUpdateSlots($request);

        return response()->json($update_slots);

    }
}
