<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\ClassesService;
use App\Models\SectionMonitoring;
use Illuminate\Support\Facades\DB;

class ClassesSlotsMonitoringController extends Controller
{
    protected $classesService;

    public function __construct(ClassesService $classesService)
    {
        $this->classesService = $classesService;
        Helpers::setLoad(['jquery_slotsmonitoring.js', 'select2.full.min.js']);
    }

    public function index()
    {
        $classes = $this->slotmonitoring(session('current_period'));
        $classeswithslots = $this->getClassesSlots($classes);
        $sections_offered = SectionMonitoring::with(['section'])->where('period_id', session('current_period'))->get();

        return view('class.slotsmonitoring.index', compact('classeswithslots', 'sections_offered'));
    }

    public function slotmonitoring($period_id, $educational_level_id = '', $college_id = '', $section_id = '', $keyword = '')
    {
        $query = Classes::query();
        $query->select(
            'classes.*',
            DB::raw("COALESCE(SUM(CASE WHEN enrollments.acctok = '1' AND enrollments.assessed = '1' THEN enrollments.assessed ELSE 0 END), 0) AS assessedinclass"),
            DB::raw("COALESCE(SUM(CASE WHEN enrollments.validated = '1' THEN 1 ELSE 0 END), 0) AS validatedinclass"),
            DB::raw("COALESCE(SUM(enrolled_classes.class_id = classes.id), 0) AS enrolledinclass"),
            'subjects.code as subject_code',
            'subjects.name as subject_name',
            'instructors.last_name',
            'instructors.first_name',
            'instructors.middle_name',
            'instructors.name_suffix',
            'schedules.schedule',
            'sections.code as section_code',
            DB::raw("CONCAT(instructors.last_name, ', ', instructors.first_name, ' ', instructors.name_suffix, ' ', instructors.middle_name) AS full_name"),
            'mergeclass.code AS mothercode',
            'mergeclass.id AS mothercodeid',
            'mergeclass.slots AS mothercodeslots',
        );
        $query->join('curriculum_subjects', 'classes.curriculum_subject_id', '=', 'curriculum_subjects.id');
        $query->join('subjects', 'curriculum_subjects.subject_id', '=', 'subjects.id');
        $query->join('sections', 'classes.section_id', '=', 'sections.id');
        $query->join('programs', 'sections.program_id', '=', 'programs.id');
        $query->leftJoin('instructors', 'classes.instructor_id', '=', 'instructors.id');
        $query->leftJoin('schedules', 'classes.schedule_id', '=', 'schedules.id'); 
        
        $query->leftJoin('enrolled_classes', 'classes.id', '=', 'enrolled_classes.class_id');
        $query->leftJoin('enrollments', 'enrolled_classes.enrollment_id', '=', 'enrollments.id');
        $query->leftJoin('classes AS mergeclass', 'classes.merge', '=', 'mergeclass.id');

        $query->where('classes.period_id', $period_id);

        $query->when(isset($educational_level_id) && !empty($educational_level_id), function ($query) use ($educational_level_id) {
            $query->where('programs.educational_level_id', $educational_level_id);
        });

        $query->when(isset($college_id) && !empty($college_id), function ($query) use ($college_id) {
            $query->where('programs.college_id', $college_id);
        });

        $query->when(isset($section_id) && !empty($section_id), function ($query) use ($section_id) {
            $query->where('classes.section_id', $section_id);
        });

        $query->when(isset($keyword) && !empty($keyword), function ($query) use ($keyword) {
            $query->where('subjects.code', 'like', $keyword.'%')
                ->orWhere('subjects.name', 'like', $keyword.'%');
        });

        $query->groupBy('classes.id')
            ->orderBy('subjects.code')
            ->orderBy('classes.id');

        $classes = $query->get();

        return $classes;
    }

