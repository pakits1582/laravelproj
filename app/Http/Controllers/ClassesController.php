<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Services\ClassesService;
use App\Services\CurriculumService;
use App\Services\InstructorService;
use App\Http\Requests\UpdateClassRequest;

class ClassesController extends Controller
{
    protected $classesService;

    public function __construct(ClassesService $classesService)
    {
        $this->classesService = $classesService;
        Helpers::setLoad(['jquery_classes.js', 'jquery_classes_merging.js', 'select2.full.min.js']);
    }
 
    public function index(InstructorService $instructorService)
    {
        //$instructors = $instructorService->getInstructor();

        return view('class.index');
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

    public function show(Classes $class)
    {
        $class->load([
            'sectioninfo',
            'curriculumsubject.subjectinfo',
            'instructor',
            'schedule',
            'enrolledstudents.enrollment',
            'merged.curriculumsubject.subjectinfo',
            'merged.sectioninfo',
            'merged.instructor',
            'merged.schedule',
            'merged.enrolledstudents.enrollment',
            'merged.mergetomotherclass',
        ]);
        

        return response()->json(['data' => $class]);
    }

    public function edit(Classes $class)
    {
        $class->load(['curriculumsubject.subjectinfo', 'instructor', 'schedule']);

        return response()->json(['data' => $class]);
    }

    public function update(UpdateClassRequest $request, Classes $class)
    {
        $return = $this->classesService->UpdateClassSubject($class, $request);

        return response()->json($return);
    }

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
        $data = $this->classesService->generateCode();

        return response()->json($data);

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

    
    public function displayenrolled(Classes $class)
    {
        $enrolled_students = $this->classesService->displayEnrolledToClassSubject($class);

        return view('class.display_enrolled_students', $enrolled_students);
    }

    public function inlineupdateslots(Request $request)
    {
        $update_slots = $this->classesService->saveInlineUpdateSlots($request);

        return response()->json($update_slots);
    }

    public function scheduletable(Request $request)
    {
        $class_schedules = $this->classesService->scheduletableClassSchedules($request->section, session('current_period'));
        $with_faculty = true;

        return view('class.schedule_table', compact('class_schedules', 'with_faculty'));
    }
}
