<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Student;
use App\Models\TaggedGrades;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Collection;
use App\Services\CurriculumService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Services\Grade\ExternalGradeService;
use App\Services\Grade\InternalGradeService;
use App\Services\Evaluation\EvaluationService;
use Illuminate\Pagination\LengthAwarePaginator;

class EvaluationController extends Controller
{
    protected $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
        Helpers::setLoad(['jquery_evaluation.js', 'select2.full.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $studentService = new StudentService();

        $students = $studentService->returnStudents($request);

        if($request->ajax())
        {
            return view('evaluation.return_students', compact('students'));
        }

        return view('evaluation.index', compact('students'));

    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         //$students = $this->evaluationService->handleUser(Auth::user(), $request);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $taggrades = $this->evaluationService->storeTaggedGrades($request);
    
        return response()->json(['data' => $taggrades]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Student $evaluation, Request $request)
    {
        $student = $evaluation->load(['user', 'curriculum', 'program']);

        $student_evaluation = $this->evaluationService->evaluateStudent($student, $request);
        
        if($request->ajax())
        {
            return view('evaluation.return_evaluation', $student_evaluation);
        }

        return view('evaluation.evaluate', $student_evaluation);
    }

    public function taggrade(Request $request)
    {
        $student            = (new StudentService)->studentInformation($request->student_id);
        $curriculum_subject = (new CurriculumService)->returnCurriculumSubject($request->curriculum_subject_id);
        $all_tagged_grades  = $this->evaluationService->studentsAllTaggedGrades($request->student_id);
        $allgrades          = $this->evaluationService->getAllGradesInternalAndExternal($request->student_id);

        return view('evaluation.tagged_grade', ['student' => $student, 'curriculum_subject' => $curriculum_subject, 'allgrades' => $allgrades, 'all_tagged_grades' => $all_tagged_grades]);
    }

    public function returntaggrade($student_id, $curriculum_subject_id)
    {
        $student            = (new StudentService)->studentInformation($student_id);
        $curriculum_subject = (new CurriculumService)->returnCurriculumSubject($curriculum_subject_id);
        $all_tagged_grades  = $this->evaluationService->studentsAllTaggedGrades($student_id);
        $allgrades          = $this->evaluationService->getAllGradesInternalAndExternal($student_id);

        return view('evaluation.return_tagged_grades', ['student' => $student, 'curriculum_subject' => $curriculum_subject, 'allgrades' => $allgrades, 'all_tagged_grades' => $all_tagged_grades]);
    }
}
