<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Services\Enrollment\EnrollmentService;
use Illuminate\Http\Request;

class StudentScheduleController extends Controller
{

    public function __construct()
    {
        Helpers::setLoad(['jquery_studentschedule.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    public function index()
    {
        return view('studentschedule.index');
    }

    public function enrolledclasssubjects(Request $request)
    {
        $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($request->enrollment_id);
        $with_checkbox = false;

        return view('enrollment.enrolled_class_subjects', compact('enrolled_classes', 'with_checkbox'));
    }
}
