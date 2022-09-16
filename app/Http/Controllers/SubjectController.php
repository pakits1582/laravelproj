<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Subject;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use App\Exports\SubjectsExport;
use App\Imports\SubjectsImport;
use App\Services\SubjectService;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;

        Helpers::setLoad(['jquery_subject.js']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subjects = $this->subjectService->returnSubjects($request);
        
        if($request->ajax()){
            return view('subject.return_subjects', compact('subjects'));
        }
        return view('subject.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subject.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectRequest $request)
    {
        $insert = Subject::firstOrCreate(['code' => $request->code, 'name' => $request->name, 'units' => $request->units], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'Subject sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, subject already exists!'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        return view('subject.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Subject sucessfully updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        //
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $import = new SubjectsImport;
            $import->import($file);

            //return errors
            return $import->failures();
        }
    }

    public function export(Request $request)
    {
        $import = new SubjectsExport();
        
        return $import->download('subjects.xlsx');
    }

    public function generatepdf(Request $request)
    {
        $subjects = $this->subjectService->returnSubjects($request, true);

        $pdf = PDF::loadView('subject.generatepdf', ['subjects' => $subjects]);
        return $pdf->stream('subjects.pdf');
    }
}
