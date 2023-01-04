<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
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
        $sections_offered = SectionMonitoring::where('period_id', session('current_period'))->get();

        return view('enrollment.searchandaddclasses', compact('sections_offered'));
    }
}
