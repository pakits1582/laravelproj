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

            
            $curriculuminfo = (new CurriculumService())->viewCurriculum($student->program, $student->curriculum);
            echo '<pre>';
            print_r($tagged_grades->toArray());
            //dd($tagged_grades);

            if($curriculuminfo['program']){
                for($x=1; $x <= $curriculuminfo['program']->years; $x++){
                    foreach ($curriculuminfo['curriculum_subjects'] as $yearlevel => $curriculum_subject){
                        if ($yearlevel === $x){
                            echo $yearlevel.'<br>';
                            foreach ($curriculum_subject as $term => $subjects){
                                echo $term.'<br>';
                                
                                foreach ($subjects->toArray() as $subject){
                                    
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
                                    }else{
                                        //CHECK EQUIVALENTS SUBJECTS IF PASSED
                                        if($subject['equivalents'])
                                        {
                                            foreach ($subject['equivalents'] as $key => $eqivalent_subject)
                                            {
                                                //GET ALL INTERNAL GRADES OF EQUIVALENT SUBJECTS
                                                $equivalent_subjects_internal_grades[] = $internal_grades->where('subject_id', $eqivalent_subject['equivalent'])->toArray();
                                                // $tagged_grades_equivalents = $tagged_grades->where('curriculum_subject_id', $eqivalent_subject['curriculum_subject_id'])->toArray();

                                                // print_r($tagged_grades_equivalents);
                                            }

                                            $equivalent_subjects_internal_grades = call_user_func_array('array_merge', $equivalent_subjects_internal_grades);
                                            $grade_info = $this->evaluationService->processGrades($equivalent_subjects_internal_grades);
                                            

                                            //print_r($grade_info);
                                        }
                                    }////end of grade is passed internal
                                    
                                    if($ispassed === 0){
                                        $curriculum_subject_tagged_grades = $tagged_grades->where('curriculum_subject_id', $subject['id'])->toArray();

                                        if($curriculum_subject_tagged_grades)
                                        {
                                            foreach ($curriculum_subject_tagged_grades as $key => $cst_grade) {
                                                if($cst_grade['origin'] === 1){
                                                    $external_grade_info = (new ExternalGradeService())->getExternalGradeInfo($cst_grade['grade_id']);

                                                    print_r($external_grade_info->toArray());
                                                }else{
                                                    //$grade = $internal_grades->where('subject_id', $eqivalent_subject['equivalent'])->toArray();

                                                }
                                            }
                                        }
                                    }
                                   
                                    //print_r($subject);
                                }
                            }
                        }
                    }
                }
            }
                

            // return view('evaluation.evaluate', $curriculuminfo + ['student' => $student]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
