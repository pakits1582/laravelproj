<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Services\Assessment\AssessmentService;
use App\Services\Enrollment\EnrollmentService;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistrationService;
use Illuminate\Support\Facades\Redirect;

class RegistrationController extends Controller
{

    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
        Helpers::setLoad(['jquery_registration.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    public function index()
    {
       try {
            $student = (new StudentService)->studentInformationByUserId(Auth::id());

            $enrollment = (new EnrollmentService)->studentEnrollment($student->id, session('current_period'));

            if($enrollment && $enrollment->assessed == 1)
            {
                $class_schedules = (new AssessmentService)->enrolledClassSchedules($enrollment->id);
                $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($enrollment->id);
                $with_faculty = false;

                return view('registration.view_registration', compact('student', 'class_schedules', 'enrolled_classes', 'with_faculty', 'enrollment'));
            }else{

                $registration = $this->registrationService->studentRegistration($student);

                return $registration;
                //return view('registration.index', compact('student'));
            }

        } catch (\Exception $e) {
            //return Redirect::route('home');
        }
    }
}
