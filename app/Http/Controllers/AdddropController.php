<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Assessment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\AdddropService;
use App\Services\ClassesService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateEnrollmentRequest;

class AdddropController extends Controller
{
    protected $adddropService;

    public function __construct(AdddropService $adddropService)
    {
        $this->adddropService = $adddropService;
        Helpers::setLoad(['jquery_adddrop.js', 'select2.full.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adddrop.index');
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
    public function show($id)
    {
        //
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
    public function update(UpdateEnrollmentRequest $request, Enrollment $adddrop)
    {
        $enrollment =  $adddrop->load([
            'assessment', 
            'student'        
        ]);

        DB::beginTransaction();

        $enrollment->student->update([
            'program_id' => $request->program_id,
            'year_level' => $request->year_level,
            'curriculum_id' => $request->curriculum_id,
        ]);

        $enrollment->update($request->validated());

        DB::commit();

        return $enrollment->assessment->id;
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
