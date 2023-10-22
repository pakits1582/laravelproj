<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Assessment;
use App\Services\FeeService;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\Enrollment;
use App\Models\PaymentSchedule;
use App\Services\Assessment\AssessmentService;
use App\Services\Enrollment\EnrollmentService;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{

    protected $assessmentService;

    public function __construct(AssessmentService $assessmentService)
    {
        $this->assessmentService = $assessmentService;
        Helpers::setLoad(['jquery_assessment.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('assessment.index');
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
    public function show(Assessment $assessment)
    {
        $assessment_info = $this->assessmentService->assessmentInformation($assessment);
        
        return view('assessment.assessment_preview', $assessment_info)->with('withbutton', 1);
    }

    public function studentassessment()
    {
        $user = Auth::user();

        $student_id = $user->studentinfo->id; 
        $period_id = session("current_period");
        
        $assessment = Assessment::whereHas('enrollment', function ($query) use ($student_id, $period_id) {
            $query->where('student_id', $student_id)->where('period_id', $period_id);
        })->first();
        
        if ($assessment) 
        {
            $assessment_info = $this->assessmentService->assessmentInformation($assessment);
            // $class_schedules = $this->assessmentService->enrolledClassSchedules($assessment->enrollment_id);
            // $with_faculty = false;

            // $assessment_info['class_schedules'] = $class_schedules;
            // $assessment_info['with_faculty'] = $with_faculty;

            return view('assessment.student_assessment', $assessment_info)->with('withbutton', 0);
                
        }else{

            return view('assessment.student_assessment', ['success' => false]);
        }
        
        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assessment $assessment)
    {
        $data = $this->assessmentService->updateAssessment($request, $assessment);

        return response()->json(['data' => $data]);
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

    public function printassessment(Assessment $assessment)
    {
        $assessment_info = $this->assessmentService->assessmentInformation($assessment);
        
        $pdf = PDF::loadView('assessment.print_assessment', $assessment_info)->setPaper('a4', 'portrait');
       
        return $pdf->stream('assessment.pdf');
    }

    public function scheduletable(Request $request)
    {
        $class_schedules = $this->assessmentService->enrolledClassSchedules($request->enrollment_id);
        $with_faculty = false;

        return view('class.schedule_table', compact('class_schedules', 'with_faculty'));
    }


  
}
