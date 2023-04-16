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

        $subjects = CurriculumSubjects::with(['subjectinfo'])->whereIn("id", $request->subjects)->get();
        foreach ($subjects as $key => $subject) 
        {
            $classes[] = [
                'period_id' => session('current_period'),
                'section_id' => $request->section,
                'curriculum_subject_id' => $subject->id,
                'units' => $subject->subjectinfo->units,
                'tfunits' => $subject->subjectinfo->tfunits,
                'loadunits' => $subject->subjectinfo->loadunits,
                'lecunits' => $subject->subjectinfo->lecunits,
                'labunits' => $subject->subjectinfo->labunits,
                'hours' => $subject->subjectinfo->hours,
                'isprof' => $subject->subjectinfo->professional,
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
                'enrolledstudents',
                'curriculumsubject' => [
                    'subjectinfo', 
                    'curriculum',
                    // 'prerequisites' => ['curriculumsubject.subjectinfo'], 
                    // 'corequisites', 
                    // 'equivalents'
                ]
            ])->where('section_id', $section)->where('period_id', $period);
        
        $query->when($with_dissolved === false, function ($q) {
            return $q->where('dissolved', '!=', 1);
        });

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

                //$class_withnocode->code = $newcode;
                $class_withnocode->update(['code' => $newcode]);
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

    public function classesWithSchedules()
    {
        $classes_with_schedules = Classes::with([
            'sectioninfo',
            'curriculumsubject.subjectinfo', 
            'instructor', 
            'schedule',
            'classschedules' => ['roominfo']])->where('period_id', session('current_period'))->whereNotNull('schedule_id')->get();

        return $classes_with_schedules;
    }

    public function checkRoomSchedule($request)
    {
        $errors = [];

        if($request->filled('schedule'))
        {
            $schedule_array = $this->processSchedule($request->schedule);
            $rooms = Room::all();
            $classes_with_schedules = $this->classesWithSchedules();
           
            foreach ($schedule_array as $key => $sched) 
            {
                $room = $sched['room'];
                $room_info = $rooms->firstWhere('code', $room);

                if (!$room_info) 
                {
                    $errors[] = 'Room <strong>'.$room.'</strong> does not exists!';
                }else{
                    if (Carbon::parse($sched['timefrom'])->greaterThanOrEqualTo(Carbon::parse($sched['timeto']))) 
                    {
                        $errors[] = 'Schedule '.$sched['raw_sched'].' TIME FROM is greater than TIME TO!</br>';
                    }else{
                        if($room_info->excludechecking === 0)
                        {
                            if($sched['days'])
                            {
                                $conflicts = [];
                                $timefrom = $sched['timefrom'];
                                $timeto =  $sched['timeto'];

                                foreach ($sched['days'] as $key => $day) 
                                {
                                    foreach ($classes_with_schedules as $key => $classes_with_schedule) 
                                    {
                                        foreach ($classes_with_schedule->classschedules as $key => $schedule) {
                                            if ($schedule->day == $day &&
                                                ($schedule->from_time >= $timefrom && $schedule->from_time < $timeto ||
                                                 $schedule->to_time > $timefrom && $schedule->to_time <= $timeto) && $schedule->roominfo->code == $room && $classes_with_schedule->id != $request->class_id) {
                                                $conflicts[] = $classes_with_schedule;
                                            }
                                        }
                                    }
                                }

                                $conflicts = array_unique($conflicts);

                                if($conflicts)
                                {
                                    foreach ($conflicts as $key => $conflict) 
                                    {
                                        $errors[] = 'Room Conflict: ('.$conflict->sectioninfo->code.') ['.$conflict->curriculumsubject->subjectinfo->code.']  - '.$conflict->schedule->schedule.'<br>';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $errors;
    }

    public function checkConflicts($request)
    {
        $errors = [];

        if($request->filled('schedule'))
        {
            $schedule_array = $this->processSchedule($request->schedule);
            $classes_with_schedules = $this->classesWithSchedules();

            foreach ($schedule_array as $key => $sched) 
            {
                $conflicts_sections = [];
                $conflicts_faculty = [];
                $timefrom = $sched['timefrom'];
                $timeto =  $sched['timeto'];
                
                foreach ($sched['days'] as $key => $day) {

                    foreach ($classes_with_schedules as $key => $classes_with_schedule) 
                    {
                        foreach ($classes_with_schedule->classschedules as $key => $schedule) {
                            if ($schedule->day == $day && ($schedule->from_time >= $timefrom && $schedule->from_time < $timeto ||
                                    $schedule->to_time > $timefrom && $schedule->to_time <= $timeto) && $classes_with_schedule->section_id == $request->section && $classes_with_schedule->id != $request->class_id) {
                                $conflicts_sections[] = $classes_with_schedule;
                            }

                            if ($schedule->day == $day && ($schedule->from_time >= $timefrom && $schedule->from_time < $timeto ||
                                    $schedule->to_time > $timefrom && $schedule->to_time <= $timeto) && $classes_with_schedule->instructor_id == $request->instructor_id && $classes_with_schedule->id != $request->class_id) {
                                $conflicts_faculty[] = $classes_with_schedule;
                            }

                        }
                    }
                }//end of days
                $conflicts_sections = array_unique($conflicts_sections);

                if($conflicts_sections)
                {
                    foreach ($conflicts_sections as $key => $conflict) 
                    {
                        $errors[] = [
                            'class_code' => $conflict->code,
                            'section_code' => $conflict->sectioninfo->code,
                            'subject_code' => $conflict->curriculumsubject->subjectinfo->code,
                            'schedule' => $conflict->schedule->schedule,
                            'conflict_from' => 'Section'
                        ];
                    }
                }

                $conflicts_faculty = array_unique($conflicts_faculty);

                if($conflicts_faculty)
                {
                    foreach ($conflicts_faculty as $key => $conflict) 
                    {
                        $errors[] = [
                            'class_code' => $conflict->code,
                            'section_code' => $conflict->sectioninfo->code,
                            'subject_code' => $conflict->curriculumsubject->subjectinfo->code,
                            'schedule' => $conflict->schedule->schedule,
                            'conflict_from' => 'Faculty'
                        ];
                    }
                }
            }
        }
        
        return $errors;
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
                $rooms = Room::all();

                foreach ($schedule_array as $key => $sched) 
                {
                    $room_info = $rooms->firstWhere('code', $sched['room']);
                   
                    $classesSchedules = [];
                    foreach ($sched['days'] as $key => $day) {
                        $classesSchedules[] =  new ClassesSchedule([
                                        'from_time' => $sched['timefrom'],
                                        'to_time' => $sched['timeto'],
                                        'day' => $day,
                                        'room_id' => ($room_info) ? $room_info->id : '',
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
            // 'sectioninfo',
            // 'curriculumsubject.subjectinfo', 
            // 'instructor', 
            // 'schedule',
            'enrolledstudents'
        ]);
        
        //return $class;

        if($class->enrolledstudents->count())
        {
            return [
                'success' => false,
                'message' => 'There are students currently enrolled in the class subject. You can not delete selected class subject!',
                'alert' => 'alert-danger',
                'status' => 401
            ];
        }

        $class->delete();

        return [
            'success' => true,
            'message' => 'Selected class subject successfully deleted!',
            'alert' => 'alert-success',
            'status' => 200
        ];

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

    public function offeredClassesOfPeriod($period_id)
    {
        return Classes::query()
        ->select('classes.*', 'subjects.code as subject_code', 'classes.code as class_code')
        ->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id')
        ->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id')
        ->where('classes.period_id', $period_id)
        ->orderBy('subjects.code')
        ->get();
    }
}
