<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Classes;
use App\Models\CurriculumSubjects;

class ClassesService
{
    //
    public function filterCurriculumSubjects($curriculum_id, $term = '', $year_level = '', $section_id = '')
    {
        $query = CurriculumSubjects::with(['subjectinfo', 'prerequisites', 'corequisites', 'equivalents'])->where("curriculum_id", $curriculum_id);
        if($term !== '') {
            $query->where('term_id', $term);
        }

        if($year_level !== '') {
            $query->where('year_level', $year_level);
        }
        if($section_id !== '') {
            $query->whereNotIn('id',function($query) use ($section_id) {
                $query->select('curriculum_subject_id')->from('classes')
                        ->where('section_id', $section_id)
                        ->where('period_id', session('current_period'));
            });
        }

        return $query->get();
    }

    public function classSubjects($request)
    {
        $class_subjects = Classes::with(['curriculumsubject', 'instructor'])->where('section_id', $request->section)->where('period_id', session('current_period'))->get();
        
        return $class_subjects;
    }

    public function classSubject($class_id)
    {
        $class_subject = Classes::with(['sectioninfo', 'curriculumsubject', 'instructor', 'schedule'])->where('id', $class_id)->firstOrFail();
    }

    public function checkScheduleRoomifExist($room)
    {
        return Room::where('id', $room)->orWhere('code', $room)->first();
    }

    public function checkConflictRoom($class_id, $room_id, $start_time, $end_time, $day)
    {
        $query = Classes::with(['curriculumsubject.subjectinfo', 'instructor', 'schedule', 'classschedules'])
                ->join('classes_schedules', 'classes.id', '=', 'classes_schedules.classes_id')
                ->leftJoin('rooms', function($join) {
                    $join->on('classes_schedules.room_id', '=', 'rooms.id');
                });
                $query->where(function($query) use($start_time, $end_time){
                    $query->where('classes_schedules.from_time', '>=', $start_time)->Where('classes_schedules.from_time', '<', $end_time)
                    ->orwhere('classes_schedules.to_time', '>', $start_time)->Where('classes_schedules.to_time', '<=', $end_time);
                });
                $query->where('classes_schedules.day', '=', $day)->where('classes_schedules.room_id', '=', $room_id)->where('rooms.excludechecking', 0);
                $query->where('classes.period_id', session('current_period'))
                      ->where('classes.dissolved', '!=', 1)
                      ->where('classes.id', '!=', $class_id);

        $result = $query->get();

        $errors = [];




    }

    public function checkRoomSchedule($request)
    {
        $schedule = strtoupper(preg_replace('/\s+/', ' ', trim($request->schedule)));
        $errors = [];

        if($schedule !== ''){
            $schedules = explode(", ", $schedule);
            foreach($schedules as $sched){
				$splits = preg_split('/ ([MTWHFSU]+) /', $sched, -1, PREG_SPLIT_DELIM_CAPTURE);
                
				$times = $splits[0];
				$days  = $splits[1];
				$room  = $splits[2];

                $room_info = $this->checkScheduleRoomifExist($room);
                if(!$room_info){
                    $errors[] = 'Room <strong>'.$room.'</strong> does not exists!';
                }

                //check if time is valid
                $times    = explode("-", $times);
                $timefrom = Carbon::parse($times[0])->format('H:i:s');
                $timeto   = Carbon::parse($times[1])->format('H:i:s');

                if($timefrom >= $timeto){
                    $errors[] = 'Schedule <strong>'.$sched.'</strong> FROM is greater than TO!';
                }

                $splitdays = preg_split('/(.[HU]?)/', $days, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
                foreach($splitdays as $day){
                    return $this->checkConflictRoom($request->class_id, $room_info->id, $timefrom, $timeto, $day);
                }

                return $errors;
            }
        }
    }

}
