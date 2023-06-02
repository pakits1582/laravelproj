<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\AdddropService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Assessment;

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
    public function update(UpdateEnrollmentRequest $request)
    {
        $enrollment = Enrollment::findOrFail($request->enrollment_id);
        $assessment = Assessment::findOrFail($enrollment->id);

        DB::beginTransaction();

        $enrollment->student()->update([
            'program_id' => $request->program_id,
            'year_level' => $request->year_level,
            'curriculum_id' => $request->curriculum_id,
        ]);

        $enrollment->update($request->validated());

        DB::commit();

        return $assessment->id;
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
