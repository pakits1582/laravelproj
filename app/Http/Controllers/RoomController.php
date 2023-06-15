<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Services\Assessment\AssessmentService;
use App\Services\ClassesService;

class RoomController extends Controller
{
    public function __construct()
    {
        Helpers::setLoad(['jquery_room.js', 'select2.full.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Room::query()->orderBy('code');
        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', '%'.$request->keyword.'%')
                ->orWhere('name', 'like', '%'.$request->keyword.'%');
            });
        }

        $rooms =  $query->paginate(10);

        if($request->ajax())
        {
            return view('room.return_rooms', compact('rooms'));
        }
        return view('room.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('room.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoomRequest $request)
    {
        $insert = Room::firstOrCreate(['code' => $request->code, 'name' => $request->name], $request->validated());

        if ($insert->wasRecentlyCreated) {
            return back()->with(['alert-class' => 'alert-success', 'message' => 'Room sucessfully added!']);
        }

        return back()->with(['alert-class' => 'alert-danger', 'message' => 'Duplicate entry, room already exists!'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        return view('room.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return back()->with(['alert-class' => 'alert-success', 'message' => 'Room sucessfully updated!']);
    }

    public function roomassignment()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $room_assignments = $this->roomassignments(session('current_period'));
        $rooms = $room_assignments->unique('room_code');
        $grouped_rooms = $this->groupRooms($room_assignments);

        return view('room.assignment.index', compact('periods', 'rooms', 'grouped_rooms'));
    }

    public function roomassignments($period_id, $room_id = '')
    {
        $query = Classes::query();
        $query->select(
            'classes.*',
            'subjects.code as subject_code',
            'subjects.name as subject_name',
            'instructors.last_name',
            'instructors.first_name',
            'instructors.middle_name',
            'instructors.name_suffix',
            'schedules.schedule',
            'sections.code as section_code',
            'classes_schedules.day',
            'classes_schedules.from_time',
            'rooms.code AS room_code',
            'rooms.id AS room_id',
            DB::raw("CONCAT(instructors.last_name, ', ', instructors.first_name, ' ', instructors.name_suffix, ' ', instructors.middle_name) AS full_name")
        );
        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id');
        $query->leftJoin('classes_schedules', 'classes.id', '=', 'classes_schedules.class_id');
        $query->leftJoin('rooms', 'classes_schedules.room_id', '=', 'rooms.id');
        $query->where('classes.period_id', $period_id)
            ->where('classes.dissolved', '!=', 1)
            ->whereNull('classes.merge')
            ->whereNotNull('classes.slots');

        $query->when(isset($room_id) && !empty($room_id), function ($query) use ($room_id) {
            $query->where('classes_schedules.room_id', $room_id);
        });

        $query->groupBy('classes.id')
            ->orderBy('rooms.code')
            ->orderByRaw("FIELD(classes_schedules.day, 'M', 'T', 'W', 'TH', 'F', 'S', 'SU')")
            ->orderBy('classes_schedules.from_time');

        $classes = $query->get();

        return $classes;
    }

    public function groupRooms($room_assignments)
    {
        $rooms = [];
        foreach ($room_assignments as $room) 
        {
            if($room->room_code)
            {
                $room_code = $room->room_code;
                if (!isset($rooms[$room_code])) 
                {
                    $rooms[$room_code] =[
                        'room' => $room_code,
                        'room_id' => $room->room_id,
                        'classes' => array()
                    ];
                }
                $rooms[$room_code]['classes'][] = 
                    [
                        'schedule' => $room->schedule,
                        'subject_code' => $room->subject_code,
                        'subject_name' => $room->subject_name,
                        'full_name' => $room->full_name,
                        'section_code' => $room->section_code,
                        'class_code' => $room->code,
                        'units' => $room->units,
                        'last_name' => $room->last_name,
                        'first_name' => $room->first_name,
                        'middle_name' => $room->middle_name,
                        'name_suffix' => $room->name_suffix,
                        'instructor_id' => $room->instructor_id,
                    ];
            }
        }
        return array_values($rooms);
    }

    public function filterroomassignment(Request $request)
    {
        $room_assignments = $this->roomassignments($request->period_id, $request->room_id);
        $grouped_rooms = $this->groupRooms($room_assignments);

        return view('room.assignment.return_roomassignment', compact('grouped_rooms'));
    }
    
    public function scheduletable(Request $request)
    {
        $class_schedules = (new ClassesService())->scheduletableClassSchedules('','','',$request->room_id);
        $with_faculty = true;

        return view('class.schedule_table', compact('class_schedules', 'with_faculty'));
    }
}
