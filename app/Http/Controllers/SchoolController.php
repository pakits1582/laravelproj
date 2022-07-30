<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolFormRequest;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    protected $school_model; 

    public function __construct()
    {
        $this->school_model = new School; 
    } 

    public function index()
    {
        $schools =  $this->school_model->allschools();

        return view('school.index', compact('schools'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('school.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SchoolFormRequest $request){

        $insert = $this->school_model->insertWithcheckdupli(['code' => $request->code, 'name' => $request->name], $request->validated());
       
        if($insert->wasRecentlyCreated){
            return back()->with(['alert-class' => 'alert-success', 'message' => 'School sucessfully added!']);
        }else{
            return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, school already exists!']) ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\school  $school
     * @return \Illuminate\Http\Response
     */
    public function show(school $school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\school  $school
     * @return \Illuminate\Http\Response
     */
    public function edit($school) {
        $schlinfo = $this->school_model->findSchool($school);

        if($schlinfo){
            return view('school.edit', ['schoolinfo' => $schlinfo]);
        }else{
            return redirect()->route('schoolindex');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\school  $school
     * @return \Illuminate\Http\Response
     */
    public function update(SchoolFormRequest $request, school $school){
        $sch = $this->school_model->checkDuplicateOnUpdate(
            [
                ['code', '=', $request->code],
                ['name', '=', $request->name],
                ['id', '<>', $school->id]
            ]);

        if($sch){
            return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, school already exists!']);
        }else{
            $this->school_model->updateSchool($request->validated(), ['id' => $school->id]);
            return back()->with(['alert-class' => 'alert-success', 'message' => 'School sucessfully updated!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\school  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(school $school){

        $school->delete();
        
        return redirect()->route('schoolindex')->with(['alert-class' => 'alert-success', 'message' => 'School sucessfully deleted!']);
    }
}
