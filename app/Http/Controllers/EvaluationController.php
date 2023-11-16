<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Student;
use App\Models\TaggedGrades;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Services\StudentService;
use App\Models\CurriculumSubjects;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Services\CurriculumService;
use App\Services\TaggedGradeService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Services\ConfigurationService;
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

    // public function paginate($items, $perPage = 5, $page = null, $options = [])
    // {
    //     $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    //     $items = $items instanceof Collection ? $items : Collection::make($items);
    //     return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        DB::enableQueryLog();

        $curriculum_subjects = CurriculumSubjects::with([
            'subjectinfo', 
            'terminfo', 
            'prerequisites', 'prerequisites.curriculumsubject', 'prerequisites.curriculumsubject.subjectinfo',
            'equivalents', 'equivalents.subjectinfo',
        ])->where('curriculum_id', 1)->get();

        //return $curriculum_subjects;
        $grouped = $curriculum_subjects->groupBy(['year_level', 'terminfo.term']);
    
        dd(DB::getQueryLog());
    
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
        $taggrades = (new TaggedGradeService)->storeTaggedGrades($request);
    
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
        $student = $evaluation->load([ 
            'program.curricula', 
            'program.level', 
            'program.collegeinfo',
            'curriculum', 
            'user'
        ]);

        $student_evaluation = $this->evaluationService->evaluateStudent($student);
        
        //return $student_evaluation;
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
        $all_tagged_grades  = (new TaggedGradeService)->getAllTaggedGrades($request->student_id);
        $allgrades          = $this->evaluationService->getAllGradesInternalAndExternal($request->student_id);

        $return = ['student' => $student, 'curriculum_subject' => $curriculum_subject, 'allgrades' => $allgrades, 'all_tagged_grades' => $all_tagged_grades];
        
        if($request->from_return === 'yes')
        {
            return view('evaluation.return_tagged_grades', $return);
        }

        return view('evaluation.tagged_grade', $return);
    }

    public function studentevaluation()
    {
        $student = (new StudentService)->studentInformationByUserId(Auth::id());
        $config_schedules = (new ConfigurationService)->configurationSchedule(session('current_period'),'grade_posting');
        $student_evaluation = $this->evaluationService->evaluateStudent($student);
        $configuration = Configuration::take(1)->first();

        //return $student_evaluation;
        return view('evaluation.student.index', $student_evaluation + compact('config_schedules', 'configuration'));
    }

}
