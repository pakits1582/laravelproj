<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\ClassesSchedule;
use App\Models\CurriculumSubjects;
use Illuminate\Support\Facades\DB;

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

    public function storeClassSubject($request, $curriculumService)
    {
        $validated = $request->validate([
            'subjects' => 'required',
            'section'  => 'required'
        ]);

        $classes = [];
        foreach ($request->subjects as $key => $subject) {
            $curriculum_subject = $curriculumService->returnCurriculumSubject($subject);
            $classes[] = [
                'period_id' => session('current_period'),
                'section_id'    => $request->section,
                'curriculum_subject_id' => $subject,
                'units' => $curriculum_subject->subjectinfo->units,
                'tfunits' => $curriculum_subject->subjectinfo->tfunits,
                'loadunits' => $curriculum_subject->subjectinfo->loadunits,
                'lecunits' => $curriculum_subject->subjectinfo->lecunits,
                'labunits' => $curriculum_subject->subjectinfo->labunits,
                'hours' => $curriculum_subject->subjectinfo->hours,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        Classes::insert($classes);
    }

    public function classSubjects($request)
    {
        return Classes::with(['curriculumsubject.subjectinfo', 'instructor', 'schedule'])->where('section_id', $request->section)->where('period_id', session('current_period'))->get();
    }

    public function classSubject($class_id)
    {
        return Classes::with(['sectioninfo', 'curriculumsubject', 'instructor', 'schedule'])->where('id', $class_id)->firstOrFail();
    }

    public function checkScheduleRoomifExist($room)
    {
        return Room::where('id', $room)->orWhere('code', $room)->first();
    }

    public function checkConflictRoom($class_id, $room_id, $start_time, $end_time, $day)
    {
        $query = Classes::with(['sectioninfo', 'curriculumsubject.subjectinfo', 'instructor', 'schedule', 'classschedules'])
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

        return $query->distinct()->get();
    }

    public function checkConflictSection($section_id, $start_time, $end_time, $day, $class_id)
    {
        $query = Classes::with(['sectioninfo', 'curriculumsubject.subjectinfo', 'instructor', 'schedule'])
                ->join('classes_schedules', 'classes.id', '=', 'classes_schedules.classes_id')
                ->where('classes.period_id', session('current_period'))
                ->where('classes.section_id', $section_id);
                $query->where(function($query) use($start_time, $end_time){
                    $query->where('classes_schedules.from_time', '>=', $start_time)->Where('classes_schedules.from_time', '<', $end_time)
                    ->orwhere('classes_schedules.to_time', '>', $start_time)->Where('classes_schedules.to_time', '<=', $end_time);
                });
                $query->where('classes_schedules.day', '=', $day)
                      ->where('classes.dissolved', '!=', 1)
                      ->where('classes.id', '!=', $class_id);

        return $query->distinct()->get();
    }

    public function checkConflictFaculty($start_time, $end_time, $day, $class_id, $instructor_id)
    {
        $query = Classes::with(['sectioninfo', 'curriculumsubject.subjectinfo', 'instructor', 'schedule'])
                ->join('classes_schedules', 'classes.id', '=', 'classes_schedules.classes_id')
                ->where('classes.period_id', session('current_period'));
                $query->where(function($query) use($start_time, $end_time){
                    $query->where('classes_schedules.from_time', '>=', $start_time)->Where('classes_schedules.from_time', '<', $end_time)
                    ->orwhere('classes_schedules.to_time', '>', $start_time)->Where('classes_schedules.to_time', '<=', $end_time);
                });
                $query->where('classes_schedules.day', '=', $day)
                    ->where('classes.dissolved', '!=', 1)
                    ->where('classes.id', '!=', $class_id)
                    ->where('classes.instructor_id', $instructor_id);

        return $query->distinct()->get();
    }

    public function deleteClassSchedules($classes_id)
    {
        ClassesSchedule::where('classes_id', $classes_id)->delete();
    }

    public function generateCode()
    {
        $classes_withnocode = Classes::with('sectioninfo.programinfo.collegeinfo')->where('code', NULL)->orWhere('code', '=', '')->get();

        if(count($classes_withnocode)){
            foreach ($classes_withnocode as $key => $class_withnocode) {
                $class_code = $class_withnocode->sectioninfo->programinfo->collegeinfo->class_code;

                $last_code = DB::table('classes')->select(DB::raw('MAX(CAST(SUBSTRING(code, 2, length(code) -1) AS UNSIGNED)) AS lastcode')) ->where('code','like', $class_code.'%')->get()[0];
                $oldcode = ($last_code->lastcode) ? $last_code->lastcode : 0;

                $lcode = str_replace($class_code, "", $oldcode)+1;
				$newcode = $class_code.$lcode++;

                $class_withnocode->code = $newcode;
                $class_withnocode->save();
            }
        }

        return true;        
    }

    public function processSchedule($schedule)
    {
        $schedule_array = [];

        $schedule = strtoupper(preg_replace('/\s+/', ' ', trim($schedule)));
        $schedules = explode(", ", $schedule);

        foreach($schedules as $sched)
        {
            $splits = preg_split('/ ([MTWHFSU]+) /', $sched, -1, PREG_SPLIT_DELIM_CAPTURE);

            $times = $splits[0];
            $days  = $splits[1];
            $room  = $splits[2];

            $times    = explode("-", $times);
            $timefrom = Carbon::parse($times[0])->format('H:i:s');
            $timeto   = Carbon::parse($times[1])->format('H:i:s');

            $splitdays = preg_split('/(.[HU]?)/' ,$days, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

            $schedule_array[] = [
                'timefrom' => $timefrom,
                'timeto' => $timeto,
                'room' => $room,
                'days' => $splitdays,
                'raw_sched' => $sched
            ];
        }

        return $schedule_array;
    }

    public function checkRoomSchedule($request)
    {
        $error = '';

        if($request->schedule !== ''){
            $schedule_array = $this->processSchedule($request->schedule);

            foreach ($schedule_array as $key => $sched) {
                $room_info = $this->checkScheduleRoomifExist($sched['room']);
                if(!$room_info){
                    $error .= 'Room '.$sched['room'].' does not exist!</br>';
                }else{
                    if($sched['timefrom'] >= $sched['timeto']){
                        $error .= 'Schedule '.$sched['raw_sched'].' TIME FROM is greater than TIME TO!</br>';
                    }else{
                        if($sched['days']){
                            $conflicts = [];
                            foreach ($sched['days'] as $key => $day) {
                                $room_conflicts = $this->checkConflictRoom($request->class_id, $room_info->id, $sched['timefrom'], $sched['timeto'], $day);

                                if(!$room_conflicts->isEmpty())
                                {
                                    foreach ($room_conflicts as $key => $room_conflict) {
                                        $conflicts[] = 'Room Conflict: ('.$room_conflict->sectioninfo->code.') ['.$room_conflict->curriculumsubject->subjectinfo->code.']  - '.$room_conflict->schedule->schedule.'</br>';
                                    }
                                    break;
                                }
                            }
                            $error = array_unique($conflicts);
                        }
                    }
                }
            }
        }
        return $error;
    }

    public function checkConflicts($request)
    {
        $allconflicts = [];

        if($request->schedule !== '')
        {
            $schedule_array = $this->processSchedule($request->schedule);

            foreach ($schedule_array as $key => $sched) 
            {
                foreach ($sched['days'] as $key => $day) {
                    //CHECK CONFLICT SECTION
					$section_conflicts = $this->checkConflictSection($request->section, $sched['timefrom'], $sched['timeto'], $day, $request->class_id);
					
                    if(!$section_conflicts->isEmpty())
                    {
                        foreach ($section_conflicts as $key => $section_conflict) {
                            $conflicts[] = [
                                'class_code'   => ($section_conflict->code) ?? '',
                                'section_code' => $section_conflict->sectioninfo->code,
                                'subject_code' => $section_conflict->curriculumsubject->subjectinfo->code,
                                'schedule' => $section_conflict->schedule->schedule,
                                'conflict_from' => 'Section'
                            ];
                        }
                    }

                    if($request->instructor_id)
                    {
                        return ['xxxx'];
                        // //CHECK CONFLICT INSTRUCTOR
                        // $faculty_conflicts = $this->checkConflictFaculty($timefrom,$timeto,$day,$request->class_id, $request->instructor);
                        // if(!$faculty_conflicts->isEmpty())
                        // {
                        //     foreach ($faculty_conflicts as $key => $faculty_conflict) {
                        //         $conflicts[] = 
                        //                     [
                        //                         'class_code'   => ($faculty_conflict->code) ?? '',
                        //                         'section_code' => $faculty_conflict->sectioninfo->code,
                        //                         'subject_code' => $faculty_conflict->curriculumsubject->subjectinfo->code,
                        //                         'schedule' => $faculty_conflict->schedule->schedule,
                        //                         'conflict_from' => 'Faculty'
                        //                     ];
                        //     }
                        // }
                    }
                }

            }
            // $schedule   = preg_replace('/\s+/', ' ', trim($request->schedule));
            // $schedules = explode(", ", $schedule);
			// foreach($schedules as $sched)
            // {
			// 	$splits = preg_split('/ ([MTWHFSU]+) /', $sched, -1, PREG_SPLIT_DELIM_CAPTURE);

            //     $times = $splits[0];
			// 	$days  = $splits[1];
			// 	$room  = $splits[2];

			// 	$times = explode("-", $times);
			// 	$timefrom = Carbon::parse($times[0])->format('H:i:s');
            //     $timeto   = Carbon::parse($times[1])->format('H:i:s');

			// 	$splitdays = preg_split('/(.[HU]?)/' ,$days, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
            //     $conflicts = [];
			// 	foreach($splitdays as $key => $day){
			// 		//CHECK CONFLICT SECTION
			// 		$section_conflicts = $this->checkConflictSection($request->section,$timefrom,$timeto,$day,$request->class_id);
					
            //         if(!$section_conflicts->isEmpty())
            //         {
            //             foreach ($section_conflicts as $key => $section_conflict) {
            //                 $conflicts[] = 
            //                             [
            //                                 'class_code'   => ($section_conflict->code) ?? '',
            //                                 'section_code' => $section_conflict->sectioninfo->code,
            //                                 'subject_code' => $section_conflict->curriculumsubject->subjectinfo->code,
            //                                 'schedule' => $section_conflict->schedule->schedule,
            //                                 'conflict_from' => 'Section'
            //                             ];
            //             }
            //         }

            //         if($request->instructor)
            //         {
            //             //CHECK CONFLICT INSTRUCTOR
            //             $faculty_conflicts = $this->checkConflictFaculty($timefrom,$timeto,$day,$request->class_id, $request->instructor);
            //             if(!$faculty_conflicts->isEmpty())
            //             {
            //                 foreach ($faculty_conflicts as $key => $faculty_conflict) {
            //                     $conflicts[] = 
            //                                 [
            //                                     'class_code'   => ($faculty_conflict->code) ?? '',
            //                                     'section_code' => $faculty_conflict->sectioninfo->code,
            //                                     'subject_code' => $faculty_conflict->curriculumsubject->subjectinfo->code,
            //                                     'schedule' => $faculty_conflict->schedule->schedule,
            //                                     'conflict_from' => 'Faculty'
            //                                 ];
            //                 }
            //             }
            //         }
			// 	}//end of split days
            // }
            // $temp = array_unique(array_column($conflicts, 'conflict_from'));
            // $allconflicts = array_intersect_key($conflicts, $temp);
        }
        
        return $allconflicts;
    }

    public function updateClassSubject($class, $request)
    {
        $data = $request->validated();

        if($request->schedule !== '')
        {
            if($request->schedule !== $class->schedule->schedule)
            {
                $schedule_info = Schedule::firstOrCreate(['schedule' => $request->schedule], ['schedule' => $request->schedule]);

                $this->deleteClassSchedules($class->id);

                $schedule   = preg_replace('/\s+/', ' ', trim($request->schedule));
                $schedules = explode(", ", $schedule);
                foreach($schedules as $sched)
                {
                    $splits = preg_split('/ ([MTWHFSU]+) /', $sched, -1, PREG_SPLIT_DELIM_CAPTURE);
    
                    $times = $splits[0];
                    $days  = $splits[1];
                    $room  = $splits[2];
    
                    $times = explode("-", $times);
                    $timefrom = Carbon::parse($times[0])->format('H:i:s');
                    $timeto   = Carbon::parse($times[1])->format('H:i:s');

                    $room_info = $this->checkScheduleRoomifExist($room);
    
                    $splitdays = preg_split('/(.[HU]?)/' ,$days, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
                    $classesSchedules = [];
                    foreach($splitdays as $key => $day)
                    {
                        $classesSchedules[] =  new ClassesSchedule([
                            'from_time' => $timefrom,
                            'to_time' => $timeto,
                            'day' => $day,
                            'room_id' => $room_info->id,
                            'schedule_id' => $schedule_info->id
                        ]);
                    }//end of split days
                }

                $class->classschedules()->saveMany($classesSchedules);

                $data = $request->validated()+['schedule_id' => $schedule_info->id];
            } 
        }

        $class->update($data);

        //IF DISSOVED IS EQUAL TO 1, REMOVE ALL STUDENTS ENROLLED IN THE SUBJECT THEN RE-ASSESS

    }

}
