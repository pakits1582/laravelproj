<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use App\Services\ClassesService;
use Illuminate\Http\Request;
use App\Services\PeriodService;
use Illuminate\Support\Facades\DB;

class ClassListController extends Controller
{
    public function __construct()
    {
        Helpers::setLoad(['jquery_classlist.js', 'select2.full.min.js']);
    }

    public function index()
    {
        $periods = (new PeriodService)->returnAllPeriods(0, true, 1);
        $classlist = $this->classlist(session('current_period'), '', '', 100);

        return view('classlist.index', compact('periods', 'classlist'));
    }

    public function classlist($period_id, $criteria = '', $keyword = '', $limit = 0)
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
        );

        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id'); 
        $query->where('classes.period_id', $period_id);

        $query->when(isset($criteria) && !empty($criteria) && !empty($keyword) , function ($query) use ($criteria, $keyword) 
        {
            switch ($criteria) 
            {
                case 'code':
                    $query->where('classes.code', $keyword);
                    break;
                case 'instructor':
                    $query->where('instructors.last_name', 'like', $keyword.'%')->orWhere('instructors.first_name', 'like', $keyword.'%');
                    break;
                case 'subject':
                    $query->where('subjects.code', 'like', $keyword.'%');
                    break;
            }
        });

        $query->orderBy('subjects.code')
            ->orderBy('classes.id');

        $query->when(isset($limit) && $limit != 0 , function ($query) use ($limit) 
        {
            $query->limit($limit);
        });
    
        $classes = $query->get();

        return $classes;
    }

    public function filterclasslist(Request $request)
    {
        $classlist = $this->classlist($request->period_id, $request->criteria, $request->keyword);

        return view('classlist.return_classlist', compact('classlist'));
    }

    public function show(Classes $class)
    {
        $class_info = (new ClassesService())->displayEnrolledToClassSubject($class);

        return view('classlist.return_classlist_info', $class_info);
        
    }
}
