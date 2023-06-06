<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Assessment;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\AdddropService;
use App\Services\ClassesService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateEnrollmentRequest;

class AdddropController extends Controller
{
    protected $adddropService;

    public function __construct(AdddropService $adddropService)
    {
        $this->adddropService = $adddropService;
        Helpers::setLoad(['jquery_adddrop.js', 'select2.full.min.js']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adddrop.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEnrollmentRequest $request)
    {
        $enrollment = Enrollment::with(['assessment', 'enrolled_class_schedules', 'enrolled_classes' => ['class.schedule']])->findOrFail($request->enrollment_id);

        DB::beginTransaction();

        $enrollment->student()->update([
            'program_id' => $request->program_id,
            'year_level' => $request->year_level,
            'curriculum_id' => $request->curriculum_id,
        ]);

        $enrollment->update($request->validated());

        if ($enrollment->enrolled_classes->count() > 0) 
        {
            $enroll_class_schedules = [];

            foreach ($enrollment->enrolled_classes as $key => $enrolled_class) 
            {
                if(!is_null($enrolled_class->class->schedule_id))
                {
                    $class_schedules = (new ClassesService())->processSchedule($enrolled_class->class->schedule->schedule);

                    foreach ($class_schedules as $key => $class_schedule) 
                    {
                        foreach ($class_schedule['days'] as $key => $day) {
                            $enroll_class_schedules[] = [
                                'enrollment_id' => $request->enrollment_id,
                                'class_id' => $enrolled_class->class_id,
                                'from_time' => $class_schedule['timefrom'],
                                'to_time' => $class_schedule['timeto'],
                                'day' => $day,
                                'room' => $class_schedule['room'],
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                    }
                }
            }
            $enrollment->enrolled_class_schedules()->delete();
            $enrollment->enrolled_class_schedules()->insert($enroll_class_schedules);
        }

        DB::commit();

        return $enrollment->assessment->id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
