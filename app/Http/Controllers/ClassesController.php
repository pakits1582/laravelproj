<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Services\CurriculumService;
use App\Services\InstructorService;

class ClassesController extends Controller
{
    protected $classesService;

    public function __construct(CurriculumService $classesService)
    {
        $this->classesService = $classesService;
        Helpers::setLoad(['jquery_classes.js', 'select2.full.min.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InstructorService $instructorService)
    {
        $instructors = $instructorService->getInstructor();

        return view('class.index', compact('instructors'));
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
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function show(Classes $classes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function edit(Classes $classes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classes $classes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classes $classes)
    {
        //
    }
}