    public function getClassesSlots($classes)
    {
        $classes_array = [];

        if($classes)
        {
            foreach ($classes as $key => $v) 
            {
                $totalassessed  = 0;
            	$totalvalidated = 0;
                $totalenrolled  = 0;
            	$remainingslot  = 0;

            	$classes_array[$key]['class_id']      = $v->id;
            	$classes_array[$key]['class_code']    = $v->code;
            	$classes_array[$key]['section_code']  = $v->section_code;
            	$classes_array[$key]['subject_code']  = $v->subject_code;
            	$classes_array[$key]['subject_name']  = $v->subject_name;
            	$classes_array[$key]['units']         = $v->units;
            	$classes_array[$key]['schedule']      = $v->schedule;
            	$classes_array[$key]['tutorial']      = $v->tutorial;
            	$classes_array[$key]['dissolved']     = $v->dissolved;
            	$classes_array[$key]['mothercode']    = $v->mothercode;
            	$classes_array[$key]['instructor_id'] = $v->instructor_id;
            	$classes_array[$key]['last_name']     = $v->last_name;
            	$classes_array[$key]['first_name']    = $v->first_name;
            	
                $assessedinclass  =  ($v->assessedinclass == "") ? 0 : $v->assessedinclass;
            	$validatedinclass = ($v->validatedinclass == "") ? 0 : $v->validatedinclass;
                $enrolledinclass  = ($v->enrolledinclass == "") ? 0 : $v->enrolledinclass;

                if($v->ismother > 0){
					$classes_array[$key]['slots'] = $v->slots;
					$class_id = $v->id;

					$children = array_filter($classes->toArray(), function($record) use($class_id){
						return $record['mothercodeid'] == $class_id;
					});

					$sum_assessedinclass  = 0;
					$sum_validatedinclass = 0;
                    $sum_enrolledinclass  = 0;

					if($children)
					{
						foreach ($children as $child) 
						{
							$sum_assessedinclass  += $child['assessedinclass'];
            				$sum_validatedinclass += $child['validatedinclass'];
                            $sum_enrolledinclass  += $child['enrolledinclass'];
						}
					}

					$totalassessed  = $assessedinclass + $sum_assessedinclass;
					$totalvalidated = $validatedinclass + $sum_validatedinclass;
                    $totalenrolled  = $enrolledinclass + $sum_enrolledinclass;
					$remainingslot  = $v->slots - $totalenrolled;

				}else{//not a mothercode
					/*
						IF SUBJECT MERGE IN != 0
						SUBJECT IS MERGED GET ENROLLED IN MOTHERCODE AND ALL MERGED CHILD
					*/
					if($v->merge != 0)
					{
						$motherclass = $v->merge;

						$mother = array_filter($classes->toArray(), function($record) use($motherclass){
							return $record['id'] == $motherclass;
						});

						$sum_mother_assessedinclass  = 0;
						$sum_mother_validatedinclass = 0;
                        $sum_mother_enrolledinclass  = 0;

						if($mother)
						{
							foreach ($mother as $m) 
							{
								$sum_mother_assessedinclass  += $m['assessedinclass'];
	            				$sum_mother_validatedinclass += $m['validatedinclass'];
                                $sum_mother_enrolledinclass  += $m['enrolledinclass'];
							}
						}

						$children = array_filter($classes->toArray(), function($record) use($motherclass){
							return $record['mothercodeid'] == $motherclass;
						});

						$sum_assessedinclass  = 0;
						$sum_validatedinclass = 0;
                        $sum_enrolledinclass  = 0;

						if($children)
						{
							foreach ($children as $child) 
							{
								$sum_assessedinclass  += $child['assessedinclass'];
	            				$sum_validatedinclass += $child['validatedinclass'];
                                $sum_enrolledinclass  += $child['enrolledinclass'];
							}
						}
                        
                        $classes_array[$key]['slots'] = $v->mothercodeslots;
						$totalassessed  += $sum_assessedinclass + $sum_mother_assessedinclass;
						$totalvalidated += $sum_validatedinclass + $sum_mother_validatedinclass;
                        $totalenrolled  += $sum_enrolledinclass + $sum_mother_enrolledinclass;
                        $remainingslot  = $v->mothercodeslots - $totalenrolled;

					}else{
						$classes_array[$key]['slots'] = $v->slots ?? 0;
						$totalassessed  = $assessedinclass;
						$totalvalidated = $validatedinclass;
                        $totalenrolled  = $enrolledinclass;
						$remainingslot  = $v->slots - $totalenrolled;
					}	
				}

                $classes_array[$key]['totalassessed']  = $totalassessed;
                $classes_array[$key]['totalvalidated'] = $totalvalidated;
                $classes_array[$key]['totalenrolled']  = $totalenrolled;
                $classes_array[$key]['remainingslot']  = $remainingslot;
            }
        }

        return $classes_array;
    }

    public function filterslotmonitoring(Request $request)
    {
        $classes = $this->slotmonitoring(session('current_period'), $request->educational_level, $request->college, $request->section_id, $request->keyword);
        $classeswithslots = $this->getClassesSlots($classes);

        return view('class.slotsmonitoring.return_slotmonitoring', compact('classeswithslots'));
    }
}
