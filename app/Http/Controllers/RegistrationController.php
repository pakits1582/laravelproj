<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationClassesRequest;
use App\Libs\Helpers;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;
use App\Models\EnrolledClassSchedule;
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
                $enrollment = $registration['enrollment'];

                $query = EnrolledClassSchedule::with([
                    'class' => [
                        'instructor',
                        'curriculumsubject.subjectinfo', 
                        'sectioninfo'
                    ]
                ])->where('enrollment_id', $registration['enrollment']->id);
        
                $enrolled_class_schedules = $query->get();
                
                $class_schedules  = (new AssessmentService)->classScheduleArray($enrolled_class_schedules);
                $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($registration['enrollment']->id);
                $section_subjects = (new EnrollmentService())->enrollSection($student->id, $registration['enrollment']->section_id, $registration['enrollment']->id);
                $section_subjects = $this->registrationService->checkClassesIfConflictStudentSchedule($enrolled_class_schedules, $section_subjects);
                $section_subjects = $this->registrationService->checkIfClassIfDuplicate($enrolled_classes, $section_subjects);
              
                //return $registration;
                return view('registration.index', compact('student', 'section_subjects', 'registration', 'class_schedules', 'enrolled_classes', 'with_faculty', 'enrollment'));
            }
        } catch (\Exception $e) {
            return Redirect::route('home');
        }
    }

    public function store(StoreRegistrationClassesRequest $request)
    {
        $save_classes = $this->registrationService->saveSelectedClasses($request);

        return response()->json($save_classes);
    }

    public function sectionofferings(Request $request)
    {
        $query = EnrolledClassSchedule::with([
            'class' => [
                'instructor',
                'curriculumsubject.subjectinfo', 
                'sectioninfo'
            ]
        ])->where('enrollment_id', $request->enrollment_id);

        $enrolled_class_schedules = $query->get();
        $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($request->enrollment_id);
        $section_subjects = (new EnrollmentService())->enrollSection($request->student_id, $request->section_id);
        $section_subjects = $this->registrationService->checkClassesIfConflictStudentSchedule($enrolled_class_schedules, $section_subjects);
        $section_subjects = $this->registrationService->checkIfClassIfDuplicate($enrolled_classes, $section_subjects);

        return view('registration.section_subjects', compact('section_subjects'));

    }

    public function enrolledclasssubjects(Request $request)
    {
        $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($request->enrollment_id);

        return view('enrollment.enrolled_class_subjects', compact('enrolled_classes'));
    }

    public function scheduletable(Request $request)
    {
        $query = EnrolledClassSchedule::with([
            'class' => [
                'instructor',
                'curriculumsubject.subjectinfo', 
                'sectioninfo'
            ]
        ])->where('enrollment_id', $request->enrollment_id);

        $enrolled_class_schedules = $query->get();
        
        $class_schedules  = (new AssessmentService)->classScheduleArray($enrolled_class_schedules);

        return view('class.schedule_table', 'class_schedules');
    }

    public function deleteenrolledsubjects(Request $request)
    {
       $data = (new EnrollmentService())->deleteSelectedSubjects($request);

        return response()->json(['data' => $data]);
    }
}
