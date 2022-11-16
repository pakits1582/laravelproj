<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Student;
use App\Services\CurriculumService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Services\Evaluation\EvaluationService;
use App\Services\Grade\InternalGradeService;
use App\Services\StudentService;
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
            $curriculuminfo = (new CurriculumService())->viewCurriculum($student->program, $student->curriculum);
            echo '<pre>';
            //dd($curriculuminfo);

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

                                    print_r($grades);
                                    // $grades = array_column($filtered->toArray(), 'grade');
                                    // if($grades){
                                    //     print_r(max($grades));
                                    // }
                                    // $cggrade = array_column($filtered->toArray(), 'completion_grade');
                                    // if($cggrade){
                                    //     print_r(max($cggrade));
                                    // }
                                    
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
