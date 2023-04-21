<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEnrollmentRequest;
use App\Libs\Helpers;
use App\Models\Assessment;
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
    public function show($student_id, $period_id = null)
    {
        $enrollment = $this->enrollmentService->studentEnrollment($student_id, $period_id ,1);

        return response()->json(['data' => $enrollment]);
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

    // public function saveenrollment(UpdateEnrollmentRequest $request, Enrollment $enrollment)
    // {
    //     return $this->enrollmentService->saveEnrollment($request, $enrollment);
        
    // }
    public function update(UpdateEnrollmentRequest $request, Enrollment $enrolment)
    {
        return $this->enrollmentService->saveEnrollment($request, $enrolment);
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
        return $this->enrollmentService->enrollClassSubjects($request);

    }

    public function enrolledclasssubjects(Request $request)
    {
        $enrolled_classes = $this->enrollmentService->enrolledClassSubjects($request->enrollment_id);

        return view('enrollment.enrolled_class_subjects', compact('enrolled_classes'));
    }

    public function deleteenrolledsubjects(Request $request)
    {
       $data = $this->enrollmentService->deleteSelectedSubjects($request);

        return response()->json(['data' => $data]);
    }

    public function searchandaddclasses()
    {
        $sections_offered = SectionMonitoring::with(['section'])->where('period_id', session('current_period'))->get();

        return view('enrollment.searchandaddclasses', compact('sections_offered'));
    }

    public function searchclasssubject(Request $request)
    {
        $checked_subjects = $this->enrollmentService->searchClassSubject($request);
        $user_permissions = Auth::user()->permissions;

        return view('enrollment.return_searchedclasses', compact('checked_subjects', 'user_permissions'));
    }

    public function searchclasssubjectbysection(Request $request)
    {
        $checked_subjects = $this->enrollmentService->searchClassSubjectBySection($request);
        $user_permissions = Auth::user()->permissions;

        return view('enrollment.return_searchedclasses', compact('checked_subjects', 'user_permissions'));
    }

    public function addselectedclasses(Request $request)
    {
        $data = $this->enrollmentService->addSelectedClasses($request);

        return response()->json(['data' => $data]);
    }

}
