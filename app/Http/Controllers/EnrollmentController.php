<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\EnrolledClass;
use App\Services\ClassesService;
use App\Services\StudentService;
use App\Models\SectionMonitoring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\EnrolledClassSchedule;
use App\Services\Enrollment\EnrollmentService;

class EnrollmentController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
        Helpers::setLoad(['jquery_enrollment.js', 'select2.full.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, StudentService $studentService)
    {
        return view('enrollment.index');
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
        $data = $this->enrollmentService->insertStudentEnrollment($request->studentinfo);
        
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function enrolmentinfo(Request $request)
    {
        $data = $this->enrollmentService->handleStudentEnrollmentInfo($request->student_id, $request->studentinfo);
        
        return response()->json(['data' => $data]);
    }

    public function checksectionslot(Request $request)
    {
        $data = $this->enrollmentService->checkSectionSlot($request->section_id);
        
        return response()->json(['data' => $data]);
    }

    public function studentenrollmentunitsallowed(Request $request)
    {
        $data = $this->enrollmentService->studentEnrollmentUnitsAllowed($request->curriculum_id, session('periodterm'), $request->year_level, $request->isprobi);
        
        return response()->json(['data' => $data]);
    }

    public function enrollsection(Request $request)
    {
        $data = $this->enrollmentService->enrollSection($request->student_id, $request->section_id, $request->enrollment_id);
        
        return response()->json(['data' => $data]);
    }

    public function enrollclasssubjects(Request $request)
    {
        DB::beginTransaction();

        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        $enrollment->update(['section_id' => $request->section_id]);
        $enrollment->enrolled_classes()->delete();
        $enrollment->enrolled_class_schedules()->delete();

        $enroll_classes = [];
        $enroll_class_schedules = [];

        foreach ($request->class_subjects as $key => $class_subject) {
            //ADD VALUES TO ACCESS ARRAY FOR MULTIPLE INPUT
            $enroll_classes[] = new EnrolledClass([
                'class_id' => $class_subject['id'],
                'user_id' => Auth::id(),
            ]);
            
            if(!is_null($class_subject['schedule']['schedule']))
            {
                $class_schedules = (new ClassesService())->processSchedule($class_subject['schedule']['schedule']);

                foreach ($class_schedules as $key => $class_schedule) 
                {
                    foreach ($class_schedule['days'] as $key => $day) {
                        $enroll_class_schedules[] = new EnrolledClassSchedule([
                            'class_id' => $class_subject['id'],
                            'from_time' => $class_schedule['timefrom'],
                            'to_time' => $class_schedule['timeto'],
                            'day' => $day,
                            'room' => $class_schedule['room'],
                        ]);
                    }
                }
            }
        }

        $enrollment->enrolled_classes()->saveMany($enroll_classes);
        $enrollment->enrolled_class_schedules()->saveMany($enroll_class_schedules);

        DB::commit();

        return true;
    }

    public function enrolledclasssubjects(Request $request)
    {
        $enrolled_classes = EnrolledClass::with([
            'class' => [
                'sectioninfo',
                'instructor', 
                'schedule',
                'curriculumsubject' => ['subjectinfo']
            ],
            'addedby'
        ])->where('enrollment_id', $request->enrollment_id)->get();

        //return $enrolled_classes;

        return view('enrollment.enrolled_class_subjects', compact('enrolled_classes'));
    }

    public function deleteenrolledsubjects(Request $request)
    {
        $selectedclasses = EnrolledClass::where('enrollment_id', $request->enrollment_id)->whereIn('class_id', $request->class_ids)->delete();
        $selectedclassesscheds = EnrolledClassSchedule::where('enrollment_id', $request->enrollment_id)->whereIn('class_id', $request->class_ids)->delete();

        return $selectedclassesscheds;
    }

    public function searchandaddclasses()
    {
        $sections_offered = SectionMonitoring::with(['section'])->where('period_id', session('current_period'))->get();

        return view('enrollment.searchandaddclasses', compact('sections_offered'));
    }

    public function searchclasssubject(Request $request)
    {
        $enrollment_id = $request->enrollment_id;
        $student_id =  $request->enrollment_id;
        $searchcodes = stripslashes($request->searchcodes);

        $searchcodes = array_unique(preg_split('/(\s*,*\s*)*,+(\s*,*\s*)*/', $searchcodes));
        $searchcodes= array_map('trim', $searchcodes);

        $query = Classes::with([
            'sectioninfo',
            'instructor', 
            'schedule',
            'enrolledstudents.enrollment',
            'curriculumsubject' => fn($query) => $query->with('subjectinfo')
        ])->where('period_id', session('current_period'))->where('dissolved', '!=', 1);

        $query->where(function($query) use($searchcodes){
            foreach($searchcodes as $key => $code){
                $query->orwhere(function($query) use($code){
                    $query->orWhere('code', 'LIKE', '%'.$code.'%');
                    $query->orwhereHas('curriculumsubject.subjectinfo', function($query) use($code){
                        $query->where('subjects.code', 'LIKE', '%'.$code.'%');
                    });
                });
            }
        });

        $section_subjects =  $query->get()->sortBy('curriculumsubject.subjectinfo.code');
        $subjects = $this->enrollmentService->handleClassSubjects($student_id, $section_subjects);
        $checked_subjects = $this->enrollmentService->checkClassesIfConflictStudentSchedule($enrollment_id, $subjects);

        //return $checked_subjects;

        return view('enrollment.return_searchedclasses', compact('checked_subjects'));
    }

    public function addselectedclasses(Request $request)
    {
        $class_subjects = Classes::with(['schedule'])->whereIn("id", $request->class_ids)->get();

        DB::beginTransaction();

        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        $enroll_classes = [];
        $enroll_class_schedules = [];

        foreach ($class_subjects as $key => $class_subject)
        {
            $enroll_classes[] = new EnrolledClass([
                'class_id' => $class_subject['id'],
                'user_id' => Auth::id(),
            ]);
            
            if(!is_null($class_subject['schedule']['schedule']))
            {
                $class_schedules = (new ClassesService())->processSchedule($class_subject['schedule']['schedule']);

                foreach ($class_schedules as $key => $class_schedule) 
                {
                    foreach ($class_schedule['days'] as $key => $day) {
                        $enroll_class_schedules[] = new EnrolledClassSchedule([
                            'class_id' => $class_subject['id'],
                            'from_time' => $class_schedule['timefrom'],
                            'to_time' => $class_schedule['timeto'],
                            'day' => $day,
                            'room' => $class_schedule['room'],
                        ]);
                    }
                }
            }
        }

        $enrollment->enrolled_classes()->saveMany($enroll_classes);
        $enrollment->enrolled_class_schedules()->saveMany($enroll_class_schedules);

        DB::commit();

        return response()->json(['data' => [
            'success' => true,
            'message' => 'Selected class subject/s successfully added!',
            'alert' => 'alert-success',
            'status' => 200
        ]]);
    }
}
