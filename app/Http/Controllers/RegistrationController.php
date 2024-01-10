<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Assessment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;
use App\Models\EnrolledClassSchedule;
use App\Services\RegistrationService;
use App\Services\Assessment\AssessmentService;
use App\Services\Enrollment\EnrollmentService;
use App\Http\Requests\StoreRegistrationClassesRequest;

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
        //try {
            $student = (new StudentService)->studentInformationByUserId(Auth::id());

            $enrollment = Enrollment::where('student_id', $student->id)->where('period_id', session('current_period'))->first();
            $with_faculty = false;
            

            if($enrollment && $enrollment->assessed == 1)
            {
                $with_checkbox = false;

                $enrollment->load([
                    'program.level:id,code,level',
                    'program.collegeinfo:id,code,name',
                    'curriculum:id,program_id,curriculum',
                    'section:id,code,name',
                ]);
                
                $class_schedules = (new AssessmentService)->enrolledClassSchedules($enrollment->id);
                $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($enrollment->id);

                return view('registration.view_registration', compact('student', 'class_schedules', 'enrolled_classes', 'with_faculty', 'enrollment', 'with_checkbox'));
            }else{

                $registration = $this->registrationService->studentRegistration($student);

                if(!empty($registration['errors']))
                {
                    $errors = $registration['errors'];

                    return view('registration.index', compact('student', 'errors'));
                }else{
                    $enrollment = $registration['enrollment'];
                    $with_checkbox = true;
                    
                    $enrolled_class_schedules = (new EnrollmentService)->enrolledClassSchedules($registration['enrollment']->id);
                    $class_schedules  = (new AssessmentService)->classScheduleArray($enrolled_class_schedules);
                    $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($registration['enrollment']->id);
                    $section_subjects = (new EnrollmentService())->enrollSection($student->id, $registration['enrollment']->section_id, $registration['enrollment']->id);
                    $section_subjects = $this->registrationService->checkClassesIfConflictStudentSchedule($enrolled_class_schedules, $section_subjects);
                    $section_subjects = $this->registrationService->checkIfClassIfDuplicate($enrolled_classes, $section_subjects);
                
                    //return $registration;
                    return view('registration.index', compact('student', 'section_subjects', 'registration', 'class_schedules', 'enrolled_classes', 'with_faculty', 'with_checkbox', 'enrollment'));
                }
                
            }
        // } catch (\Exception $e) {
        //     return Redirect::route('home');
        // }
    }

    public function store(StoreRegistrationClassesRequest $request)
    {
        $save_classes = $this->registrationService->saveSelectedClasses($request);

        return response()->json($save_classes);
    }

    public function sectionofferings(Request $request)
    {
        $enrolled_class_schedules = (new EnrollmentService)->enrolledClassSchedules($request->enrollment_id);
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
        $enrolled_class_schedules = (new EnrollmentService)->enrolledClassSchedules($request->enrollment_id);
        $class_schedules  = (new AssessmentService)->classScheduleArray($enrolled_class_schedules);
        $with_faculty = false;

        return view('class.schedule_table', compact('class_schedules', 'with_faculty'));
    }

    public function deleteenrolledsubjects(Request $request)
    {
       $data = (new EnrollmentService())->deleteSelectedSubjects($request);

        return response()->json(['data' => $data]);
    }
    
    public function sectionofferingsbysection(Request $request)
    {
        $enrolled_class_schedules = (new EnrollmentService)->enrolledClassSchedules($request->enrollment_id);
        $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($request->enrollment_id);        
        $section_subjects = (new EnrollmentService)->searchClassSubjectBySection($request);
        $section_subjects = $this->registrationService->checkClassesIfConflictStudentSchedule($enrolled_class_schedules, $section_subjects);
        $section_subjects = $this->registrationService->checkIfClassIfDuplicate($enrolled_classes, $section_subjects);

        return view('registration.section_subjects', compact('section_subjects'));
    }

    public function searchandaddclasses()
    {
        return view('registration.searchandaddclasses');
    }

    public function searchclasssubject(Request $request)
    {
        $enrolled_class_schedules = EnrolledClassSchedule::where('enrollment_id', $request->enrollment_id)->get();
        $enrolled_classes = (new EnrollmentService)->enrolledClassSubjects($request->enrollment_id);     
           
        $checked_subjects = $this->registrationService->searchClassSubject($request, $enrolled_class_schedules, $enrolled_classes);

        return view('registration.return_searchedclasses', compact('checked_subjects'));
    }

    public function saveselectedclasses(Request $request)
    {
        $save_selected_searched_classes = $this->registrationService->saveSelectedClasses($request);

        return response()->json($save_selected_searched_classes);
    }

    public function saveregistration(Request $request, Enrollment $enrollment)
    {   
        $validatedData = $request->validate([
            'year_level' => 'required|numeric|max:5',
            'enrolled_units' => 'required|numeric|max:50',
        ]);

        $save_registration = $this->registrationService->saveRegistration($validatedData, $enrollment);

        return response()->json($save_registration);
    }

    public function assessmentpreview(Assessment $assessment)
    {
        $assessment_info = (new AssessmentService)->assessmentInformation($assessment);
        
        return view('assessment.assessment_preview', $assessment_info)->with('withbutton', 1);
    }

    public function saveassessment(Request $request, Assessment $assessment)
    {
        $data = (new AssessmentService)->updateAssessment($request, $assessment);

        return response()->json(['data' => $data]);
    }

    public function printassessment(Assessment $assessment)
    {
        $assessment_info = (new AssessmentService)->assessmentInformation($assessment);
        
        $pdf = PDF::loadView('assessment.print_assessment', $assessment_info)->setPaper('a4', 'portrait');
       
        return $pdf->stream('assessment.pdf');
    }
}
