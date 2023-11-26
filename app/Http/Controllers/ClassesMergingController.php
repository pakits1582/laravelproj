<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use App\Models\EnrolledClass;
use Illuminate\Http\Request;
use App\Services\ClassesService;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();
            
            if($request->filled('class_ids'))
            {
                // if(!is_null($class_subject['schedule']['schedule']))
                // {
                //     $class_schedules = (new ClassesService())->processSchedule($class_subject['schedule']['schedule']);

                //     foreach ($class_schedules as $key => $class_schedule) 
                //     {
                //         foreach ($class_schedule['days'] as $key => $day) {
                //             $enroll_class_schedules[] = [
                //                 'enrollment_id' => $request->enrollment_id,
                //                 'class_id' => $class_subject['id'],
                //                 'from_time' => $class_schedule['timefrom'],
                //                 'to_time' => $class_schedule['timeto'],
                //                 'day' => $day,
                //                 'room' => $class_schedule['room'],
                //                 'created_at' => carbon::now(),
                //                 'updated_at' => carbon::now()
                //             ];
                //         }
                //     }
                // }

                
                // DB::commit();
            
                // return [
                //     'success' => true,
                //     'message' => 'Class subjects successfully merged!',
                //     'alert' => 'alert-success',
                //     'status' => 200
                // ];

                //get all enrolled in enrolled_classes of selected classes to be merged
                $enrolled_classes_to_merge =  EnrolledClass::whereIn("id", $request->class_ids)->get();

                //then delete all enrolled_class_schedules of classes to be merged

                //loop through all enrolled_classes_to merge get enrollment_id and class_id insert in an array
                //get the schedule of the mother code process and insert in an array for enrolled_class_schedules insertion

                //update classes_to_merge set merge and schedule to mother class
                // Classes::whereIn("id", $request->class_ids)->update(['merge' => $request->class_id, 'schedule_id' => motherclass->schedule_id]);

                //update mother class set ismother=1
                // Classes::where("id", $request->class_id)->update(["ismother" => 1]);


                return $enrolled_classes_to_merge;
            }
        
            return [
                'success' => false,
                'message' => 'Please select at least one checkbox or class subject to merge!',
                'alert' => 'alert-danger',
                'status' => 401
            ];

        } catch (\Exception $e) {

            return [
                'success' => false,
                'message' => 'An error occurred while merging class subjects.',
                'alert' => 'alert-danger',
                'status' => 500
            ];
        }
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
