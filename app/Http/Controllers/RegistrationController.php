<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistrationService;
use Illuminate\Support\Facades\Redirect;
use App\Services\Assessment\AssessmentService;
use App\Services\Enrollment\EnrollmentService;

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

            $enrollment = Enrollment::where('student_id', $student->id)->where('period_id', session('current_period'))->first();

            $with_faculty = false;

            if($enrollment && $enrollment->assessed == 1)
            {
                $enrollment->load([
                    'program.level:id,code,level',
                    'program.collegeinfo:id,code,name',
                    'curriculum:id,program_id,curriculum',
                    'section:id,code,name',
                ]);
                
                $class_schedules = (new AssessmentService)->enrolledClassSchedules($enrollment->id);
                $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($enrollment->id);

                return view('registration.view_registration', compact('student', 'class_schedules', 'enrolled_classes', 'with_faculty', 'enrollment'));
            }else{

                $registration = $this->registrationService->studentRegistration($student);
                
                $class_schedules = (new AssessmentService)->enrolledClassSchedules($registration['enrollment']->id);
                $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($registration['enrollment']->id);
                // $section_subjects = (new EnrollmentService())->enrollSection($student->id, $data['section']->section_id, $enrollment->id);

                //return $registration;
                return view('registration.index', compact('student', 'registration', 'class_schedules', 'enrolled_classes', 'with_faculty', 'enrollment'));
            }

        } catch (\Exception $e) {
            return Redirect::route('home');
        }
    }
}
