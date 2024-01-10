<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ValidationService;
use App\Services\Enrollment\EnrollmentService;

class ValidationController extends Controller
{

    protected $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
        Helpers::setLoad(['jquery_validation.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('validation.index');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Enrollment $validation)
    {
        $validation->load([
            'studentledger_assessment',
            'grade',
            'enrolled_classes' => ['class'],
            'assessment' => ['details']
        ]);

        return $this->validationService->validateEnrollment($validation);
    }

    public function unvalidate(Enrollment $enrollment)
    {
        return $this->validationService->unvalidateEnrollment($enrollment);
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

    public function enrolledclasssubjects(Request $request)
    {
        $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($request->enrollment_id);
        $with_checkbox = false;

        return view('enrollment.enrolled_class_subjects', compact('enrolled_classes', 'with_checkbox'));
    }
}
