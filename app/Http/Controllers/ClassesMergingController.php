<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\ClassesService;

class ClassesMergingController extends Controller
{
    protected $classesService;

    public function __construct(ClassesService $classesService)
    {
        $this->classesService = $classesService;
        Helpers::setLoad(['jquery_classes.js', 'jquery_classes_merging.js', 'select2.full.min.js']);
    }

    public function merge(Request $request)
    {
        $class_info = $request->class; 

        return view('class.merging.merge_class', ['class' => $class_info]);
    }

    public function searchcodetomerge(Request $request)
    {
        $search_classes = $this->classesService->searchClassSubjectsToMerge($request->searchcode);

        $classes = $search_classes->where('merge', 0)->where('ismother', 0)->where('id', '!=', $request->class_id);

        return view('class.merging.return_search_code_results', ['classes' => $classes]);
    }

    public function savemerge(Request $request)
    {
        if($request->filled('class_ids'))
        {
            Classes::whereIn("id", $request->class_ids)->update(['merge' => $request->class_id]);
            Classes::where("id", $request->class_id)->update(["ismother" => 1]);

            return [
                'success' => true,
                'message' => 'Class subjects sucessfully merged!',
                'alert' => 'alert-success',
                'status' => 200
            ];
        }
       
        return [
            'success' => false,
            'message' => 'Please select at least one checkbox or class subject to merge!',
            'alert' => 'alert-danger',
            'status' => 401
        ];
    }

    public function unmergesubject(Request $request)
    {
        $class_info = Classes::with(['merged', 'mergetomotherclass' => ['merged']])->findOrFail($request->class_id);

        if($class_info->mergetomotherclass->merged->count() === 1)
        {
            Classes::where("id", $class_info->merge)->update(["ismother" => 0]);
        }

        $class_info->update(["merge" => NULL]);

        return [
            'success' => true,
            'message' => 'Class subject sucessfully unmerged!',
            'alert' => 'alert-success',
            'status' => 200
        ];
    }

    public function viewmergedclasses(Classes $class)
    {
        $class->load([
            'sectioninfo',
            'curriculumsubject.subjectinfo', 
            'instructor', 
            'schedule',
            'enrolledstudents.enrollment.student',
            'merged' => [
                'curriculumsubject' => fn($query) => $query->with('subjectinfo'),
                'sectioninfo',
                'instructor', 
                'schedule',
                'enrolledstudents.enrollment.student',
                'mergetomotherclass',
            ]
        ]);

        return view('class.merging.merged_classes', ['class' => $class]);
    }

}
