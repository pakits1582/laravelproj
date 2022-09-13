<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Program;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Models\Educationallevel;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Http\Requests\StoreEducationalLevelRequest;
use App\Imports\ProgramsImport;

class ProgramController extends Controller
{
    public function __construct()
    {
        //$this->instructorService = $instructorService;
        Helpers::setLoad(['jquery_program.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$programs = Program::all();
        $programs = Program::with(['level', 'collegeinfo', 'headinfo'])->orderBy('code')->get();

        return view('program.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $heads = Instructor::where('designation', 2)->get()->sortBy('lname');

        return view('program.create', compact('heads'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgramRequest $request)
    {
        $insert = Program::firstOrCreate(['code' => $request->code, 'name' => $request->name], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'Program sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, program already exists!'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        $heads = Instructor::where('designation', 2)->get()->sortBy('lname');

        return view('program.edit', compact('program', 'heads'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramRequest $request, Program $program)
    {
        $program->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Program sucessfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        //
    }

    public function storelevel(StoreEducationalLevelRequest $request)
    {
        $insert = Educationallevel::firstOrCreate(['code' => $request->code, 'level' => $request->level], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Educational level successfully added!',
                'alert' => 'alert-success',
                'level_id' => $insert->id,
                'level' => $request->validated('level'),
            ], 200);
        }

        return response()->json(['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, level already exists!']);
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $import = new ProgramsImport;
            $import->import($file);

            //return errors
            dd($import->failures());
        }
    }
}
