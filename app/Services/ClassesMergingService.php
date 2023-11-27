<?php

namespace App\Services;

use App\Models\Classes;
use App\Models\EnrolledClass;
use App\Models\EnrolledClassSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClassesMergingService
{
    public function searchClassSubjectsToMerge($searchcodes)
    {
        $searchcodes=  explode(",",preg_replace('/\s+/', ' ', trim($searchcodes)));
        $searchcodes= array_map('trim', $searchcodes);

        $query = Classes::with([
            'sectioninfo',
            'instructor', 
            'schedule',
            'enrolledstudents',
            'curriculumsubject.subjectinfo'
        ])->where('period_id', session('current_period'));

        $query->where(function($query) use($searchcodes){
            foreach($searchcodes as $key => $code){
                $query->orwhere(function($query) use($code){
                    $query->orWhere('code', 'LIKE', $code.'%');
                    $query->orwhereHas('curriculumsubject.subjectinfo', function($query) use($code){
                        $query->where('subjects.code', 'LIKE', $code.'%');
                    });
                });
            }
        });

        return $query->get()->sortBy('curriculumsubject.subjectinfo.code');
    }

    public function saveMergeClasses($request)
    {
        try {
            DB::beginTransaction();

            if(!$request->filled('class_ids'))
            {
                return [
                    'success' => false,
                    'message' => 'Please select at least one checkbox or class subject to merge!',
                    'alert' => 'alert-danger',
                    'status' => 401
                ];
            }

            $mother_class = Classes::with('schedule')->findOrFail($request->class_id);

            if(!is_null($mother_class->schedule->schedule))
            {
                $class_schedules = (new ClassesService())->processSchedule($mother_class->schedule->schedule);
                $enrolled_classes_to_merge =  EnrolledClass::whereIn("class_id", $request->class_ids)->get();

                foreach ($enrolled_classes_to_merge as $key => $enrolled_class_to_merge) 
                {
                    foreach ($class_schedules as $key => $class_schedule) 
                    {
                        foreach ($class_schedule['days'] as $key => $day) {
                            $enroll_class_schedules[] = [
                                'enrollment_id' => $enrolled_class_to_merge->enrollment_id,
                                'class_id' => $enrolled_class_to_merge->class_id,
                                'from_time' => $class_schedule['timefrom'],
                                'to_time' => $class_schedule['timeto'],
                                'day' => $day,
                                'room' => $class_schedule['room'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                        }
                    }
                }
                
                //then delete all enrolled_class_schedules of classes to be merged
                EnrolledClassSchedule::whereIn("class_id", $request->class_ids)->delete();
                //insert new enrolled class schedules
                EnrolledClassSchedule::insert($enroll_class_schedules);
            }

            //update classes_to_merge set merge and schedule to mother class
            Classes::whereIn("id", $request->class_ids)->update(['merge' => $request->class_id, 'schedule_id' => $mother_class->schedule_id]);

            //update mother class set ismother=1
            Classes::where("id", $request->class_id)->update(["ismother" => 1]);
                
            DB::commit();
        
            return [
                'success' => true,
                'message' => 'Class subjects successfully merged!',
                'alert' => 'alert-success',
                'status' => 200
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

    public function unmergeClassSubject($request)
    {
        try {

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

        } catch (\Exception $e) {

            return [
                'success' => false,
                'message' => 'An error occurred while unmerging class subjects.',
                'alert' => 'alert-danger',
                'status' => 500
            ];
        }
    }
}