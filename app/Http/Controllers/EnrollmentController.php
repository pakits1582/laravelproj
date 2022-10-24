<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Student;
use App\Services\Enrollment\EnrollmentService;
use App\Services\StudentService;
use Illuminate\Http\Request;

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
        
        return response()->json(['data' => $data]);
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

    public function getstudent(Request $request, StudentService $studentService)
    {
        $data = $studentService->returnStudentInfo($request->student_id);
        
        return response()->json(['data' => $data]);
       
    }

    public function enrolmentinfo(Request $request)
    {
        $data = $this->enrollmentService->handleStudentEnrollmentInfo($request->student_id, $request->studentinfo);
        
        return response()->json(['data' => $data]);
    }
}
