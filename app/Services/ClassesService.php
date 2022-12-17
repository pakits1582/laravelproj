<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Schedule;
use App\Models\ClassesSchedule;
use App\Models\SectionMonitoring;
use App\Models\CurriculumSubjects;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

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

    public function storeClassSubjects($request, $curriculumService)
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
                'section_id' => $request->section,
                'curriculum_subject_id' => $subject,
                'units' => $curriculum_subject->subjectinfo->units,
                'tfunits' => $curriculum_subject->subjectinfo->tfunits,
                'loadunits' => $curriculum_subject->subjectinfo->loadunits,
                'lecunits' => $curriculum_subject->subjectinfo->lecunits,
                'labunits' => $curriculum_subject->subjectinfo->labunits,
                'hours' => $curriculum_subject->subjectinfo->hours,
                'isprof' => $curriculum_subject->subjectinfo->professional,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        Classes::insert($classes);

        $this->insertSectionMonitoring($request->section);

    }

    public function classSubjects($section, $period, $with_dissolved = true)
    {
        $query = Classes::with([
                'sectioninfo',
                'instructor', 
                'schedule',
                'enrolledstudents.enrollment',
                'mergetomotherclass',
                'curriculumsubject' => fn($query) => $query->with('subjectinfo', 'curriculum','prerequisites', 'corequisites', 'equivalents')
            ])->where('section_id', $section)->where('period_id', $period);
        
        if($with_dissolved === false)
        {
            $query->where('dissolved', '!=', 1);
        }

        return $query->get();
    }

    public function classSubject($class_id)
    {
        return Classes::with([
            'sectioninfo',
            'instructor', 
            'schedule',
            'enrolledstudents.enrollment',
            'mergetomotherclass',
            'curriculumsubject' => fn($query) => $query->with('subjectinfo', 'curriculum','prerequisites', 'corequisites', 'equivalents')
        ])->where('id', $class_id)->firstOrFail();
    }

    public function checkScheduleRoomifExist($room)
    {
        return Room::where('id', $room)->orWhere('code', $room)->first();
    }

    public function checkConflictRoom($class_id, $room_id, $start_time, $end_time, $day)
    {
        $query = Classes::with(['sectioninfo', 'curriculumsubject.subjectinfo', 'instructor', 'schedule', 'classschedules'])
                ->join('classes_schedules', 'classes.id', '=', 'classes_schedules.class_id')
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
                ->leftjoin('classes_schedules', 'classes.id', '=', 'classes_schedules.class_id')
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
                ->leftjoin('classes_schedules', 'classes.id', '=', 'classes_schedules.class_id')
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

    public function deleteClassSchedules($class_id)
    {
        ClassesSchedule::where('class_id', $class_id)->delete();
    }

    public function generateCode()
    {
        $classes_withnocode = Classes::with('sectioninfo.programinfo.collegeinfo')->where('code', NULL)->orWhere('code', '=', '')->where('period_id', session('current_period'))->get();

        if(count($classes_withnocode)){
            foreach ($classes_withnocode as $key => $class_withnocode) {
                $class_code = $class_withnocode->sectioninfo->programinfo->collegeinfo->class_code ?? 'NC';

                $last_code = DB::table('classes')->select(DB::raw('MAX(CAST(SUBSTRING(code, 2, length(code) -1) AS UNSIGNED)) AS lastcode'))->where('code','like', $class_code.'%')->where('period_id', session('current_period'))->get()[0];
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

        if($schedule !== '')
        {
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
        }

        return $schedule_array;
    }

    public function checkRoomSchedule($request)
    {
        $error = '';

        if($request->filled('schedule'))
        {
            $schedule_array = $this->processSchedule($request->schedule);

            foreach ($schedule_array as $key => $sched) 
            {
                $room_info = $this->checkScheduleRoomifExist($sched['room']);
                if(!$room_info){
                    $error .= 'Room '.$sched['room'].' does not exist!</br>';
                }else{
                    if($sched['timefrom'] >= $sched['timeto'])
                    {
                        $error .= 'Schedule '.$sched['raw_sched'].' TIME FROM is greater than TIME TO!</br>';
                    }else{
                        if($sched['days']){
                            $conflicts = [];
                            foreach ($sched['days'] as $key => $day) 
                            {
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

        if($request->filled('schedule'))
        {
            $schedule_array = $this->processSchedule($request->schedule);

            foreach ($schedule_array as $key => $sched) 
            {
                $conflicts = [];
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
                        //CHECK CONFLICT INSTRUCTOR
                        $faculty_conflicts = $this->checkConflictFaculty($sched['timefrom'], $sched['timeto'], $day, $request->class_id, $request->instructor_id);
                        if(!$faculty_conflicts->isEmpty())
                        {
                            foreach ($faculty_conflicts as $key => $faculty_conflict) {
                                $conflicts[] = [
                                    'class_code'   => ($faculty_conflict->code) ?? '',
                                    'section_code' => $faculty_conflict->sectioninfo->code,
                                    'subject_code' => $faculty_conflict->curriculumsubject->subjectinfo->code,
                                    'schedule' => $faculty_conflict->schedule->schedule,
                                    'conflict_from' => 'Faculty'
                                ];
                            }
                        }
                    }
                }//end of days
            }
           
            $temp = array_unique(array_column($conflicts, 'conflict_from'));
            $allconflicts = array_intersect_key($conflicts, $temp);
        }
        
        return $allconflicts;
    }

    public function updateClassSubject($class, $request)
    {
        $data = $request->validated();

        if($request->filled('schedule'))
        {
            if($request->schedule !== $class->schedule->schedule)
            {
                $schedule_info = Schedule::firstOrCreate(['schedule' => $request->schedule], ['schedule' => $request->schedule]);

                $this->deleteClassSchedules($class->id);

                $schedule_array = $this->processSchedule($request->schedule);
               
                foreach ($schedule_array as $key => $sched) 
                {
                    $room_info = $this->checkScheduleRoomifExist($sched['room']);

                    $classesSchedules = [];
                    foreach ($sched['days'] as $key => $day) {
                        $classesSchedules[] =  new ClassesSchedule([
                                        'from_time' => $sched['timefrom'],
                                        'to_time' => $sched['timeto'],
                                        'day' => $day,
                                        'room_id' => $room_info->id,
                                        'schedule_id' => $schedule_info->id
                                    ]);
                    }//end of days
                }
                $class->classschedules()->saveMany($classesSchedules);

                $data = $request->validated()+['schedule_id' => $schedule_info->id];
            } 
        }

        $class->update($data);

        //IF DISSOVED IS EQUAL TO 1, REMOVE ALL STUDENTS ENROLLED IN THE SUBJECT THEN RE-ASSESS

    }

    public function storeCopyClass($request)
    {
        if($request->section_copyfrom === $request->section_copyto  && $request->period_copyfrom === $request->period_copyto)
        {
            return [
                'success' => false,
                'message' => 'You are trying to copy from the same section where you want to save!',
                'alert' => 'alert-danger',
                'status' => 401
            ];
        }else{
            $section_subjects = $this->classSubjects($request->section_copyfrom, $request->period_copyfrom);

            if(!$section_subjects->isEmpty())
            {
                $classes = [];
                foreach ($section_subjects as $key => $section_subject) {
                    $classes[] = [
                        'period_id' => session('current_period'),
                        'section_id' => $request->section,
                        'curriculum_subject_id' => $section_subject->curriculumsubject->id,
                        'units' => $section_subject->units,
                        'tfunits' => $section_subject->tfunits,
                        'loadunits' => $section_subject->loadunits,
                        'lecunits' => $section_subject->lecunits,
                        'labunits' => $section_subject->labunits,
                        'hours' => $section_subject->hours,
                        'slots' => $section_subject->slots,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }

                Classes::insert($classes);

                $this->insertSectionMonitoring($request->section);

                return [
                    'success' => true,
                    'message' => 'Section class subjects successfully copied!',
                    'alert' => 'alert-success',
                    'status' => 200
                ];
            }

            return [
                    'success' => false,
                    'message' => 'Section has no class subjects, nothing to copy!',
                    'alert' => 'alert-danger',
                    'status' => 401
                ];
        }
    }

    public function insertSectionMonitoring($section_id)
    {
        $section = Section::with('programinfo')->findOrFail($section_id);

        if($section)
        {
            $arr = [
                'section_id' => $section->id,
                'minimum_enrollees' => $section->minenrollee,
                'period_id' => session('current_period')
            ];

            SectionMonitoring::firstOrCreate($arr, $arr);
        }
    }

    public function deleteClassSubject($class)
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
        
        return $class;
        // if($class->has('enrolledstudents')->get())
        // {
        //     return [
        //         'success' => false,
        //         'message' => 'There are students currently enrolled in the class subject. You can not delete selected class subject!',
        //         'alert' => 'alert-danger',
        //         'status' => 401
        //     ];
        // }

		//delete from classes
		//delete from classes_schedules
		//delete from enrolled_classes
		//delete from enrolled_classes_schedules
		//check if last class in the section, if true delete in section_monitorings
    }

    public function searchClassSubjectsToMerge($searchcodes)
    {
        $searchcodes=  explode(",",preg_replace('/\s+/', ' ', trim($searchcodes)));
        $searchcodes= array_map('trim', $searchcodes);

        $query = Classes::with([
            'sectioninfo',
            'instructor', 
            'schedule',
            'enrolledstudents.enrollment',
            'curriculumsubject' => fn($query) => $query->with('subjectinfo')
        ])->where('period_id', session('current_period'));

        $query->where(function($query) use($searchcodes){
            foreach($searchcodes as $key => $code){
                $query->orwhere(function($query) use($code){
                    $query->orWhere('code', 'LIKE', '%'.$code.'%');
                    $query->orwhereHas('curriculumsubject.subjectinfo', function($query) use($code){
                        $query->where('subjects.code', 'LIKE', '%'.$code.'%');
                    });
                });
            }
        });


        //return $query->toSql();
        return $query->get()->sortBy('curriculumsubject.subjectinfo.code');
    }

    public function displayEnrolledToClassSubject($class)
    {
        $class->load([
            'sectioninfo',
            'curriculumsubject.subjectinfo', 
            'instructor', 
            'schedule',
            'enrolledstudents'=> [
                'class',
                'enrollment' => [
                    'section',
                    'student' => [
                        'user',
                        'program'
                    ]
                ]
            ]
        ]);

        //return $class;

        $enrolled_students = new Collection();

        if($class->ismother === 1)
        {
            $class->load([
                    'merged' => [
                        'curriculumsubject' => fn($query) => $query->with('subjectinfo'),
                        'enrolledstudents'=> [
                            'class',
                            'enrollment' => [
                                'section',
                                'student' => [
                                    'user',
                                    'program'
                                ]
                            ]
                        ]
                    ]
            ]);
            $enrolled_students = $enrolled_students->merge($class->enrolledstudents);
        
            foreach ($class->merged as $key => $merged) {
                $enrolled_students = $enrolled_students->merge($merged->enrolledstudents);
            }

        }else if($class->merge !== NULL){
            $class->load([
                'mergetomotherclass' => [
                    'curriculumsubject' => fn($query) => $query->with('subjectinfo'),
                    'enrolledstudents'=> [
                        'class',
                        'enrollment' => [
                            'section',
                            'student' => [
                                'user',
                                'program'
                            ]
                        ]
                    ],
                    'merged' => [
                        'curriculumsubject' => fn($query) => $query->with('subjectinfo'),
                        'enrolledstudents'=> [
                            'class',
                            'enrollment' => [
                                'section',
                                'student' => [
                                    'user',
                                    'program'
                                ]
                            ]
                        ]
                    ],
                ]
            ]);

            $enrolled_students = $enrolled_students->merge($class->mergetomotherclass->enrolledstudents);

            foreach ($class->mergetomotherclass->merged as $key => $mother_merged) {
                $enrolled_students = $enrolled_students->merge($mother_merged->enrolledstudents);
            }
        }else{
            $enrolled_students = $enrolled_students->merge($class->enrolledstudents);
        }
       
        return ['class' => $class, 'enrolled_students' => $enrolled_students];
    }

}
