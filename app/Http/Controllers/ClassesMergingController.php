<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\ClassesMergingService;

class ClassesMergingController extends Controller
{
    protected $classesMergingService;

    public function __construct(ClassesMergingService $classesMergingService)
    {
        $this->classesMergingService = $classesMergingService;
        Helpers::setLoad(['jquery_classes.js', 'jquery_classes_merging.js', 'select2.full.min.js']);
    }

    public function merge(Request $request)
    {
        $class_info = $request->class; 

        return view('class.merging.merge_class', ['class' => $class_info]);
    }

    public function searchcodetomerge(Request $request)
    {
        $search_classes = $this->classesMergingService->searchClassSubjectsToMerge($request->searchcode);

        $classes = $search_classes->where('merge', 0)->where('ismother', 0)->where('id', '!=', $request->class_id);

        return view('class.merging.return_search_code_results', ['classes' => $classes]);
    }

    public function savemerge(Request $request)
    {
       $savemerge = $this->classesMergingService->saveMergeClasses($request);

       return response()->json($savemerge);

    }

    public function unmergesubject(Request $request)
    {
        $unmerge = $this->classesMergingService->unmergeClassSubject($request);

        return response()->json($unmerge);
    }

    public function viewmergedclasses(Classes $class)
    {
        $class->load([
            'sectioninfo',
            'curriculumsubject.subjectinfo', 
            'instructor', 
            'schedule',
            'enrolledstudents',
            'merged' => [
                'curriculumsubject' => fn($query) => $query->with('subjectinfo'),
                'sectioninfo',
                'instructor', 
                'schedule',
                'enrolledstudents',
                'mergetomotherclass',
            ]
        ]);

        return view('class.merging.merged_classes', ['class' => $class]);
    }

    public function show(Classes $class)
    {
        $class->load([
            'sectioninfo',
            'curriculumsubject.subjectinfo',
            'instructor',
            'schedule',
            'enrolledstudents',
            'merged.curriculumsubject.subjectinfo',
            'merged.sectioninfo',
            'merged.instructor',
            'merged.schedule',
            'merged.enrolledstudents'
        ]);
        
        return response()->json(['data' => $class]);
    }

}
