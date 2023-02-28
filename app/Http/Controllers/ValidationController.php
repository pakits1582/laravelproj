<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Enrollment;
use App\Services\ValidationService;
use Illuminate\Http\Request;

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

        return $validation;
        // $validation->studentledgers()->delete();
        // $validation->details()->delete();

        // if(!is_null($validation->grade))
        // {
        //     return 'may gradeno';
        // }else{
        //     return 'wala';
        // }

        // if($validation->studentledgers
        // ->isNotEmpty()) {
        //     return 'may ledger';
        // }else{
        //     return 'walng ledger';
        // }

           // return $validation;
        // if($validation->validated == 1)
        // {
        //     return [
        //         'success' => false,
        //         'message' => 'Student is already validated, uncheck validated checkbox to unvalidate enrollment.',
        //         'alert' => 'alert-danger',
        //         'status' => 401
        //     ];
        // }


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
