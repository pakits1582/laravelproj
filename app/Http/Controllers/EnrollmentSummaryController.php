<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\PeriodService;

class EnrollmentSummaryController extends Controller
{
    public function __construct()
    {
        Helpers::setLoad(['jquery_enrollmentsummary.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $enrolled_students = $this->enrolledstudents(session('current_period'));
        $enrollment_summary = $this->processAllEnrolled($enrolled_students);

        return view('enrollmentsummary.index', compact('periods', 'enrollment_summary'));
    }

    public function filtersummary(Request $request)
    {
        $enrolled_students = $this->enrolledstudents($request->period_id, $request->college);
        $enrollment_summary = $this->processAllEnrolled($enrolled_students);

        return view('enrollmentsummary.summary', compact('enrollment_summary'));
    }

    public function enrolledstudents($period, $college = '')
    {
        $query = Enrollment::with([
            'student', 
            'student.user' => function($query) {
                $query->select('id', 'idno');
            },
            'program' => ['level', 'collegeinfo']
        ]);

        $query->where('period_id', $period)->where('withdrawn', 0)->where('cancelled', 0)->where('assessed', 1);
        
        $query->when(isset($college) && !empty($college), function ($query) use($college) {
            $query->whereHas('program.collegeinfo', function($query) use($college){
                $query->where('colleges.id', $college);
            });
        });

        $enrolled_students = $query->get();

        return $enrolled_students;
    }

    public function processAllEnrolled($enrolled_students)
    {
        $grouped  = [];

        if($enrolled_students)
        {
            foreach ($enrolled_students as $key => $student) {
                $college_id           = $student->program->collegeinfo->id;
                $program_id           = $student->program->id;
                $year_level           = $student->year_level;

                if (!isset($grouped[$college_id])) {
                    $grouped[$college_id] = [
                        'id' => $college_id,
                        'college_name' => $student->program->collegeinfo->name,
                        'college_code' => $student->program->collegeinfo->code,
                        'programs' => []
                    ];
                }

                if (!isset($grouped[$college_id]['programs'][$program_id])) {
                    $grouped[$college_id]['programs'][$program_id] = [
                        'program_code' =>  $student->program->code,
                        'program_name' =>  $student->program->name,
                        'year_level'   => [
                            '1' => [
                                'res' => 0,
                                'con' => 0,
                            ],
                            '2' => [
                                'res' => 0,
                                'con' => 0,
                            ],
                            '3' => [
                                'res' => 0,
                                'con' => 0,
                            ],
                            '4' => [
                                'res' => 0,
                                'con' => 0,
                            ],
                            '5' => [
                                'res' => 0,
                                'con' => 0,
                            ],
                        ]
                    ];
                }

                if(isset($grouped[$college_id]['programs'][$program_id]['year_level'][$year_level]))
                {
                    if($student->validated == 1)
                    {
                        $grouped[$college_id]['programs'][$program_id]['year_level'][$year_level]['con']++;
                    }else{
                        $grouped[$college_id]['programs'][$program_id]['year_level'][$year_level]['res']++;
                    }
                   
                }
            }
        }

        $grouped = array_values($grouped);
        foreach ($grouped as &$group) {
            $group['programs'] = array_values($group['programs']);
        }

        return $grouped;
    }
}
