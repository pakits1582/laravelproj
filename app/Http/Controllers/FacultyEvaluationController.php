<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluationAnswerRequest;
use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\FacultyEvaluation;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use Illuminate\Support\Facades\Auth;
use App\Services\FacultyEvaluationService;
use App\Services\QuestionService;

class FacultyEvaluationController extends Controller
{
    protected $facultyEvaluationService;

    public function __construct(FacultyEvaluationService $facultyEvaluationService)
    {
        $this->facultyEvaluationService = $facultyEvaluationService;
        Helpers::setLoad(['jquery_facultyevaluation.js', 'select2.full.min.js']);
    }

    public function index(Request $request)
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $classes = $this->facultyEvaluationService->classesForEvaluation(Auth::user(), session('current_period'));
        $classeswithslots = $this->facultyEvaluationService->getClassesSlots($classes);
        $instructors = $this->facultyEvaluationService->getUniqueInstructors($classes);

        return view('facultyevaluation.index', compact('classeswithslots', 'periods', 'instructors'));
    }

    public function evaluationaction(Classes $class, $action)
    {
        $action = $this->facultyEvaluationService->evaluationAction($class, $action);

        return response()->json($action); 
    }

    public function filter(Request $request)
    {
        $classes = $this->facultyEvaluationService->classesForEvaluation(Auth::user(), session('current_period'), $request->instructor_id, NULL, true);
        $classeswithslots = $this->facultyEvaluationService->getClassesSlots($classes);

        return view('facultyevaluation.return_evaluations', compact('classeswithslots'));

    }

    public function results()
    {
        $classes = $this->facultyEvaluationService->classesForEvaluation(Auth::user(), session('current_period'), null, null, true, FacultyEvaluation::CLASS_FOR_EVALUATION_TRUE, Auth::id());
        $classeswithslots = $this->facultyEvaluationService->getClassesSlots($classes);
        $instructors = $this->facultyEvaluationService->getUniqueInstructors($classes);

        return view('facultyevaluation.result.index', compact('classeswithslots', 'instructors'));
    }

    public function viewresult(Classes $class)
    {
        $result = $this->facultyEvaluationService->evaluationResult($class);

        //dd($result);
        return view('facultyevaluation.result.view', compact('result'));
    }

    public function resetevaluation(Classes $class)
    {
        $reset = $this->facultyEvaluationService->resetEvaluation($class);

        return response()->json($reset); 
    }

    public function viewrespondents(Classes $class)
    {
        $respondents = $this->facultyEvaluationService->returnRespondents($class);

        return view('facultyevaluation.result.view_respondents', compact('respondents'));
    }

    public function studentfacultyevaluation()
    {
        $enrollment = $this->facultyEvaluationService->studentEnrollment(Auth::id());
        $classes_for_evaluation = $this->facultyEvaluationService->studentClassesForEvaluation($enrollment);
        
        return view('facultyevaluation.student.index', compact('enrollment', 'classes_for_evaluation'));
    }

    public function evaluateclass(FacultyEvaluation $facultyevaluation)
    {
        if($facultyevaluation->status == FacultyEvaluation::FACULTY_EVAL_FINISHED)
        {
            return redirect()->route('studentfacultyevaluation');
        }
        
        $evaluate_class = $this->facultyEvaluationService->evaluateClass($facultyevaluation);

        return view('facultyevaluation.student.evaluate', compact('evaluate_class'));
    }

    public function saveevaluation(StoreEvaluationAnswerRequest $request)
    {   
        $save_evaluation = $this->facultyEvaluationService->saveEvaluationAnswers($request);

        return response()->json($save_evaluation); 
    }

    public function printresult(Classes $class)
    {

    }

    public function commentsummary(Classes $class)
    {
        $result = $this->facultyEvaluationService->commentSummary($class);

        return view('facultyevaluation.result.comment_summary', compact('result'));
    }

}
