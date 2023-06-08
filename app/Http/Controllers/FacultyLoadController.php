<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\PeriodService;

class FacultyLoadController extends Controller
{
    public function __construct()
    {
        Helpers::setLoad(['jquery_facultyload.js', 'select2.full.min.js', 'jquery-dateformat.min.js']);
    }

    
    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $faculty_loads = $this->facultyload(session('current_period'));

        return view('facultyload.index', compact('periods', 'faculty_loads'));
    }

    public function facultyload($period_id, $instructor_id = '')
    {
        $query = Classes::with([
            'instructor' => function ($query) {
                $query->orderBy('last_name', 'ASC');
            },
            'schedule',
            'curriculumsubject.subjectinfo',
            'sectioninfo'
        ])
            ->where('period_id', $period_id)
            ->where('dissolved', '!=', 1)
            ->whereNull('merge')
            ->whereNotNull('slots');
        
        $query->when(isset($instructor_id) && !empty($instructor_id), function ($query) use ($instructor_id) {
            $query->whereHas('instructor', function ($query) use ($instructor_id) {
                $query->where('instructor_id', $instructor_id);
            });
        });
        
        $classes = $query->get();
        
        return $classes;
        
    }
}
