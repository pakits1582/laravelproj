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
        //
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
        //dd($student);
        $user_programs = $this->evaluationService->handleUser(Auth::user(), $request);

        //dd($user_programs);
        if($user_programs->contains('id', $student->program_id) || $user_programs->contains('program.id', $student->program_id))
        {
            $internal_grades = (new InternalGradeService())->getAllStudentPassedInternalGrades($student->id);
            $tagged_grades = TaggedGrades::where('student_id', $student->id)->get();
            $blank_grades = (new InternalGradeService())->getAllBlankInternalGrades($student->id);
            $curriculuminfo = (new CurriculumService())->viewCurriculum($student->program, $student->curriculum);
            // echo '<pre>';
            // print_r($blank_grades->toArray());
            $evaluation = [];
            if($curriculuminfo['program'])
            {
                for($x=1; $x <= $curriculuminfo['program']->years; $x++)
                {
                    foreach ($curriculuminfo['curriculum_subjects'] as $yearlevel => $curriculum_subject)
                    {
                        if ($yearlevel === $x)
                        {
                            //echo $yearlevel.'<br>';
                            foreach ($curriculum_subject as $term => $subjects)
                            {
                                foreach ($subjects->toArray() as $subject)
                                {
                                    $finalgrade = '';
		                            $cggrade    = '';
		                            $gradeid    = '';
		                            $units      = '';
		                            $origin     = '';
		                            $ispassed   = 0;
		                            $manage     = true;

                                    $grades = $internal_grades->where('subject_id', $subject['subject_id'])->toArray();
                                    //print_r($grades);
                                    if($grades)
                                    {
                                        $grade_info = $this->evaluationService->processGrades($grades);

                                        if($grade_info)
                                        {
                                            $finalgrade = $grade_info['grade'];
                                            $cggrade    = $grade_info['completion_grade'];
                                            $gradeid    = $grade_info['id'];
                                            $units      = $grade_info['units'];
                                            $origin     = 'internal';
                                            $ispassed   = 1;
                                            $manage     = ($subject['subjectinfo']['units'] !== $grade_info['units']) ? true : false;
                                        }
                                    }else{
                                        //CHECK EQUIVALENTS SUBJECTS IF PASSED
                                        if($subject['equivalents'])
                                        {
                                            //print_r($subject['equivalents']);
                                            $equivalent_subjects_internal_grades = [];

                                            foreach ($subject['equivalents'] as $key => $eqivalent_subject)
                                            {
                                                //echo $eqivalent_subject['curriculum_subject_id'];
                                                //GET ALL INTERNAL GRADES OF EQUIVALENT SUBJECTS
                                                $equivalent_subjects_internal_grades[] = $internal_grades->where('subject_id', $eqivalent_subject['equivalent'])->toArray();
                                            }
                                            if($equivalent_subjects_internal_grades)
                                            {
                                                $equivalent_subjects_internal_grades = call_user_func_array('array_merge', $equivalent_subjects_internal_grades);
                                                $grade_info = $this->evaluationService->processGrades($equivalent_subjects_internal_grades);
                                            }
                                            
                                            $tagged_grades_of_equivalents = $tagged_grades->where('curriculum_subject_id', $eqivalent_subject['curriculum_subject_id'])->toArray();

                                            if($ispassed === 0)
                                            {
                                                if($tagged_grades_of_equivalents)
                                                {
                                                   $grade_info = $this->evaluationService->checkTaggedGradeInfo($tagged_grades_of_equivalents);
                                                }
                                            }
                                            if($grade_info)
                                            {
                                                $finalgrade = $grade_info['grade'];
                                                $cggrade    = $grade_info['completion_grade'];
                                                $gradeid    = $grade_info['id'];
                                                $units      = $grade_info['units'];
                                                $origin     = $grade_info['source'];
                                                $ispassed   = 1;
                                                $manage     = true;
                                            }
                                        }
                                    }////end of grade is passed internal
                                    
                                    if($ispassed === 0)
                                    {
                                        $curriculum_subject_tagged_grades = $tagged_grades->where('curriculum_subject_id', $subject['id'])->toArray();

                                        if($curriculum_subject_tagged_grades)
                                        {
                                            $grade_info = $this->evaluationService->checkTaggedGradeInfo($curriculum_subject_tagged_grades);
                                            if($grade_info)
                                            {
                                                $finalgrade = $grade_info['grade'];
                                                $cggrade    = $grade_info['completion_grade'];
                                                $gradeid    = $grade_info['id'];
                                                $units      = $grade_info['units'];
                                                $origin     = $grade_info['source'];
                                                $ispassed   = 1;
                                                $manage     = true;
                                            }
                                        }//end of if has tagged subject
                                    }
                                    
                                    $subject['grade_info'] = [
                                        'finalgrade' => $finalgrade,
                                        'completion_grade' => $cggrade,
                                        'grade_id' => $gradeid,
                                        'units' => $units,
                                        'origin' => $origin,
                                        'ispassed' => $ispassed,
                                        'manage' => $manage,
                                        'inprogress' => (!$blank_grades->isEmpty()) ? ((Helpers::is_column_in_array($subject['subject_id'], 'subject_id', $blank_grades->toArray()) === false) ? 0 : 1) : 0
                                    ];

                                    $evaluation[] = $subject;
                                }
                            }
                        }
                    }
                }
            }

            return view('evaluation.evaluate', $curriculuminfo + ['student' => $student, 'evaluation' => $evaluation]);
        }
    }

    public function taggrade(Request $request)
    {
        $student = (new StudentService)->studentInformation($request->student_id);
        $curriculum_subject = (new CurriculumService)->returnCurriculumSubject($request->curriculum_subject_id);
        $all_tagged_grades = TaggedGrades::where('student_id', $request->student_id)->get();
        $allgrades = $this->evaluationService->getAllGradesInternalAndExternal($request->student_id);

        return view('evaluation.tagged_grade', ['student' => $student, 'curriculum_subject' => $curriculum_subject, 'allgrades' => $allgrades, 'all_tagged_grades' => $all_tagged_grades]);
    }
}
