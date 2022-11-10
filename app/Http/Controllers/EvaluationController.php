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

        if($user_programs->contains('id', $student->program_id))
        {
            $curriculum_subjects = (new CurriculumService())->viewCurriculum( $student->program_id, $student->curriculum);

            dd($curriculum_subjects);
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
