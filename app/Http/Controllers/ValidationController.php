<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ValidationService;

class ValidationController extends Controller
{

    protected $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
        Helpers::setLoad(['jquery_validation.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('validation.index');
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
    public function show(Enrollment $validation)
    {
        $validation->load([
            'studentledger_assessment',
            'grade',
            'assessment' => ['details']
        ]);

        if($validation->validated == 1)
        {
            return [
                'success' => false,
                'message' => 'Student is already validated, uncheck validated checkbox to unvalidate enrollment.',
                'alert' => 'alert-danger',
                'status' => 401
            ];
        }

        DB::beginTransaction();

        $validation->validated = 1;   
        $validation->save();

        $validation->grade()->delete();
        $validation->studentledger_assessment()->delete();
        

        $validation->grade()->create([
            'enrollment_id' => $validation->id,
            'student_id' => $validation->student_id,
            'period_id' => $validation->period_id,
            'program_id' => $validation->program_id,
            'origin' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $studentledger = $validation->studentledger_assessment()->create([
            'enrollment_id' => $validation->id,
            'source_id' => $validation->assessment->id,
            'type' => 'A',
            'amount' => $validation->assessment->amount,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if($validation->assessment->details->isNotEmpty()) 
        {
            $studentledger_details = [];
            foreach ($validation->assessment->details as $key => $assessment_detail) 
            {
                $studentledger_details[] = [
                    'studentledger_id' => $studentledger->id,
                    'fee_id' => $assessment_detail->fee_id,
                    'amount' =>  $assessment_detail->amount,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $studentledger->details()->insert($studentledger_details);
        }

        DB::commit();

        return [
            'success' => true,
            'message' => 'Student enrollment successfully validated.',
            'alert' => 'alert-success',
            'status' => 200
        ];
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
